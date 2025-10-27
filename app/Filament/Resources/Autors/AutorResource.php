<?php

namespace App\Filament\Resources\Autors;

use App\Filament\Resources\Autors\Pages\ManageAutors;
use App\Filament\Resources\Autors\Schemas\AutorForm;
use App\Filament\Resources\Autors\Tables\AutorsTable;
use App\Models\Autor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AutorResource extends Resource
{
    protected static ?string $model = Autor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $modelLabel = 'autor';

    protected static ?string $pluralModelLabel = 'autores';

    protected static ?string $navigationLabel = 'Autores';

    public static function form(Schema $schema): Schema
    {
        return AutorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AutorsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAutors::route('/'),
        ];
    }
}
