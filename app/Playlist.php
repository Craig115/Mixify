<?php

namespace App;

use SpotifyWebAPI\Session;
use Session as AppSession;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    public function getMyPlaylists(SpotifyWebAPI $api, $limit)
    {
      $playlists = $api->getMyPlaylists([
        'limit' => $limit,
      ]);

      return $playlists;
    }

    public function addTracks(SpotifyWebAPI $api, $username, $playlists, $tracks)
    {
      foreach($playlists as $playlistid){
        $api->addUserPlaylistTracks($username, $playlistid, $tracks);
      }

      return "Added successfully.";
    }

    public function createPlaylist(SpotifyWebAPI $api, $username, $playlistname)
    {
      $api->createUserPlaylist($username, [
        'name' => $playlistname,
      ]);

      return "Created successfully";
    }
}
