<select wire:change="changeLocale($event.target.value)" class="border p-2 rounded">
    @foreach ($locales as $lang)
        <option value="{{ $lang }}" {{ $locale === $lang ? 'selected' : '' }}>
            {{__($lang)}}
        </option>
    @endforeach
</select>
