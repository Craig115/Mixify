<?php

namespace App\Http\Middleware;

use Closure;
use Session as AppSession;
use App\Repositories\ApiAccessRepositoryInterface;

class CheckSession
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
        $session = AppSession::get('session');

        if ($session) {
          $next($request);
        } else {
          Redirect::to('/splash')->send();
        }

    }
}
