<?php

namespace App\Filament\Resources\Items\Infolists;

use App\Filament\Infolists\Components\ActivityLogTable;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Datos')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextEntry::make('titulo')
                                            ->label('Título')
                                            ->size('lg')
                                            ->weight('bold')
                                            ->columnSpanFull(),
                                        TextEntry::make('subtitulo')
                                            ->label('Subtítulo')
                                            ->columnSpanFull(),
                                        TextEntry::make('descripcion')
                                            ->label('Descripción')
                                            ->columnSpanFull(),
                                        Group::make([
                                            TextEntry::make('carpeta.nombre')
                                                ->label('Carpeta')
                                                ->icon('heroicon-o-folder'),
                                            TextEntry::make('numero.anio.fuente.nombre')
                                                ->label('Fuente')
                                                ->icon('heroicon-o-newspaper'),
                                            TextEntry::make('numero.anio.anio')
                                                ->label('Año')
                                                ->icon('heroicon-o-calendar'),
                                            TextEntry::make('numero.numero')
                                                ->label('Número')
                                                ->icon('heroicon-o-hashtag'),
                                            TextEntry::make('seccion.nombre')
                                                ->label('Sección')
                                                ->icon('heroicon-o-rectangle-stack'),
                                            TextEntry::make('fecha')
                                                ->label('Fecha')
                                                ->date('d/m/Y')
                                                ->icon('heroicon-o-calendar-days'),
                                            TextEntry::make('pagina')
                                                ->label('Página')
                                                ->icon('heroicon-o-document'),
                                            TextEntry::make('estado')
                                                ->label('Estado')
                                                ->badge(),
                                        ])->columns(4),
                                        Group::make([
                                            TextEntry::make('temas.nombre')
                                                ->label('Temas')
                                                ->badge()
                                                ->separator(','),
                                            TextEntry::make('autores.nombre')
                                                ->label('Autores')
                                                ->badge()
                                                ->color('info')
                                                ->separator(','),
                                        ])->columns(2),
                                        Group::make([
                                            IconEntry::make('tiene_ocr')
                                                ->label('Tiene OCR')
                                                ->boolean(),
                                            TextEntry::make('texto_ocr')
                                                ->label('Texto OCR')
                                                ->columnSpanFull()
                                                ->hidden(fn ($record) => ! $record->tiene_ocr),
                                        ])
                                            ->columnSpanFull()
                                            ->hidden(fn ($record) => ! $record->tiene_ocr),
                                        Group::make([
                                            TextEntry::make('creator.name')
                                                ->label('Creado por'),
                                            TextEntry::make('created_at')
                                                ->label('Creado')
                                                ->dateTime('d/m/Y H:i'),
                                            TextEntry::make('editor.name')
                                                ->label('Editado por'),
                                            TextEntry::make('updated_at')
                                                ->label('Actualizado')
                                                ->dateTime('d/m/Y H:i'),
                                        ])->columns(4),
                                    ])
                                    ->columns(1),
                            ]),
                        Tab::make('Auditoría')
                            ->icon('heroicon-o-clipboard-document-list')
                            ->schema([
                                ActivityLogTable::make(),
                            ]),
                    ]),
            ]);
    }
}
