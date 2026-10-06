<?php

namespace App\Providers;

use App\Http\CustomHelpers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if($this->app->environment('production')) {
        //     URL::forceScheme('https');
        // }
        Schema::defaultStringLength(191);
        Schema::defaultStringLength(125);
    }

    
}
