<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskFollowUpResource\Pages;
use App\Filament\Resources\TaskFollowUpResource\RelationManagers;
use App\Models\TaskFollowUp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TaskFollowUpResource extends Resource
{
    protected static ?string $model = TaskFollowUp::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?int $navigationSort = 3;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('user_id')
                //     ->required()
                //     ->numeric(),
                Forms\Components\Select::make('emp_id')
                ->relationship('emp', 'name'),
                // Forms\Components\TextInput::make('task_id')
                //     ->required()
                //     ->numeric(),
                Forms\Components\Select::make('task_id')
                ->relationship('task', 'title'),
                // Forms\Components\TextInput::make('task_status_id')
                //     ->required()
                //     ->numeric(),
                    Forms\Components\Select::make('task_status_id')
                ->relationship('taskStatus', 'name'),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('emp_id')
                //     ->numeric()
                //     ->sortable(),
                    Tables\Columns\TextColumn::make('emp.name')
                    ->label('Employee') // Optional label
                    ->sortable(),
                // Tables\Columns\TextColumn::make('task_id')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('task.title')
                ->label('Task') // Optional label
                ->sortable(),
                // Tables\Columns\TextColumn::make('task_status_id')
                //     ->numeric()
                //     ->sortable(),
                    Tables\Columns\TextColumn::make('taskStatus.name')
                    ->label('Task Status') // Optional label
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')->limit(50),

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
                // Tables\Actions\EditAction::make(),
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

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->user()->id;
        // return TaskFollowUp::whereHas('task.emp_project2', function (Builder $query) use ($userId) {
        //     $query->where('user_id', $userId);
        // });
        return TaskFollowUp::whereHas('task', function (Builder $query) use ($userId) {
            $query->whereHas('project', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            });
        });
    }

     /**
     * Get the translated model label.
     */
    public static function getModelLabel(): string
    {
        $modelClass = static::$model;
        $modelName = class_basename($modelClass); // e.g., "TaskFollowUps"

        // Convert to headline case (e.g., "Task Follow Ups")
        $headline = Str::headline($modelName);

        // Convert to lowercase and capitalize the first character of the first word
        $formatted = Str::lower($headline); // e.g., "task follow ups"
        $formatted = Str::ucfirst($formatted); // e.g., "Task follow ups"
        return __($formatted);
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
