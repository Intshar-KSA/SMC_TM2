<?php

namespace App\Filament\App\Resources\ProjectAttachmentResource\Pages;

use App\Filament\App\Resources\ProjectAttachmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProjectAttachments extends ListRecords
{
    protected static string $resource = ProjectAttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->guard('emp')->user()->user_id;

        return parent::getEloquentQuery()
            ->whereHas('attach_project', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            });
    }
    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->latest();
    }
}
