<?php

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;

class ActivityLogTable
{
    public static function make(?Model $record = null): RepeatableEntry
    {
        return RepeatableEntry::make('activities')
            ->label('Historial de Cambios')
            ->state(fn (?Model $record): array => $record?->activities()
                ->with('causer')
                ->latest()
                ->limit(100)
                ->get()
                ->map(fn ($activity) => [
                    'event' => $activity->description,
                    'causer' => $activity->causer?->name ?? 'Sistema',
                    'properties' => $activity->properties->toArray(),
                    'created_at' => $activity->created_at,
                ])
                ->toArray() ?? [])
            ->table([
                TableColumn::make('Evento')
                    ->width('120px')
                    ->alignment(Alignment::Center),
                TableColumn::make('Usuario')
                    ->width('180px'),
                TableColumn::make('Cambios')
                    ->width('auto'),
                TableColumn::make('Fecha y Hora')
                    ->width('160px')
                    ->alignment(Alignment::End),
            ])
            ->schema([
                TextEntry::make('event')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'Creado',
                        'updated' => 'Actualizado',
                        'deleted' => 'Eliminado',
                        default => ucfirst($state),
                    }),
                TextEntry::make('causer')
                    ->icon('heroicon-o-user')
                    ->default('Sistema'),
                TextEntry::make('properties')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) {
                            return 'Sin cambios';
                        }

                        $changes = [];
                        $excludedFields = ['updated_at', 'created_at', 'updated_by', 'created_by', 'id'];

                        // Cambios en actualización (cuando hay 'old' y 'attributes')
                        if (isset($state['old']) && isset($state['attributes'])) {
                            foreach ($state['old'] as $key => $oldValue) {
                                if (in_array($key, $excludedFields)) {
                                    continue;
                                }

                                $newValue = $state['attributes'][$key] ?? null;

                                // Comparar valores (considerar tipos)
                                if ($oldValue != $newValue) {
                                    $oldDisplay = is_null($oldValue) || $oldValue === '' ? '(vacío)' : $oldValue;
                                    $newDisplay = is_null($newValue) || $newValue === '' ? '(vacío)' : $newValue;
                                    $changes[] = "**{$key}**: {$oldDisplay} → {$newDisplay}";
                                }
                            }
                        }

                        // Creación (solo attributes, sin old)
                        if (empty($changes) && isset($state['attributes']) && ! isset($state['old'])) {
                            $relevant = array_diff_key(
                                $state['attributes'],
                                array_flip($excludedFields)
                            );

                            foreach ($relevant as $key => $value) {
                                if ($value !== null && $value !== '') {
                                    $changes[] = "**{$key}**: {$value}";
                                }
                            }
                        }

                        return empty($changes) ? 'Sin cambios registrados' : implode("  \n", $changes);
                    })
                    ->markdown()
                    ->default('Sin cambios'),
                TextEntry::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->default('—'),
            ])
            ->columnSpanFull()
            ->visible(fn (?Model $record): bool => $record !== null && $record->exists);
    }
}
