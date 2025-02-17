<?php

namespace App\Livewire;

use Livewire\Component;

class LocaleSwitcher extends Component
{
    public $locale;
    public $locales = [];

    public function mount()
    {
        $this->locale = app()->getLocale();
        $this->locales = array_keys(config('app.supported_locales'));
    }

    public function changeLocale($lang)
    {
        $this->locale = $lang;
        // Dispatch the event to notify other components
        $this->dispatch('localeChanged',locale: $this->locale);
    }


    public function render()
    {
        return view('livewire.locale-switcher');
    }
}
