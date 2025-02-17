<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\TaskStatus;
use Filament\Tables\Table;
use App\Models\TaskFollowUp;
use Filament\Resources\Resource;
use App\Services\WhatsAppService;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\TaskFollowUpResource\Pages;
use App\Filament\App\Resources\TaskFollowUpResource\RelationManagers;
use App\Helpers\ModelLabelHelper;

class TaskFollowUpResource extends Resource
{
    protected static ?string $model = TaskFollowUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $userId = auth()->guard('emp')->user()->user_id;

        return $form
            ->schema([
                Forms\Components\Select::make('task_id')
                    ->options(function () use ($userId) {
                        return Task::whereHas('project', function (Builder $query) use ($userId) {
                            $query->where('user_id', $userId);
                        })->pluck('title', 'id');
                    })
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('task_status_id')
                    ->relationship('taskStatusForUser', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $taskStatus = TaskStatus::find($state);
                        $set('is_completed', $taskStatus && $taskStatus->is_completely);
                    }),

                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('exact_time')
                    ->numeric()
                    ->minValue(0)
                    ->required(fn (callable $get) => $get('is_completed'))
                    ->hidden(fn (callable $get) => !$get('is_completed'))
                    ->reactive(),
            ])
            ->statePath('data');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('emp.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('task.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->sortable(),
                Tables\Columns\TextColumn::make('taskStatus.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
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
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->guard('emp')->user()->user_id;
        return TaskFollowUp::whereHas('task', function (Builder $query) use ($userId) {
            $query->whereHas('project', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            });
        });
    }

    public static function getModelLabel(): string
    {
        return ModelLabelHelper::getModelLabel(static::$model);
    }

    public static function getPluralModelLabel(): string
    {
        return ModelLabelHelper::getPluralModelLabel(static::$model);
    }
}
