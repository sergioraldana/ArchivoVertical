<?php

namespace App\Filament\Resources\Numeros\Pages;

use App\Filament\Resources\Numeros\NumeroResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageNumeros extends ManageRecords
{
    protected static string $resource = NumeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
