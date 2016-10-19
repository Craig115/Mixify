<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SpotifyWebAPI\SpotifyWebAPI;

class Track extends Model
{
    public function getTopTracks(SpotifyWebAPI $api, $limit)
    {
      $tracks = $api->getMyTop('tracks', [
        'limit' => $limit,
      ]);
      
      return $tracks;
    }
}
