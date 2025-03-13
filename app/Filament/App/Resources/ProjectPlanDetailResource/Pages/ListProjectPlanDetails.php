<?php

namespace App\Filament\App\Resources\ProjectPlanDetailResource\Pages;

use App\Filament\App\Resources\ProjectPlanDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectPlanDetails extends ListRecords
{
    protected static string $resource = ProjectPlanDetailResource::class;

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
