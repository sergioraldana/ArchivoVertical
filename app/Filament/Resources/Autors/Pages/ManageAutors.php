<?php

namespace App\Filament\Resources\Autors\Pages;

use App\Filament\Resources\Autors\AutorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAutors extends ManageRecords
{
    protected static string $resource = AutorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
