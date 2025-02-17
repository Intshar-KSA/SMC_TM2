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
// use RelationManagers\ProjectPlanDetailsRelationManager;
use App\Filament\App\Resources\ProjectPlanResource\Pages;
use App\Filament\App\Resources\ProjectPlanResource\RelationManagers;
use App\Helpers\ModelLabelHelper;

class ProjectPlanResource extends Resource
{
    protected static ?string $model = ProjectPlan::class;
    protected static ?string $navigationGroup = 'Projects Management';
    protected static ?int $navigationSort = 4;


    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // select project
                // Forms\Components\Select::make('project_id')
                // ->relationship('emp_project', 'name')
                // ->searchable(),
                Select::make('project_id')
                    ->relationship('emp_project', 'name')
                    ->required()
                    ->label('Project'),
                TextInput::make('name')
                    ->required()
                    ->label('Plan Name'),
                DatePicker::make('start_date')
                    ->required()
                    ->label('Start Date'),
                DatePicker::make('end_date')
                    ->required()
                    ->label('End Date'),


                    // Forms\Components\Select::make('receiver_id')
                    // ->relationship('task_emp_app', 'name')
                    // ->required()
                    // ->label('Receiver Name'),
                Select::make('moderator_id')
                    ->relationship('moderator', 'name')
                    ->required()
                    ->label('Moderator'),
                Select::make('copy_writer_id')
                    ->relationship('copyWriter', 'name')
                    ->label('Copy Writer'),
                Select::make('media_buyer_id')
                    ->relationship('mediaBuyer', 'name')
                    ->label('Media Buyer'),
                Select::make('graphic_designer_id')
                    ->relationship('graphicDesigner', 'name')
                    ->label('Graphic Designer'),
                Select::make('video_designer_id')
                    ->relationship('videoDesigner', 'name')
                    ->label('Video Designer'),
                Select::make('programmer_id')
                    ->relationship('programmer', 'name')
                    ->label('Programmer'),
                Select::make('seo_specialist_id')
                    ->relationship('seoSpecialist', 'name')
                    ->label('SEO Specialist'),
                TextInput::make('files_url')
                    ->label('Files URL')
                    ->url()
                    ->nullable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([

                TextColumn::make('name')->label('Plan Name')->sortable()->searchable(),
                TextColumn::make('project.name')->label('Project')->sortable()->searchable(),

                // DateColumn::make('start_date')->label('Start Date')->sortable(),
                // DateColumn::make('end_date')->label('End Date')->sortable(),
                TextColumn::make('emp.name')->label('Employee')->sortable()->searchable(),
                BooleanColumn::make('is_completed')->label('Completed')->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                ->dateTime()
                ->sortable()
         ,
            Tables\Columns\TextColumn::make('end_date')
                ->dateTime()
                ->sortable()
                ,
                TextColumn::make('moderator.name')->label('Moderator')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('copyWriter.name')->label('Copy Writer')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mediaBuyer.name')->label('Media Buyer')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('graphicDesigner.name')->label('Graphic Designer')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('videoDesigner.name')->label('Video Designer')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('programmer.name')->label('Programmer')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('seoSpecialist.name')->label('SEO Specialist')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('files_url')->label('Files URL')->sortable()->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                // Add any filters if needed
            ])
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
        // 'view' => Pages\ViewProjectPlan::route('/{record}'),
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
