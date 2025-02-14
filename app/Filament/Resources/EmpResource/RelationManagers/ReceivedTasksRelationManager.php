<?php

namespace App\Filament\Resources\EmpResource\RelationManagers;

use App\Filament\Resources\TaskResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceivedTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'receivedTasks';

    public function form(Form $form): Form
    {
        return TaskResource::form($form);
    }



    public function table(Table $table): Table
    {
        return TaskResource::table($table)
            ->recordTitleAttribute('title');
    }
}
