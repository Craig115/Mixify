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

      if(is_null($session)) {
        return view('splash');
      } else {
        $repository->Check($request, $session);
      }
    }

    public function getProfile(Request $request, Playlist $playlist, Track $track, Profile $profile, Artist $artist)
    {
      $allplaylists = $playlist->getMyPlaylists($request->api, 30);
      $tracks = $track->getTopTracks($request->api, 5);
      $user = $profile->getUser($request->api);
      $artists = $artist->getTopArtists($request->api);

      $myplaylists = array();

      foreach($allplaylists->items as $play){
        if($play->owner->id == $user->id ){
          array_push($myplaylists, $play);
        }
      }

      AppSession::put('topTracks', $tracks->items);

      return view('profile', [
        'playlists' => $myplaylists,
        'tracks' => $tracks->items,
        'user' => $user,
        'artists' => $artists->items
      ]);
    }

    public function getRecommended(Request $request, Track $track)
    {
      $toptracks = AppSession::get('topTracks');
      $recommendedTracks = $track->getRecommendedTracks($request->api, $toptracks);

      return $recommendedTracks;

    }

    public function mixify(Request $request, Track $track)
    {
      $toptracks = AppSession::get('topTracks');

      //Randomize tunable values
      $tuneables = array(
        'danceability' => rand(0, 10) / 10,
        'energy' => rand(0, 10) / 10,
        'loudness' => rand(0, 10) / 10,
        'popularity' => rand(0, 100),
        'speechiness' => rand(0, 10) / 10
      );
      dd($toptracks);
      $recommendedTracks = $track->Mixify($request->api, $toptracks, $tuneables);

      $mixifytracks = array();

      foreach ($recommendedTracks as $mixifytrack) {
        array_push($mixifytracks, $mixifytrack->id);
      }

      AppSession::put('mixifyTracks', $mixifytracks);

      return;

    }

    public function handleError($error)
    {
      $message = json_decode($error);
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
      $headers .= "From: craig@138.68.141.154";

      mail('craigmcaulay98@googlemail.com', 'Error Report', $error, $headers);
    }
}
