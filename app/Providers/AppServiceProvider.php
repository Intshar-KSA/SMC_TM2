<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use App\Filament\Widgets\SendCompanyPolicy;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (Session::has('locale')) {
        //     App::setLocale(Session::get('locale'));
        // }
        // // Filament::serving(function () {
        // //     // Register the widget
        // //     Filament::registerWidget(SendCompanyPolicy::class);
        // // });
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en','ar']); // also accepts a closure
        });
    }
}
