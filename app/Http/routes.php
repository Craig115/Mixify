<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', function()
{
  $check = Session::get('authorized');

  if($check) {
    return "here";
    return redirect()->action('SpotifyApiController@Authorize');
  } else {
    return view('splash');
  }
});

Route::get('/profile', 'UserController@getProfile');


Route::post('/auth', 'SpotifyApiController@Authorize');
Route::get('/recommendations', 'SpotifyApiController@Authorize');

Route::get('/home', function(){
  return view('test');
})
