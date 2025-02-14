<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Project;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Exports\ProjectsExport;
use App\Policies\ProjectPolicy;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use RelationManagers\UsersRelationManager;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\ProjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProjectResource\RelationManagers;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?int $navigationSort = 1;




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('user_id')
                //     ->required()
                //     ->label('user id')

                //     ->numeric(),
                    // Forms\Components\Select::make('user_id')
                    // ->options(User::pluck('name', 'id')) // Adjust 'name' and 'id' based on your User model
                    // ->displayUsingLabels()
                    // ->required(),
                //    Forms\Components\Select::make('user_id')
                //     ->relationship('user', 'name'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('whatsapp_group_id')
                    ->maxLength(255),

                Forms\Components\TextInput::make('insta_user'),
                Forms\Components\TextInput::make('insta_pass'),
                Forms\Components\TextInput::make('tiktok_user'),
                Forms\Components\TextInput::make('tiktok_pass'),

                Forms\Components\TextInput::make('snap_user'),
                Forms\Components\TextInput::make('snap_pass'),
                Forms\Components\TextInput::make('x_user'),
                Forms\Components\TextInput::make('x_pass'),
                Forms\Components\TextInput::make('facebook_user')
                ->nullable()
                ->maxLength(255),
                Forms\Components\TextInput::make('instagram_pass'),





                Forms\Components\TextInput::make('instagram_user'),
                Forms\Components\TextInput::make('store_url'),
                Forms\Components\TextInput::make('store_user'),
                Forms\Components\TextInput::make('store_password'),
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

                    Infolists\Components\TextEntry::make('insta_user'),
                    Infolists\Components\TextEntry::make('tiktok_user'),
                    Infolists\Components\TextEntry::make('instagram_user'),
                    Infolists\Components\TextEntry::make('snap_user'),
                    Infolists\Components\TextEntry::make('x_user'),
                    Infolists\Components\TextEntry::make('facebook_pass'),
                    Infolists\Components\TextEntry::make('insta_pass'),
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
                // Tables\Columns\TextColumn::make('user_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('user.name')
                // ->label('User Name') // Optional label
                // ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('whatsapp_group_id')->label('Whatsapp Group ID')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('insta_user')->label('Instagram User')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('tiktok_user')->label('Tiktok User')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('instagram_user')->label('Instagram User')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('snap_user')->label('Snap User')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('x_user')->label('X User')->sortable()->toggleable(isToggledHiddenByDefault: true),


                    Tables\Columns\TextColumn::make('store_url')->label('Store URL')->sortable()->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('store_user')->label('Store User')->sortable()->toggleable(isToggledHiddenByDefault: true),
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

                Tables\Actions\ViewAction::make(),


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('export')
                ->label('تصدير إلى Excel')
                // ->icon('heroicon-o-document-download')
                ->action(function (Collection $records) {
                    // تصدير السجلات المختارة إلى ملف Excel
                    return Excel::download(new ProjectsExport($records), 'projects.xlsx');
                }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
    if(auth()->user()->type=="super admin"){
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
        $modelName = class_basename($modelClass);
        return __("{$modelName}");
    }

    /**
     * Get the translated plural model label.
     */
    public static function getPluralModelLabel(): string
    {
        $modelClass = static::$model;
        $modelName = class_basename($modelClass);
        $plural= Str::plural(Str::headline($modelName));
        return  __("{$plural}");
    }
}
