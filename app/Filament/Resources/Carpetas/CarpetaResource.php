<?php

namespace App\Filament\Resources\Carpetas;

use App\Filament\Resources\Carpetas\Pages\ManageCarpetas;
use App\Filament\Resources\Carpetas\Schemas\CarpetaForm;
use App\Filament\Resources\Carpetas\Tables\CarpetasTable;
use App\Models\Carpeta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CarpetaResource extends Resource
{
    protected static ?string $model = Carpeta::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $modelLabel = 'carpeta';

    protected static ?string $pluralModelLabel = 'carpetas';

    protected static ?string $navigationLabel = 'Carpetas';

    public static function form(Schema $schema): Schema
    {
        return CarpetaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarpetasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCarpetas::route('/'),
        ];
    }
}
