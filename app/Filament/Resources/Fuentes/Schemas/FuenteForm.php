<?php

namespace App\Filament\Resources\Fuentes\Schemas;

use App\TipoFuente;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FuenteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(150),
                Select::make('tipo')
                    ->options(TipoFuente::class)
                    ->required(),
            ]);
    }
}
