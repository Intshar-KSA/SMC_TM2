<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\On;



class ListTranslations extends Component
{

    public $translations = [];
    public $locale = 'en';
    public $editingKey = null;
    public $editingValue = '';

    public function mount($translations): void
    {
        // $this->locale = app()->getLocale();
        $this->translations = $translations;
    }
    #[On('localeChanged')]
    public function updateLocale($locale)
    {
        $this->locale = $locale;
        // $this->dispatch('refresh-translations', locale: $locale);
        $this->loadTranslations();
    }


    public function editTranslation($key, $value)
    {
        $this->editingKey = $key;
        $this->editingValue = $value;
    }

    public function saveTranslation($key)
    {
        if ($this->editingKey !== null) {
            $formattedKey = $this->formatKey($key);
            $this->translations[$formattedKey] = $this->editingValue;
            $this->saveToFile();
            $this->editingKey = null;
        }
    }

    protected function formatKey(string $key): string
    {
        return ucfirst(strtolower(str_replace('_', ' ', $key)));
    }

    public function cancelEdit()
    {
        $this->editingKey = null;
    }

    private function saveToFile()
    {
        $path = lang_path("{$this->locale}.json");
        File::put($path, json_encode($this->translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function loadTranslations(): void
    {
        $path = lang_path("{$this->locale}.json");
        $this->translations = File::exists($path) ? json_decode(File::get($path), true) : [];
    }

    public function render()
    {
        return view('livewire.list-translations');
    }
}
