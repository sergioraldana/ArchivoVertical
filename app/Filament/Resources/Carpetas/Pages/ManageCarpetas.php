<?php

namespace App\Filament\Resources\Carpetas\Pages;

use App\Filament\Resources\Carpetas\CarpetaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCarpetas extends ManageRecords
{
    protected static string $resource = CarpetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
