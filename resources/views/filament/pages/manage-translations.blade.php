<x-filament-panels::page>
    <form wire:submit.prevent="addTranslation">
        {{ $this->form }}
    </form>

    <x-filament-actions::modals />

    @livewire('list-translations', ['translations' => $translations])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="text"]');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    this.value = this.value.trim();
                });
            });
        });
    </script>
</x-filament-panels::page>
