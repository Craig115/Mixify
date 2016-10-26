<?php

namespace App\Http\Middleware;

use Closure;
use Session as AppSession;
use SpotifyWebAPI\SpotifyWebAPI;

class TokenHandler
{
    public function handle($request, Closure $next)
    {
      $accessToken = AppSession::get('accessToken');

      $api = new SpotifyWebAPI();

      $api->setAccessToken($accessToken);
      $request->api = $api;

      return $next($request);
    }
}
