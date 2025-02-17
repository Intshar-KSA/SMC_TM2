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
                    ->required(),
                TextInput::make('captions')
                    ->required(),
                TextInput::make('hashtag'),
                Textarea::make('des'),
                Select::make('type')
                    ->options(WhatsAppService::getOptions()),
                Select::make('platform')
                    ->multiple()
                    ->options(WhatsAppService::getPlatformOptions())
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'posted' => 'Posted',
                        'canceled' => 'Canceled',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('projectPlan.name')->sortable()->searchable(),
                TextColumn::make('emp.name')->sortable()->searchable(),
                TextColumn::make('type')->sortable(),
                TextColumn::make('platform')->sortable(),
                TextColumn::make('status')->sortable(),
            ])
            ->filters([])
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
