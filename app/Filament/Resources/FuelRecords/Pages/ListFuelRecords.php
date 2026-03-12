<?php

namespace App\Filament\Resources\FuelRecords\Pages;

use App\Filament\Resources\FuelRecords\FuelRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFuelRecords extends ListRecords
{
    protected static string $resource = FuelRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
