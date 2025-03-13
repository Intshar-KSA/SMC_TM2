<?php

namespace App\Filament\App\Resources\TaskAppResource\Pages;

use App\Filament\App\Resources\TaskAppResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskApps extends ListRecords
{
    protected static string $resource = TaskAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->latest();
    }
}
