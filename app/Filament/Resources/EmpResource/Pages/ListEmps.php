<?php

namespace App\Filament\Resources\EmpResource\Pages;

use App\Filament\Resources\EmpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmps extends ListRecords
{
    protected static string $resource = EmpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
