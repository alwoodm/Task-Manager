<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Ustawienie języka aplikacji na podstawie sesji
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
        
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }
    }
}
