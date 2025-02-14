<?php

namespace App\Filament\Resources\EmpResource\Pages;

use App\Filament\Resources\EmpResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmp extends CreateRecord
{
    protected static string $resource = EmpResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();



        return $data;
    }
}
