<?php
namespace App\Filament\App\Resources;

use App\Models\Campaign;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateColumn;
use App\Filament\App\Resources\CampaignResource\Pages;
use App\Helpers\ModelLabelHelper;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;
    protected static ?string $navigationGroup = 'Projects Management';
    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Campaign Name')
                    ->required(),
                Select::make('project_id')
                    ->relationship('emp_project', 'name')
                    ->required()
                    ->label('Project'),
                // Select::make('emp_id')
                //     ->relationship('emp', 'name')
                //     ->required()
                //     ->label('Employee'),
                TextInput::make('campaign_type')
                    ->label('Campaign Type')
                    // ->required()
                    ,
                Select::make('platform')
                    ->options([
                        'facebook' => 'Facebook',
                        'instagram' => 'Instagram',
                        'whatsapp' => 'Whatsapp',
                        'telegram' => 'Telegram',
                        'snapchat' => 'Snapchat',

                        'tiktok' => 'TikTok',
                        'youtube' => 'Youtube',
                        'twitter' => 'Twitter',
                        'linkedin' => 'Linkedin',
                        'other' => 'Other',

                        // Add more platforms as needed
                    ])
                    // ->required()
                    ->label('Platform'),
                TextInput::make('daily_spend')
                    ->label('Daily Spend')
                    ->numeric()
                    // ->required()
                    ,
                TextInput::make('landing_page_url')
                    ->label('Landing Page URL')
                    ->url()
                    ->nullable(),
                TextInput::make('sheet_url')
                    ->label('Sheet URL')
                    ->url()
                    ->nullable(),
                TextInput::make('area')
                    ->label('Area')
                    // ->required()
                    ,
                TextInput::make('location_url')
                    ->label('Location URL')
                    ->url()
                    // ->required()
                    ,
                TextInput::make('creatives_url')
                    ->label('Creatives URL')
                    ->url()
                    // ->required()
                    ,
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('End Date')
                    ->required(),

            // إضافة مفتاح Toggle يظهر عند التعديل فقط
            Forms\Components\Toggle::make('send_to_group')
            ->label('إرسال إلى جروب العميل')
            ->visible(fn ($livewire) => $livewire instanceof \App\Filament\App\Resources\CampaignResource\Pages\EditCampaign), // يظهر فقط في صفحة التعديل
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Campaign Name')->sortable()->searchable(),
                TextColumn::make('project.name')->label('Project')->sortable()->searchable(),
                TextColumn::make('emp.name')->label('Employee')->sortable()->searchable(),
                TextColumn::make('campaign_type')->label('Campaign Type')->sortable(),
                TextColumn::make('daily_spend')->label('Daily Spend')->sortable(),
                TextColumn::make('platform')->label('Platform')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('landing_page_url')->label('Landing Page URL')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sheet_url')->label('Sheet URL')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('area')->label('Area')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location_url')->label('Location URL')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('creatives_url')->label('Creatives URL')->sortable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('start_date')
                ->label('Start Date')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('end_date')
                ->label('End Date')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
        'index' => Pages\ListCampaigns::route('/'),
        'create' => Pages\CreateCampaign::route('/create'),
        'edit' => Pages\EditCampaign::route('/{record}/edit'),
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
