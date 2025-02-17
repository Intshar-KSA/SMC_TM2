<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProjectAttachmentResource\Pages;
use App\Models\ProjectAttachment;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\BooleanEntry;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Filament\App\Resources\TaskFollowUpResource\RelationManagers;
use App\Helpers\ModelLabelHelper;

class ProjectAttachmentResource extends Resource
{
    protected static ?string $model = ProjectAttachment::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $user = auth()->guard('emp')->user();
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->relationship('attach_project', 'name')
                    ->required(),
                Forms\Components\TextInput::make('des')
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->required(),
                Forms\Components\Toggle::make('is_in_own_drive')
                    ->default(false)
                    ->disabled(!$user->is_admin),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('des'),
                Tables\Columns\TextColumn::make('url')
                    ->url(fn ($record) => $record->url)
                    ->formatStateUsing(fn ($state) => 'Open'),
                Tables\Columns\TextColumn::make('emp.name')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_in_own_drive')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectAttachments::route('/'),
            'create' => Pages\CreateProjectAttachment::route('/create'),
            'view' => Pages\ViewProjectAttachment::route('/{record}'),
            'edit' => Pages\EditProjectAttachment::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->guard('emp')->user()->user_id;

        return parent::getEloquentQuery()
            ->whereHas('attach_project', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
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
