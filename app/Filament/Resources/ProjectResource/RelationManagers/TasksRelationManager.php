<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Resources\TaskResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function form(Form $form): Form
    {
        return TaskResource::form($form);
    }



    public function table(Table $table): Table
    {
        return TaskResource::table($table)
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('sender.name'),
                Tables\Columns\TextColumn::make('receiver.name'),
Tables\Columns\TextColumn::make('lastFollowUp.taskStatus.name'),
                Tables\Columns\TextColumn::make('created_at'),

            ]);
    }
}
