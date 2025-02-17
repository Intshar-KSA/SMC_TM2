<?php
namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\ProjectPlan;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BooleanColumn;
use App\Filament\App\Resources\ProjectPlanResource\Pages;
use App\Filament\App\Resources\ProjectPlanResource\RelationManagers;
use App\Helpers\ModelLabelHelper;

class ProjectPlanResource extends Resource
{
    protected static ?string $model = ProjectPlan::class;
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    public static function getNavigationGroup(): string
    {
        return __('Projects management');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('project_id')
                    ->relationship('emp_project', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                Select::make('moderator_id')
                    ->relationship('moderator', 'name')
                    ->required(),
                Select::make('copy_writer_id')
                    ->relationship('copyWriter', 'name'),
                Select::make('media_buyer_id')
                    ->relationship('mediaBuyer', 'name'),
                Select::make('graphic_designer_id')
                    ->relationship('graphicDesigner', 'name'),
                Select::make('video_designer_id')
                    ->relationship('videoDesigner', 'name'),
                Select::make('programmer_id')
                    ->relationship('programmer', 'name'),
                Select::make('seo_specialist_id')
                    ->relationship('seoSpecialist', 'name'),
                TextInput::make('files_url')
                    ->url()
                    ->nullable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('project.name')->sortable()->searchable(),
                TextColumn::make('emp.name')->sortable()->searchable(),
                BooleanColumn::make('is_completed')->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('moderator.name')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('copyWriter.name')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mediaBuyer.name')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('graphicDesigner.name')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('videoDesigner.name')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('programmer.name')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('seoSpecialist.name')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('files_url')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProjectPlanDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectPlans::route('/'),
            'create' => Pages\CreateProjectPlan::route('/create'),
            'edit' => Pages\EditProjectPlan::route('/{record}/edit'),
        ];
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
