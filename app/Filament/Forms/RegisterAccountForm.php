<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterAccountForm
{

    protected static function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->tel()
            ->required()
            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/');
    }

        protected static function getPasswordFormComponent(): Component
        {
            return TextInput::make('password')
                ->label(__('filament-panels::pages/auth/register.form.password.label'))
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->required(fn (string $context) => $context === 'create')
                ->rule(Password::default())
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->same('passwordConfirmation')
                ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute'));
        }

        protected static function getPasswordConfirmationFormComponent(): Component
        {
            return TextInput::make('passwordConfirmation')
                ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->required(fn (string $context) => $context === 'create')
                ->dehydrated(false);
        }
    protected static function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/register.form.email.label'))
            ->email()
            ->unique(ignoreRecord: true)
            ->required()->maxLength(255);
    }
    protected static function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('filament-panels::pages/auth/register.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }
    public static function getRegisterAccountFormComponent(string $title): Section
    {
        return Section::make(__($title))
        ->schema([
        Self::getNameFormComponent(),
        Self::getPhoneFormComponent(),
        Self::getEmailFormComponent(),
        Self::getPasswordFormComponent(),
        Self::getPasswordConfirmationFormComponent(),

        ])
        ->columnSpan(['lg' => 1])
        ;
    }

}
