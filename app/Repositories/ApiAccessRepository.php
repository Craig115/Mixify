<?php
namespace App\Repositories;

use Session as AppSession;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Http\Request;

class ApiAccessRepository implements ApiAccessRepositoryInterface
{
  public function Access(Request $request, Session $session)
  {
    $checkToken = AppSession::get('accessToken');

    if($checkToken)
    {
      $api = new SpotifyWebAPI();
      $api->setAccessToken($checkToken);
      $request->api = $api;
      return $request;
    }

    $code = substr($_SERVER['REQUEST_URI'], 14,300);

    $session->requestAccessToken($code);
    $accessToken = $session->getAccessToken();

    $api = new SpotifyWebAPI();
    $api->setAccessToken($accessToken);

    AppSession::put('accessToken', $accessToken);

    $request->api = $api;

    return $request;
  }
}
