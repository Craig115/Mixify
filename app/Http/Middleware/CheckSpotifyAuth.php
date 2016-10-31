<?php

namespace App\Http\Middleware;

use Session;
use Closure;

class CheckSpotifyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $authorized = Session::get('authorized');
      if($authorized){
        return $next($request);
      } else {
        return view('splash');
      }
    }
}
