<?php
namespace App\Repositories;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Http\Request;


interface ApiAccessRepositoryInterface
{
  public function Access(Request $request, Session $session);
}
