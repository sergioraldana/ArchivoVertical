<?php

namespace App\Filament\Resources\Fuentes\Pages;

use App\Filament\Resources\Fuentes\FuenteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFuentes extends ManageRecords
{
    protected static string $resource = FuenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
