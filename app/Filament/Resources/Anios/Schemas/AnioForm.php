<?php

namespace App\Filament\Resources\Anios\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AnioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('fuente_id')
                    ->relationship('fuente', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('anio')
                    ->required()
                    ->maxLength(10)
                    ->regex('/^(?:[12][0-9]{3}|M{0,3}(?:CM|CD|D?C{0,3})(?:XC|XL|L?X{0,3})(?:IX|IV|V?I{0,3}))$/')
                    ->helperText('Numérico (1000-2999) o romano (ej: MMXXIV)')
                    ->placeholder('2024 o MMXXIV')
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn ($rule, $get) => $rule->where('fuente_id', $get('fuente_id')))
                    ->validationMessages([
                        'regex' => 'El año debe ser numérico (1000-2999) o en números romanos válidos.',
                        'unique' => 'Esta fuente ya tiene un registro para este año.',
                    ]),
            ]);
    }
}
