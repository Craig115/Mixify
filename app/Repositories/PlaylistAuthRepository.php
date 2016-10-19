<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Http\Requests;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;


class OauthApiRepository
{

    public $stack;

    public function __construct(HandlerStack $stack)
    {
      $this->stack = $stack;

      $middleware = new Oauth1([
          'client_id'    => 'f14895140c8944678bb07d346e423cfb',
      ]);

      $stack->push($middleware);

    }


  public function authorize()
  {
    $client = new Client([
        'base_uri' => 'https://accounts.spotify.com',
        'handler' => $stack,
        'auth' => 'oauth'
    ]);

  }

}
