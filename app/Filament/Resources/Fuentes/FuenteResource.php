<?php

namespace App\Filament\Resources\Fuentes;

use App\Filament\Resources\Fuentes\Pages\ManageFuentes;
use App\Filament\Resources\Fuentes\Schemas\FuenteForm;
use App\Filament\Resources\Fuentes\Tables\FuentesTable;
use App\Models\Fuente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FuenteResource extends Resource
{
    protected static ?string $model = Fuente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $modelLabel = 'fuente';

    protected static ?string $pluralModelLabel = 'fuentes';

    protected static ?string $navigationLabel = 'Fuentes';

    public static function form(Schema $schema): Schema
    {
        return FuenteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FuentesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFuentes::route('/'),
        ];
    }
}
