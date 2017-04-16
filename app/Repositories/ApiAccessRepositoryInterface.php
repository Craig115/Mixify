<?php
namespace App\Repositories;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Illuminate\Http\Request;


interface ApiAccessRepositoryInterface
{
  public function Access(Request $request, Session $session);
  public function Check(Request $request, Session $session);
  public function Refresh(Request $request, Session $session);
  public function Set(Request $request, Session $session);
}
