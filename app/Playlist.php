<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SpotifyWebAPI\SpotifyWebAPI;

class Playlist extends Model
{
    public function getMyPlaylists(SpotifyWebAPI $api)
    {
      $playlists = $api->getMyPlaylists();
      
      return $playlists;
    }
}
