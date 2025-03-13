<?php

namespace App\Filament\Resources\TaskFollowUpResource\Pages;

use App\Filament\Resources\TaskFollowUpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskFollowUps extends ListRecords
{
    protected static string $resource = TaskFollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->latest();
    }
}
