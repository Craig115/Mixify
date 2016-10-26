<?php

namespace App\Http\Controllers;

use App\Track;
use App\Artist;
use App\Playlist;
use App\Profile;
use Session as AppSession;
use App\Http\Requests;
use Illuminate\Http\Request;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApiController extends Controller
{
    public $session;

    public function __construct(Session $session)
    {
      $this->session = $session;
    }

    public function Test(Session $session)
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

      AppSession::put('authorized', 'yes');

      header('Location: ' . $authorizeUrl);

    }
}
