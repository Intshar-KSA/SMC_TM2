<x-filament-panels::page>
    <form wire:submit.prevent="saveTranslations">
        {{ $this->form }}
    </form>

    <x-filament-actions::modals />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const keyValueInputs = document.querySelectorAll('.filament-forms-key-value-component input');
            keyValueInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    this.value = this.value.trim();
                });
            });
        });
    </script>
</x-filament-panels::page>
