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
use App\Filament\App\Resources\ProjectResource\RelationManagers;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?int $navigationSort = 0;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->disabled(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('whatsapp_group_id')
                ->maxLength(255),
                Forms\Components\TextInput::make('store_url'),
                Forms\Components\TextInput::make('store_user'),
                Forms\Components\TextInput::make('store_password'),
                Forms\Components\TextInput::make('tiktok_user'),
                Forms\Components\TextInput::make('instagram_user'),
                Forms\Components\TextInput::make('snap_user'),
                Forms\Components\TextInput::make('x_user'),
                Forms\Components\TextInput::make('facebook_user')
                ->nullable()
                ->maxLength(255),
                Forms\Components\TextInput::make('tiktok_pass'),
                Forms\Components\TextInput::make('instagram_pass'),
                Forms\Components\TextInput::make('snap_pass'),
                Forms\Components\TextInput::make('x_pass'),


                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('end_date'),

                Forms\Components\TextInput::make('facebook_user')
                ->nullable()
                ->maxLength(255),

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
                // Tables\Columns\TextColumn::make('user.name')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                    TextColumn::make('whatsapp_group_id')->label('Whatsapp Group ID')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('tiktok_user')->label('Tiktok User')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('instagram_user')->label('Instagram User')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('snap_user')->label('Snap User')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('x_user')->label('X User')->sortable()->toggleable(isToggledHiddenByDefault: true),


                    Tables\Columns\TextColumn::make('store_url')->label('Store URL')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('store_user')->label('Store User')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    // Tables\Columns\TextColumn::make('store_password')->label('Store Password')->sortable()->toggleable(isToggledHiddenByDefault: true),

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
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProjects::route('/'),
            // 'create' => Pages\CreateProject::route('/create'),
            // 'edit' => Pages\EditProject::route('/{record}/edit'),
      'view' => Pages\ViewProject::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
       
        return parent::getEloquentQuery();
    }
}
