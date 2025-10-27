<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información básica')
                    ->schema([
                        Select::make('carpeta_id')
                            ->relationship('carpeta', 'nombre')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('numero_id')
                            ->relationship('numero', 'numero')
                            ->searchable()
                            ->preload(),
                        Select::make('seccion_id')
                            ->relationship('seccion', 'nombre')
                            ->searchable()
                            ->preload(),
                        TextInput::make('titulo')
                            ->required()
                            ->maxLength(300)
                            ->columnSpanFull(),
                        TextInput::make('subtitulo')
                            ->maxLength(300)
                            ->columnSpanFull(),
                        Textarea::make('descripcion')
                            ->rows(3)
                            ->columnSpanFull(),
                        Group::make()
                            ->schema([
                                DatePicker::make('fecha'),
                                TextInput::make('pagina')
                                    ->maxLength(30),
                            ])
                            ->columns(2),
                    ])
                    ->columns(2),

                Section::make('Clasificación')
                    ->schema([
                        Select::make('autores')
                            ->label('Autores')
                            ->relationship('autores', 'nombre')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(150)
                                    ->unique(),
                            ]),
                        Select::make('temas')
                            ->label('Temas')
                            ->relationship('temas', 'nombre')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(150)
                                    ->unique(),
                            ]),
                        TextInput::make('estado')
                            ->label('Estado')
                            ->maxLength(30),
                    ])
                    ->columns(2),

                Section::make('Digitalización')
                    ->schema([
                        Toggle::make('tiene_ocr')
                            ->label('Tiene OCR'),
                        Textarea::make('texto_ocr')
                            ->label('Texto OCR')
                            ->rows(5)
                            ->columnSpanFull()
                            ->visible(fn ($get) => $get('tiene_ocr')),
                    ]),

                Section::make('Archivos digitales')
                    ->schema([
                        FileUpload::make('archivos')
                            ->label('Archivos')
                            ->multiple()
                            ->disk('public')
                            ->directory('items')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(10240)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
