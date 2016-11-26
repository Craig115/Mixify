<?php

namespace App\Http\Controllers;

use App\Track;
use App\Artist;
use App\Playlist;
use App\Profile;
use App\Http\Requests;
use SpotifyWebAPI\Session;
use Session as AppSession;
use Illuminate\Http\Request;
use App\Repositories\ApiAccessRepositoryInterface;

class PlaylistController extends Controller
{
    protected $repository;
    public $session;

    public function __construct(Request $request, ApiAccessRepositoryInterface $repository, Session $session)
    {
      $this->repository = $repository;

      $session = AppSession::get('session');

      $repository->Check($request, $session);
    }

    public function addTracks($data, Playlist $playlist, Request $request, Profile $profile)
    {
      $user = $profile->getUser($request->api);
      $username = $user->id;

      $structure = json_decode($data);
      $playlists = $structure->playlists;
      $tracks = $structure->tracks;

      $playlist->addTracks($request->api, $username, $playlists, $tracks);
    }
}
