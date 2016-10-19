<?php

namespace App\Http\Controllers;

use App\Api;
use App\Track;
use App\Artist;
use App\Playlist;
use App\Profile;
use App\Http\Requests;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public $session;

    public function __construct()
    {
      $this->middleware('token');
    }

    public function getProfile(Request $request, Playlist $playlist, Track $track, Profile $profile, Artist $artist)
    {
      $playlist = $playlist->getMyPlaylists($request->api);
      $tracks = $track->getTopTracks($request->api, 5);
      $user = $profile->getUser($request->api);
      $artists = $artist->getTopArtists($request->api);

      return view('profile', [
        'playlists' => $playlist->items,
        'tracks' => $tracks->items,
        'user' => $user,
        'artists' => $artists->items
      ]);

    }

}
