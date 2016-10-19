<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SpotifyWebAPI\SpotifyWebAPI;

class Profile extends Model {

    public function getUser(SpotifyWebAPI $api)
    {
      $profile = $api->me();

      return $profile;
    }

}
