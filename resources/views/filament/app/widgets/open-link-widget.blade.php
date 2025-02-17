<x-filament::widget>
    <x-filament::card>
        <div class="flex justify-center">
            @php
                $sheetApiUrl = $this->getSheetApiUrl(); // استدعاء الدالة لجلب الرابط
            @endphp

            @if ($sheetApiUrl)
                <a href="{{ $sheetApiUrl }}" target="_blank" class="btn btn-primary">
                    {{ __('Open my task sheet') }}
                </a>
            @else
                <p>No sheet URL available</p>
            @endif
        </div>
    </x-filament::card>
</x-filament::widget>
