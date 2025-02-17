<?php

namespace App\Filament\Pages;

use session;
use Filament\Pages\Page;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Illuminate\Contracts\Support\Htmlable;

class Setting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.pages.setting';
    protected static ?string $navigationLabel = 'Settings';
    // protected static ?string $navigationGroup = 'General Settings';

    public $name;
    public $phone;
    public $enable_whatsapp_notifications;
    public $enable_group_notifications;
    public $enable_employee_notifications;
    public $w_api_profile_id;
    public $w_api_token;
    public $work_group;
    public $company_policy;

    protected function getFormSchema(): array
    {
        $user = auth()->user();

        return [
            TextInput::make('name')
                ->required()
                ->default($user->name),

            TextInput::make('phone')
                ->tel()
                ->placeholder('Enter your phone number')
                ->prefixIcon('heroicon-o-phone')
                ->default($user->phone),

            TextInput::make('w_api_profile_id')
                ->default($user->w_api_profile_id),

            TextInput::make('w_api_token')
                ->default($user->w_api_token),

            TextInput::make('work_group')
                ->default($this->work_group),

            Textarea::make('company_policy')
                ->placeholder('Enter company policy')
                ->rows(5)
                ->required(false),

            Toggle::make('enable_whatsapp_notifications')
                ->default($user->enable_whatsapp_notifications == 1 ? 1 : 0),

            Toggle::make('enable_group_notifications')
                ->default($user->enable_group_notifications == 1 ? 1 : 0),

            Toggle::make('enable_employee_notifications')
                ->default($user->enable_employee_notifications == 1 ? 1 : 0),

            Placeholder::make(' ')
                ->content(' ')
                ->columnSpan('full')
        ];
    }

    public function mount()
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->enable_whatsapp_notifications = $user->enable_whatsapp_notifications == 1 ? 1 : 0;
        $this->enable_group_notifications = $user->enable_group_notifications == 1 ? 1 : 0;
        $this->enable_employee_notifications = $user->enable_employee_notifications == 1 ? 1 : 0;
        $this->w_api_token = $user->w_api_token;
        $this->w_api_profile_id = $user->w_api_profile_id;
        $this->work_group = $user->work_group;
        $this->company_policy = $user->company_policy;
    }

    public function saveProfile()
    {
        $data = $this->form->getState();

        auth()->user()->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'w_api_profile_id' => $data['w_api_profile_id'],
            'w_api_token' => $data['w_api_token'],
            'work_group' => $data['work_group'],
            'enable_whatsapp_notifications' => ($data['enable_whatsapp_notifications']),
            'enable_group_notifications' => ($data['enable_group_notifications']),
            'enable_employee_notifications' => ($data['enable_employee_notifications']),
            'company_policy' => $data['company_policy'],
        ]);

        Notification::make()
            ->title('User Profile Updated!')
            ->success()
            ->send();

        return redirect('/admin/setting');
    }

    protected function getActions(): array
    {
        return [];
    }

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }
     public static function getNavigationGroup(): string
    {
        return __('General settings');
    }
    public function getTitle(): string | Htmlable
    {
        return __(static::$title ?? (string) str(class_basename(static::class))
        ->kebab()
        ->replace('-', ' ')
        ->title());
    }
}
