<?php

namespace App;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    public function getNewAlbums(SpotifyWebAPI $api)
    {
      $releases = $api->getNewReleases([
        'country' => 'GB',
      ]);

      return $releases;
    }
}
