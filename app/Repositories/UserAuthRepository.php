<?php

namespace App\Repositories;

use Session as AppSession;
use Illuminate\Http\Request;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;


class UserAuthRepository implements AuthRepositoryInterface
{

  public function check(SpotifyWebAPI $api)
  {
    $accessToken = AppSession::get('accessToken');

    if($accessToken){

      $this->setAccessToken($accessToken, $api);

    } else {

      $this->setRefreshToken();

    }

    return $api;
  }

  public function setAccessToken($accessToken, SpotifyWebAPI $api)
  {
    $api->setAccessToken($accessToken);
  }

  public function setRefreshToken()
  {

  }


}
