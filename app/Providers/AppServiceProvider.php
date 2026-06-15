<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Set default string length for MySQL
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
    }
}
