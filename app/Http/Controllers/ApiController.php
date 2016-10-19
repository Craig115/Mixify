<?php

namespace App\Http\Controllers;

use Session as AppSession;
use App\Http\Requests;
use Illuminate\Http\Request;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use App\Http\Controllers\UserController;

class ApiController extends Controller
{
    public $session;

    public function __construct(Session $session)
    {
      $this->session = $session;
    }

    public function Authenticate(Session $session)
    {
      $scopes = array(
        'playlist-read-private',
        'user-read-private',
        'user-library-read',
        'user-top-read'
      );

      $authorizeUrl = $session->getAuthorizeUrl(array(
        'scope' => $scopes
      ));

      header('Location: ' . $authorizeUrl);
    }

    public function access(Session $session, Request $request)
    {
      $code = substr($request->fullurl(), 32,300);

      $session->requestAccessToken($code);
      $accessToken = $session->getAccessToken();

      AppSession::put('accessToken', $accessToken);

      return view('home');
    }
}
