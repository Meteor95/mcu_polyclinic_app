<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{Gate, URL, View, Log};
use App\Services\MpdfService; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        $this->app->singleton('mpdf.service', function () {
            return new MpdfService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
        \Illuminate\Foundation\AliasLoader::getInstance()->alias('MPDF', \App\Facades\MPDF::class);
    }
}
