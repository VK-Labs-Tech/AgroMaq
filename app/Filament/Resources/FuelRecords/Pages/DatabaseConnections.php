<?php

namespace App\Filament\Resources\FuelRecords\Pages;

use App\Filament\Resources\FuelRecords\FuelRecordResource;
use Filament\Resources\Pages\Page;

class DatabaseConnections extends Page
{
    protected static string $resource = FuelRecordResource::class;

    // Redirect to the DatabaseConnection resource index (no Blade needed)
    public function mount(): void
    {
        // Adjust route name if Filament panel uses different route naming
        redirect()->route('filament.admin.resources.database-connections.index')->send();
    }
}
