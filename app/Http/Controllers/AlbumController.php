<?php

namespace App\Http\Controllers;

use App\Album;
use Illuminate\Http\Request;
use SpotifyWebAPI\Session;
use App\Http\Requests;
use Session as AppSession;
use App\Repositories\ApiAccessRepositoryInterface;

class AlbumController extends Controller
{
  protected $repository;

  public function __construct(Request $request, ApiAccessRepositoryInterface $repository, Session $session)
  {
    $this->repository = $repository;

    $session = AppSession::get('session');

    $repository->Check($request, $session);
  }

  public function getNewReleases(Album $album, Request $request)
  {
    $releases = $album->getNewAlbums($request->api);

    $releases = json_encode($releases);

    $file = 'splash.json';
    $path = public_path() . '/js/' . $file;

    $handle = fopen($path, 'w') or die("Couldn't open JSON file.");

    fwrite($handle, $releases);
    fclose($handle);

    exit();
  }
}
