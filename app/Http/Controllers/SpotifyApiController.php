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

<<<<<<< HEAD
    public function Authorize(Session $session)
=======
    public function spotifyLogin(Session $session)
>>>>>>> vue
    {
      $scopes = array(
        'playlist-read-private',
        'playlist-modify-private',
        'playlist-modify-public',
        'user-read-private',
        'user-top-read'
      );

      $authorizeUrl = $session->getAuthorizeUrl(array(
        'scope' => $scopes
      ));

      AppSession::put('session', $session);

      header('Location: ' . $authorizeUrl);

    }
}
