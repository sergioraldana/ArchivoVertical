<?php

namespace App\Filament\Resources\Carpetas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CarpetaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo')
                    ->required()
                    ->maxLength(1),
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(150),
            ]);
    }
}
