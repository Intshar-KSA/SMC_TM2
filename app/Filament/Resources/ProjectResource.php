<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use App\Models\Project;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Exports\ProjectsExport;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\ProjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Helpers\ModelLabelHelper;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('whatsapp_group_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('instagram_user'),
                Forms\Components\TextInput::make('instagram_pass'),
                Forms\Components\TextInput::make('tiktok_user'),
                Forms\Components\TextInput::make('tiktok_pass'),
                Forms\Components\TextInput::make('snap_user'),
                Forms\Components\TextInput::make('snap_pass'),
                Forms\Components\TextInput::make('x_user'),
                Forms\Components\TextInput::make('x_pass'),
                Forms\Components\TextInput::make('facebook_user')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('facebook_pass'),

                Forms\Components\TextInput::make('store_user'),
                Forms\Components\TextInput::make('store_password'),
                Forms\Components\TextInput::make('store_url'),
                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('end_date'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name'),
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('whatsapp_group_id'),
                Infolists\Components\TextEntry::make('tiktok_user'),
                Infolists\Components\TextEntry::make('instagram_user'),
                Infolists\Components\TextEntry::make('snap_user'),
                Infolists\Components\TextEntry::make('x_user'),
                Infolists\Components\TextEntry::make('facebook_pass'),
                Infolists\Components\TextEntry::make('tiktok_pass'),
                Infolists\Components\TextEntry::make('instagram_pass'),
                Infolists\Components\TextEntry::make('snap_pass'),
                Infolists\Components\TextEntry::make('x_pass'),
                Infolists\Components\TextEntry::make('store_url'),
                Infolists\Components\TextEntry::make('store_user'),
                Infolists\Components\TextEntry::make('store_password'),
                Infolists\Components\TextEntry::make('start_date'),
                Infolists\Components\TextEntry::make('end_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('whatsapp_group_id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tiktok_user')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('instagram_user')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('snap_user')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('x_user')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('store_url')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('store_user')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('export')
                    ->action(function (Collection $records) {
                        return Excel::download(new ProjectsExport($records), 'projects.xlsx');
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
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
