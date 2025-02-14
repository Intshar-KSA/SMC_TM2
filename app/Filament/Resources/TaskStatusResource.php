<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskStatusResource\Pages;
use App\Models\TaskStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TaskStatusResource extends Resource
{
    protected static ?string $model = TaskStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'General Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('only_for_admin')
                    ->required(),
                Forms\Components\Toggle::make('is_completely'),
                Forms\Components\Toggle::make('is_cancelled'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('only_for_admin')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_completely')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_cancelled')
                    ->boolean(),
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
                // Filters can be added here
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
            // Relations can be added here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaskStatuses::route('/'),
            'create' => Pages\CreateTaskStatus::route('/create'),
            'edit' => Pages\EditTaskStatus::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->type == "super admin") {
            return parent::getEloquentQuery();
        }
        return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
    }

    /**
     * Get the translated model label.
     */
    public static function getModelLabel(): string
    {
        $modelClass = static::$model;
        $modelName = class_basename($modelClass); // e.g., "TaskStatus"

        // Convert to headline case (e.g., "Task Status")
        $headline = Str::headline($modelName);

        // Convert to lowercase and capitalize the first character of the first word
        $formatted = Str::lower($headline); // e.g., "task status"
        $formatted = Str::ucfirst($formatted); // e.g., "Task status"

        return __($formatted); // Translate the model label
    }

    /**
     * Get the translated plural model label.
     */
    public static function getPluralModelLabel(): string
    {
        $modelClass = static::$model;
        $modelName = class_basename($modelClass); // e.g., "TaskStatus"

        // Convert to headline case (e.g., "Task Status")
        $headline = Str::headline($modelName);

        // Convert to lowercase and capitalize the first character of the first word
        $formatted = Str::lower($headline); // e.g., "task status"
        $formatted = Str::ucfirst($formatted); // e.g., "Task status"

        // Pluralize the formatted string
        $plural = Str::plural($formatted); // e.g., "Task status" -> "Task statuses"

        return __($plural); // Translate the plural label
    }
}
