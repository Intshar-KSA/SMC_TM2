<x-filament-panels::page>
    <form wire:submit.prevent="saveTranslations">
        {{ $this->form }}
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
