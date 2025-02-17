<div wire:listen="updateLocale">
    <livewire:locale-switcher />

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 p-2">{{ __('Key') }}</th>
                <th class="border border-gray-300 p-2">{{ __('Value') }}</th>
                <th class="border border-gray-300 p-2">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($translations as $key => $value)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $key }}</td>
                    <td class="border border-gray-300 p-2">
                        @if ($editingKey === $key)
                            <input type="text" wire:model.defer="editingValue" class="border p-1 w-full">
                        @else
                            {{ $value }}
                        @endif
                    </td>
                    <td class="border border-gray-300 p-2">
                        @if ($editingKey === $key)
                            <button wire:click="saveTranslation('{{ $key }}')"
                                class="bg-amber-500 hover:bg-amber-600 text-black px-2 py-1 rounded transition">
                               {{ __('Save') }}
                            </button>
                            <button wire:click="cancelEdit"
                                class="bg-gray-500 hover:bg-gray-600 text-yellow px-2 py-1 rounded transition">
                                {{ __('Cancel') }}
                            </button>
                        @else
                            <button wire:click="editTranslation('{{ $key }}', '{{ $value }}')"
                                class="text-orange-400 px-2 py-1 rounded transition">
                                {{ __('Edit') }}
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center border border-gray-300 p-2">No translations found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
