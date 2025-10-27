<?php

namespace App\Filament\Resources\Numeros\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NumeroForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('anio_id')
                    ->relationship('anio', 'anio')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('numero')
                    ->required()
                    ->maxLength(50),
                DatePicker::make('fecha_publicacion'),
            ]);
    }
}
