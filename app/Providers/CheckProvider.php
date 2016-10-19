<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CheckProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind('check', function () {
          return new \App\Classes\Check;
      });
    }
}
