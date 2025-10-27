<?php

namespace App\Filament\Resources\Temas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TemaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(150)
                    ->unique(ignoreRecord: true),
            ]);
    }
}
