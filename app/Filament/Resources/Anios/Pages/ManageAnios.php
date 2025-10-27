<?php

namespace App\Filament\Resources\Anios\Pages;

use App\Filament\Resources\Anios\AnioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAnios extends ManageRecords
{
    protected static string $resource = AnioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
