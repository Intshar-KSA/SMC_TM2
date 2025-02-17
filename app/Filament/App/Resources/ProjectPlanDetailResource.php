<?php
namespace App\Filament\App\Resources;

use App\Models\ProjectPlanDetail;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\App\Resources\ProjectPlanDetailResource\Pages;
use App\Helpers\ModelLabelHelper;
use App\Services\WhatsAppService;

class ProjectPlanDetailResource extends Resource
{
    protected static ?string $model = ProjectPlanDetail::class;

    protected static ?int $navigationSort = 5;


    // protected static ?string $navigationIcon = 'heroicon-o-list';
    public static function getNavigationGroup(): string
    {
        return __('Projects management');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('project_plan_id')
                    ->relationship('projectPlan', 'name')
                    ->required()
                    ->label('Project Plan'),
                // Select::make('emp_id')
                //     ->relationship('emp', 'name')
                //     ->required()
                //     ->label('Employee'),
                TextInput::make('captions')
                    ->label('Captions')
                    ->required()
                    ,

                TextInput::make('hashtag')
                ->label('Hashtag')
                // ->required()
                ,
                Textarea::make('des')
                    ->label('Description')
                    // ->required()
                    ,
                Select::make('type')
                ->options(WhatsAppService::getOptions())
                    // ->required()
                    ->label('Type'),
                Select::make('platform')
                    ->multiple()
                    ->options(WhatsAppService::getPlatformOptions())
                    ->required()
                    ->label('Platform'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'posted' => 'Posted',
                        'canceled' => 'Canceled',
                    ])
                    ->required()
                    ->label('Status'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('projectPlan.name')->label('Project Plan')->sortable()->searchable(),
                TextColumn::make('emp.name')->label('Employee')->sortable()->searchable(),
                TextColumn::make('type')->label('Type')->sortable(),
                TextColumn::make('platform')->label('Platform')->sortable(),
                TextColumn::make('status')->label('Status')->sortable(),
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
    public static function getPages(): array
{
    return [
        'index' => Pages\ListProjectPlanDetails::route('/'),
        'create' => Pages\CreateProjectPlanDetail::route('/create'),
        'edit' => Pages\EditProjectPlanDetail::route('/{record}/edit'),
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
