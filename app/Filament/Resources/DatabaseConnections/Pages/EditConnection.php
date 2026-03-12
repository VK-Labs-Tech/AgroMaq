<?php

namespace App\Filament\Resources\DatabaseConnections\Pages;

use App\Filament\Resources\DatabaseConnections\DatabaseConnectionResource;
use Filament\Resources\Pages\EditRecord;

class EditConnection extends EditRecord
{
    protected static string $resource = DatabaseConnectionResource::class;
}
