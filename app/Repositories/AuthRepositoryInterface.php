<?php

namespace App\Repositories;

use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
  public function check(SpotifyWebAPI $api);
  public function setAccessToken($accessToken, SpotifyWebAPI $api);
  public function setRefreshToken();
}
