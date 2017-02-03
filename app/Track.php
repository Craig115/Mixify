<?php

namespace App;

use Session as AppSession;
use Illuminate\Database\Eloquent\Model;
use SpotifyWebAPI\SpotifyWebAPI;

class Track extends Model
{
    public function getTopTracks(SpotifyWebAPI $api, $limit)
    {
      $tracks = $api->getMyTop('tracks', [
        'limit' => $limit,
      ]);

      return $tracks;
    }

    public function getRecommendedTracks(SpotifyWebAPI $api, $toptracks)
    {
      $recommendations = $api->getRecommendations([
        'seed_tracks' => [$toptracks[0]->id, $toptracks[1]->id, $toptracks[2]->id, $toptracks[3]->id, $toptracks[4]->id],
      ]);

      return $recommendations->tracks;
    }

    public function Mixify(SpotifyWebAPI $api, $toptracks, $tuneables)
    {
      $recommendations = $api->getRecommendations([
        'seed_tracks' => [$toptracks[0]->id, $toptracks[1]->id, $toptracks[2]->id, $toptracks[3]->id, $toptracks[4]->id],
        'danceability' => $tuneables['danceability'],
        'energy' => $tuneables['energy'],
        'loudness' => $tuneables['loudness'],
        'popularity' => $tuneables['popularity'],
        'speechiness' => $tuneables['speechiness']
      ]);

      return $recommendations->tracks;
    }
}
