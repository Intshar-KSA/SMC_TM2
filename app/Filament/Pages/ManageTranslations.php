<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageTranslations extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static string $view = 'filament.pages.manage-translations';
    protected static ?string $navigationLabel = 'Manage Translations';
    protected static ?string $navigationGroup = 'Settings';

    public $translations = [];
    public $locale = 'en';

    public function mount(): void
    {
        $this->locale = app()->getLocale();
        $this->loadTranslations();
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('locale')
                ->label('Language')
                ->options([
                    'en' => 'English',
                    'ar' => 'Arabic',
                ])
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->changeLocale($state)),

            KeyValue::make('translations')
                ->label('Translations')
                ->keyLabel('Key') // Customize the key column label
                ->valueLabel('Value') // Customize the value column label
                ->addable(true) // Allow adding new key-value pairs
                ->editableKeys(true) // Allow editing keys
                ->editableValues(true) // Allow editing values
                ->deletable(true), // Allow deleting key-value pairs
        ];
    }

    public function loadTranslations(): void
    {
        $path = lang_path("{$this->locale}.json");
        $this->translations = File::exists($path) ? json_decode(File::get($path), true) : [];
    }

    public function changeLocale($locale): void
    {
        $this->locale = $locale;
        $this->loadTranslations();
    }

    public function saveTranslations(): void
    {
        // Save translations for the current locale
        $currentLocalePath = lang_path("{$this->locale}.json");
        File::put($currentLocalePath, json_encode($this->translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Add new keys to all other locales
        $locales = ['en', 'ar']; // Add all supported locales here
        foreach ($locales as $locale) {
            if ($locale === $this->locale) {
                continue; // Skip the current locale
            }

            $path = lang_path("{$locale}.json");
            $existingTranslations = File::exists($path) ? json_decode(File::get($path), true) : [];

            // Add new keys from the current locale to other locales
            foreach ($this->translations as $key => $value) {
                if (!array_key_exists($key, $existingTranslations)) {
                    $existingTranslations[$key] = ''; // Add the new key with an empty value
                }
            }

            // Save the updated translations for the locale
            File::put($path, json_encode($existingTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        Notification::make()
            ->title('Translations saved successfully!')
            ->success()
            ->send();
    }

    protected function getActions(): array
    {
        return [
            Action::make('saveTranslations')
                ->label('Save Translations')
                ->action('saveTranslations')
                ->color('primary'),
        ];
    }
}
