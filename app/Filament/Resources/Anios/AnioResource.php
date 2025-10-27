<?php

namespace App\Filament\Resources\Anios;

use App\Filament\Resources\Anios\Pages\ManageAnios;
use App\Filament\Resources\Anios\Schemas\AnioForm;
use App\Filament\Resources\Anios\Tables\AniosTable;
use App\Models\Anio;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AnioResource extends Resource
{
    protected static ?string $model = Anio::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static ?string $modelLabel = 'año';

    protected static ?string $pluralModelLabel = 'años';

    protected static ?string $navigationLabel = 'Años';

    public static function form(Schema $schema): Schema
    {
        return AnioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AniosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAnios::route('/'),
        ];
    }
}
