<?php

namespace App\Providers;

use App\View\Composers\FontSizeComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class FontSizeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register font size view composer for all views
        View::composer('*', FontSizeComposer::class);
    }
}
