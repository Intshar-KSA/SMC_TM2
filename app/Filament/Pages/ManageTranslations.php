<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Filament\Tables;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ManageTranslations extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static string $view = 'filament.pages.manage-translations';
    protected static ?string $navigationLabel = 'Manage Translations';
    protected static ?string $navigationGroup = 'Settings';

    public $translations = [];
    public $locales = [];
    public $locale = 'en';


    public function mount(): void
    {
        $this->locale = app()->getLocale();
        $this->locales = array_keys(config('app.supported_locales'));
        $this->loadTranslations();
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



    public function addTranslation(string $key, string $value)
    {


        foreach ($this->locales as $locale) {
            $path = lang_path("{$locale}.json");

            // Load existing translations or initialize an empty array
            $existingTranslations = File::exists($path) ? json_decode(File::get($path), true) : [];

            // Check if the key already exists, if not, add it
            if (!array_key_exists($key, $existingTranslations)) {
                $translatedValue = ($locale ===  $this->locale) ? $value : $this->translateValue($value,  $this->locale, $locale);
                $formattedKey = $this->formatKey($key);
                $existingTranslations[$formattedKey] = $translatedValue;
            }

            // Save the updated translations back to the file
            if (!File::put($path, json_encode($existingTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                Notification::make()
                    ->title('Error')
                    ->body("Failed to save translation for locale: {$locale}.")
                    ->danger()
                    ->send();
                return;
            }
        }
    }






protected function getFormattedTranslations(): array
{
    $formattedTranslations = [];
    foreach ($this->translations as $key => $value) {
        $formattedKey = $this->formatKey($key);
        $formattedTranslations[$formattedKey] = $value;
    }
    return $formattedTranslations;
}
    protected function getActions(): array
    {
        return [
            Action::make('addTranslation')
                // ->label('Add Translation')
                ->form([
                    TextInput::make('key')->label('Key')->required(),
                    TextInput::make('value')->label('Value')->required(),
                ])
                ->action(function (array $data)  {

                 $this->addTranslation($data['key'],$data['value']);
                 return  $this->loadTranslations();
                } ),

        ];
    }

    protected function formatKey(string $key): string
    {
        return ucfirst(strtolower(str_replace('_', ' ', $key)));
    }

    protected function translateValue(string $value, string $sourceLocale, string $targetLocale): string
    {
        try {
            $translator = new GoogleTranslate();
            $translator->setSource($sourceLocale);
            $translator->setTarget($targetLocale);
            return $translator->translate($value);
        } catch (\Exception $e) {
            // Log the error and return the original value if translation fails
            Log::error("Translation failed: {$e->getMessage()}");
            return $value;
        }
    }


    public static function canAccess(): bool
    {
        $user = Auth::user();
        $guard = Auth::getDefaultDriver();


        if ($guard === 'web') {
            if( $user?->isSuperAdmin()??false) {
                return true;
            }

            }
        return false;
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
