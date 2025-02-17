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

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function getNavigationGroup(): string
    {
        return __('Projects management');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('project_id')->relationship('emp_project', 'name')->required(),
                TextInput::make('campaign_type'),
                Select::make('platform')->options([
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
                ]),
                TextInput::make('daily_spend')->numeric(),
                TextInput::make('landing_page_url')->url()->nullable(),
                TextInput::make('sheet_url')->url()->nullable(),
                TextInput::make('area'),
                TextInput::make('location_url')->url(),
                TextInput::make('creatives_url')->url(),
                DatePicker::make('start_date')->required(),
                DatePicker::make('end_date')->required(),
                Forms\Components\Toggle::make('send_to_group')
                    ->visible(fn ($livewire) => $livewire instanceof \App\Filament\App\Resources\CampaignResource\Pages\EditCampaign),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('project.name')->sortable()->searchable(),
                TextColumn::make('emp.name')->sortable()->searchable(),
                TextColumn::make('campaign_type')->sortable(),
                TextColumn::make('daily_spend')->sortable(),
                TextColumn::make('platform')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('landing_page_url')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sheet_url')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('area')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location_url')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('creatives_url')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_date')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('end_date')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
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
