<?php

namespace App\Http\Middleware;

use Closure;
use SpotifyWebAPI\Session;

class IsAuth
{
    public $session;

    public function __construct(Session $session)
    {
      $this->session = $session;
    }

    public function handle($request, Closure $next)
    {
      $scopes = array(
        'playlist-read-private',
        'user-read-private',
        'user-library-read',
        'user-top-read'
      );

      $authorizeUrl = $this->session->getAuthorizeUrl(array(
        'scope' => $scopes
      ));

      header('Location: ' . $authorizeUrl);
    }
}
