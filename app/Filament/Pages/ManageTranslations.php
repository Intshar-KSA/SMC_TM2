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

    protected function getFormSchema(): array
    {
        return [
            Select::make('locale')
                ->label('Language')
                ->options(config('app.supported_locales'))
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->changeLocale($state))
                ->columnSpan(2),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->label('Key')->sortable(),
                TextColumn::make('value')->label('Value')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Translation')
                    ->form([
                        TextInput::make('value')->label('Value')->required(),
                    ])
                    ->action(fn ($record, $data) => $this->updateTranslation($record, $data['value'])),
            ])
            ->records(array_map(fn ($key, $value) => ['key' => $key, 'value' => $value], array_keys($this->translations), $this->translations));
    }

    public function loadTranslations(): void
    {
        $this->syncTranslations($this->translations);
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
                $existingTranslations[$key] = $translatedValue;
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

    public function syncTranslations($translations): void
{

    foreach ($this->locales as $locale) {
        if ($locale === $this->locale) {
            continue;
        }

        $path = lang_path("{$locale}.json");
        $existingTranslations = File::exists($path) ? json_decode(File::get($path), true) : [];

        foreach ($translations as $key => $value) {
            if (!array_key_exists($key, $existingTranslations)) {
                // Translate the value into the target locale
                $translatedValue = $this->translateValue($value, $this->locale, $locale);
                $existingTranslations[$key] = $translatedValue;
            }
        }

        if (!File::put($path, json_encode($existingTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                Log::error("TFailed to save translations for locale: {$locale}.");
            return;
        }

    }

}


    public function saveTranslations(): void
{
    // Validate translations before saving
    $validator = Validator::make($this->translations, [
        '*' => 'required|string', // Ensure all values are strings
    ]);

    if ($validator->fails()) {
        Notification::make()
            ->title('Validation Error')
            ->body('Please ensure all translation values are valid strings.')
            ->danger()
            ->send();
        return;
    }

    // Format keys before saving
    $formattedTranslations= $this->getFormattedTranslations();


    // Save formatted translations for the current locale
    $currentLocalePath = lang_path("{$this->locale}.json");
    if (!File::put($currentLocalePath, json_encode($formattedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        Notification::make()
            ->title('Error')
            ->body('Failed to save translations for the current locale.')
            ->danger()
            ->send();
        return;
    }

    Notification::make()
        ->title('Translations updated successfully!')
        ->success()
        ->send();
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
                ->label('Add Translation')
                ->form([
                    TextInput::make('key')->label('Key')->required(),
                    TextInput::make('value')->label('Value')->required(),
                ])
                ->action(function (array $data)  {

                 $this->addTranslation($data['key'],$data['value']);
                 return  $this->loadTranslations();
                } ),
                Action::make('saveTranslations')
                ->label('Save Translations')
                ->action('saveTranslations')
                ->color('primary'),
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
}
