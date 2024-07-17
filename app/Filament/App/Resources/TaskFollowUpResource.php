<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\TaskFollowUpResource\Pages;
use App\Filament\App\Resources\TaskFollowUpResource\RelationManagers;
use App\Models\TaskFollowUp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskFollowUpResource extends Resource
{
    protected static ?string $model = TaskFollowUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('emp_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('task_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('task_status_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('emp_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('task_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('task_status_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskFollowUps::route('/'),
            'create' => Pages\CreateTaskFollowUp::route('/create'),
            'edit' => Pages\EditTaskFollowUp::route('/{record}/edit'),
        ];
    }
}
