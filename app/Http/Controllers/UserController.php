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
use App\Repositories\AuthRepositoryInterface;
#
class UserController extends Controller
{
    public $session;
    public $api;
    protected $repository;

    public function __construct(SpotifyWebAPI $api, AuthRepositoryInterface $repository)
    {
      $this->api = $api;
      $this->repository = $repository;
    }

    public function getProfile(AuthRepositoryInterface $repository, SpotifyWebAPI $api, Playlist $playlist, Track $track, Profile $profile, Artist $artist)
    {
      $repository->check($api);

      $playlist = $playlist->getMyPlaylists($api);
      $tracks = $track->getTopTracks($api, 5);
      $user = $profile->getUser($api);
      $artists = $artist->getTopArtists($api);

      return view('profile', [
        'playlists' => $playlist->items,
        'tracks' => $tracks->items,
        'user' => $user,
        'artists' => $artists->items
      ]);

    }

}
