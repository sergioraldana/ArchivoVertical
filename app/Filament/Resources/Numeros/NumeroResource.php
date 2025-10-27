<?php

namespace App\Filament\Resources\Numeros;

use App\Filament\Resources\Numeros\Pages\ManageNumeros;
use App\Filament\Resources\Numeros\Schemas\NumeroForm;
use App\Filament\Resources\Numeros\Tables\NumerosTable;
use App\Models\Numero;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NumeroResource extends Resource
{
    protected static ?string $model = Numero::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $modelLabel = 'número';

    protected static ?string $pluralModelLabel = 'números';

    protected static ?string $navigationLabel = 'Números';

    public static function form(Schema $schema): Schema
    {
        return NumeroForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NumerosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageNumeros::route('/'),
        ];
    }
}
