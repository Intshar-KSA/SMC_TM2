<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpResource\RelationManagers\ReceivedTasksRelationManager;
use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaskResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TaskResource\RelationManagers;
use Illuminate\Support\Str;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->relationship('user_project', 'name'), // Label will be auto-generated as "Project ID"
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255), // Label will be auto-generated as "Title"
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(), // Label will be auto-generated as "Description"
                Forms\Components\Select::make('receiver_id')
                    ->relationship('task_emp', 'name')->hiddenOn(ReceivedTasksRelationManager::class) , // Label will be auto-generated as "Receiver ID"
                Forms\Components\TextInput::make('time_in_minutes')
                    ->numeric()
                    ->default(null), // Label will be auto-generated as "Time In Minutes"
                Forms\Components\Toggle::make('is_recurring')
                    ->required(), // Label will be auto-generated as "Is Recurring"
                Forms\Components\Toggle::make('send_to_group'), // Label will be auto-generated as "Send To Group"
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(), // Label will be auto-generated as "ID"
                Tables\Columns\TextColumn::make('projectOwner.name')
                    ->sortable(), // Label will be auto-generated as "Project Owner Name"
                Tables\Columns\TextColumn::make('title')
                    ->searchable(), // Label will be auto-generated as "Title"
                Tables\Columns\TextColumn::make('sender.name')
                    ->sortable(), // Label will be auto-generated as "Sender Name"
                Tables\Columns\TextColumn::make('receiver.name')
                    ->sortable(), // Label will be auto-generated as "Receiver Name"
                Tables\Columns\TextColumn::make('lastFollowUp.taskStatus.name')
                    ->sortable(), // Label will be auto-generated as "Last Follow Up Task Status Name"
                Tables\Columns\TextColumn::make('time_in_minutes')
                    ->numeric()
                    ->sortable(), // Label will be auto-generated as "Time In Minutes"
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(), // Label will be auto-generated as "Start Date"
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Label will be auto-generated as "Created At"
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Label will be auto-generated as "Updated At"
                Tables\Columns\IconColumn::make('is_recurring')
                    ->boolean(), // Label will be auto-generated as "Is Recurring"
                Tables\Columns\TextColumn::make('recurrence_interval_days')
                    ->numeric()
                    ->sortable(), // Label will be auto-generated as "Recurrence Interval Days"
                Tables\Columns\TextColumn::make('next_occurrence')
                    ->dateTime()
                    ->sortable(), // Label will be auto-generated as "Next Occurrence"
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'), // Label will be auto-generated as "Created From"
                        DatePicker::make('created_until'), // Label will be auto-generated as "Created Until"
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Filter::make('reciver_id')
                    ->form([
                        Select::make('reciver_id') // Label will be auto-generated as "Reciver ID"
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['reciver_id'],
                                fn (Builder $query, $date): Builder => $query->where('receiver_id', $date),
                            );
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            RelationManagers\FollowUpsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->type == "super admin") {
            return parent::getEloquentQuery();
        }

        $userId = auth()->guard('emp')->check() ? auth()->guard('emp')->user_id : auth()->user()->id;

        return parent::getEloquentQuery()
            ->whereHas('user_project', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            });
    }

    /**
     * Get the translated model label.
     */
    public static function getModelLabel(): string
    {
        $modelClass = static::$model;
        $modelName = class_basename($modelClass);
        return __("{$modelName}");
    }

    /**
     * Get the translated plural model label.
     */
    public static function getPluralModelLabel(): string
    {
        $modelClass = static::$model;
        $modelName = class_basename($modelClass); // e.g., "TaskFollowUps"

        // Convert to headline case (e.g., "Task Follow Ups")
        $headline = Str::headline($modelName);

        // Convert to lowercase and capitalize the first character of the first word
        $formatted = Str::lower($headline); // e.g., "task follow ups"
        $formatted = Str::ucfirst($formatted); // e.g., "Task follow ups"

        // Pluralize the formatted string
        $plural = Str::plural($formatted); // e.g., "Task follow ups" -> "Task follow ups" (plural)

        return __($plural); // Translate the plural label
    }
}
