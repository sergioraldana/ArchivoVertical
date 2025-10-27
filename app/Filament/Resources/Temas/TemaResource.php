<?php

namespace App\Filament\Resources\Temas;

use App\Filament\Resources\Temas\Pages\ManageTemas;
use App\Filament\Resources\Temas\Schemas\TemaForm;
use App\Filament\Resources\Temas\Tables\TemasTable;
use App\Models\Tema;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TemaResource extends Resource
{
    protected static ?string $model = Tema::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $modelLabel = 'tema';

    protected static ?string $pluralModelLabel = 'temas';

    protected static ?string $navigationLabel = 'Temas';

    public static function form(Schema $schema): Schema
    {
        return TemaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TemasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTemas::route('/'),
        ];
    }
}
