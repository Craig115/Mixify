<?php

namespace App\Scripts;

use App\Album;
use Session as AppSession;


$username = $argv[1];
$password = $argv[2];

$album = new Album();

if($username == "Craig11" && $password == "January11"){
  getNewReleases();
}


function getNewReleases(Album $album, Request $request)
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
