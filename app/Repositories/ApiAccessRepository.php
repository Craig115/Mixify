<?php
namespace App\Repositories;

use Session as AppSession;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Http\Request;

class ApiAccessRepository implements ApiAccessRepositoryInterface
{
  public function Check(Request $request, Session $session)
  {

    if($session->accessToken) {
      $this->Refresh($request, $session);
    } else {
      $this->Access($request, $session);
    }
  }

  public function Access(Request $request, Session $session)
  {
    $code = substr($_SERVER['REQUEST_URI'], 14,306);

    $session->requestAccessToken($code);

    $this->Set($request, $session);
  }

  public function Refresh(Request $request, Session $session)
  {
    $refreshToken = $session->getRefreshToken();

    $session->refreshAccessToken($refreshToken);

    $this->Set($request, $session);
  }

  public function Set(Request $request, Session $session)
  {
    $accessToken = $session->getAccessToken();

    $api = new SpotifyWebAPI();

    $api->setAccessToken($accessToken);

    $request->api = $api;

    return $request;
  }
}
