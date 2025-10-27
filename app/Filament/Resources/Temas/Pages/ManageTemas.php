<?php

namespace App\Filament\Resources\Temas\Pages;

use App\Filament\Resources\Temas\TemaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTemas extends ManageRecords
{
    protected static string $resource = TemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
