<x-filament-panels::page>
    <form wire:submit.prevent="saveTranslations">
        {{ $this->form }}
    </form>

    <x-filament-actions::modals />

    <table class="w-full mt-4 border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Key</th>
                <th class="border px-4 py-2">Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($translations as $key => $value)
                <tr>
                    <td class="border px-4 py-2">{{ $key }}</td>
                    <td class="border px-4 py-2">
                        <input type="text" wire:model="translations.{{ $key }}" class="w-full p-1 border rounded" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

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
