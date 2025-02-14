<?php

namespace App\Filament\Forms;

use Filament\Component;
use Filament\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Textarea;
use Filament\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DateForm
{


    public static function getDateFormComponent(): Section
    {
        return    Section::make()
        ->schema([
            Placeholder::make('created_at')
                ->label(__('labels.created_at'))
                ->content(fn (Model $record): ?string => $record->created_at?->diffForHumans()),

            Placeholder::make('updated_at')
                ->label(__('labels.last_modified_at'))
                ->content(fn (Model $record): ?string => $record->updated_at?->diffForHumans()),
        ])
        ->columnSpan(['lg' => 1]) ->hidden(fn (?Model $record) => $record === null);
    }

}
