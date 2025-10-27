<?php

namespace App\Filament\Resources\Seccions;

use App\Filament\Resources\Seccions\Pages\ManageSeccions;
use App\Filament\Resources\Seccions\Schemas\SeccionForm;
use App\Filament\Resources\Seccions\Tables\SeccionsTable;
use App\Models\Seccion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SeccionResource extends Resource
{
    protected static ?string $model = Seccion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?string $modelLabel = 'sección';

    protected static ?string $pluralModelLabel = 'secciones';

    protected static ?string $navigationLabel = 'Secciones';

    public static function form(Schema $schema): Schema
    {
        return SeccionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SeccionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSeccions::route('/'),
        ];
    }
}
