<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Project;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ProjectResource\Pages;
use App\Helpers\ModelLabelHelper;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->disabled(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('whatsapp_group_id')
                    ->maxLength(255),
                TextInput::make('store_url'),
                TextInput::make('store_user'),
                TextInput::make('store_password'),
                TextInput::make('tiktok_user'),
                TextInput::make('instagram_user'),
                TextInput::make('snap_user'),
                TextInput::make('x_user'),
                TextInput::make('facebook_user')
                    ->nullable()
                    ->maxLength(255),
                TextInput::make('tiktok_pass'),
                TextInput::make('instagram_pass'),
                TextInput::make('snap_pass'),
                TextInput::make('x_pass'),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
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
                TextColumn::make('name')->searchable(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('end_date')->date()->sortable(),
                TextColumn::make('whatsapp_group_id')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tiktok_user')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('instagram_user')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('snap_user')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('x_user')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('store_url')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('store_user')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'view' => Pages\ViewProject::route('/{record}'),
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
