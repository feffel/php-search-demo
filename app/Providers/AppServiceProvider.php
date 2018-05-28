<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('dateRange', function ($attribute, $value) {
            $dateRegex = '([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})';
            $regex = '/^' . $dateRegex . ':' . $dateRegex .'$/';
            return (bool) preg_match($regex, $value);
        });

        Validator::extend('priceRange', function ($attribute, $value) {
            $numRegex = '[0-9]+(\.[0-9]+)?';
            $regex = '/^' . $numRegex . ':' . $numRegex . '$/';
            return (bool) preg_match($regex, $value);
        });
        \App::bind('hotelUtils', function() {
            return new \App\Search\Hotel\Utils;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
