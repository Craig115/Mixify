<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SpotifyWebAPI\SpotifyWebAPI;

class Artist extends Model
{
  public function getTopArtists(SpotifyWebAPI $api)
  {
    $artists = $api->getMyTop('artists', [
      'limit' => 5,
    ]);

    return $artists;
  }
}
