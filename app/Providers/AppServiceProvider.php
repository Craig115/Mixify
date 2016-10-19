<?php

namespace App\Providers;

use GuzzleHttp\HandlerStack;
use Illuminate\Support\ServiceProvider;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI ;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('SpotifyWebAPI\Session', function () {
            return new Session('f14895140c8944678bb07d346e423cfb', '1e9fd3799064451aa5ce3ea33696a5da', 'http://138.68.141.154/home');
        });

        $this->app->bind('SpotifyWebAPI\SpotifyWebAPI', function () {
            return new SpotifyWebAPI();
        });

    }
}
