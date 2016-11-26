<?php

namespace App\Http\Controllers;

use App\Api;
use App\Track;
use App\Artist;
use App\Playlist;
use App\Profile;
use App\Http\Requests;
use SpotifyWebAPI\Session;
use Session as AppSession;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Http\Request;
use App\Repositories\ApiAccessRepositoryInterface;


class UserController extends Controller
{
    protected $repository;
    public $session;

    public function __construct(Request $request, ApiAccessRepositoryInterface $repository, Session $session)
    {
      $this->repository = $repository;

      $session = AppSession::get('session');

      $repository->Check($request, $session);
    }

    public function getProfile(Request $request, Playlist $playlist, Track $track, Profile $profile, Artist $artist)
    {
      $playlist = $playlist->getMyPlaylists($request->api);
      $tracks = $track->getTopTracks($request->api, 5);
      $user = $profile->getUser($request->api);
      $artists = $artist->getTopArtists($request->api);

      AppSession::put('topTracks', $tracks->items);

      return view('profile', [
        'playlists' => $playlist->items,
        'tracks' => $tracks->items,
        'user' => $user,
        'artists' => $artists->items
      ]);
    }

    public function getRecommended(Request $request, Track $track, ApiAccessRepositoryInterface $repository)
    {
      $toptracks = AppSession::get('topTracks');

      $recommendedTracks = $track->getRecommendedTracks($request->api, $toptracks);

       return $recommendedTracks;

    }

}
