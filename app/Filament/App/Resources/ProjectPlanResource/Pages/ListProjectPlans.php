<?php

namespace App\Filament\App\Resources\ProjectPlanResource\Pages;

use App\Filament\App\Resources\ProjectPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectPlans extends ListRecords
{
    protected static string $resource = ProjectPlanResource::class;

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
