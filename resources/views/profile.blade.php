<!DOCTYPE html>
<html>
    <head>
        <title>Mixify</title>

        <link href="/css/app.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
        <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    </head>
    <body>
        <div class="container">

            <div class="header">
              <div class="mobile-menu">
                <i v-on:click="showMenu" class="fa fa-bars fa-2x" aria-hidden="true" v-show="!mobile"></i>
                <i v-on:click="hideMenu" class="fa fa-bars fa-2x" aria-hidden="true" v-show="mobile"></i>
              </div>
              <div class="head-container">
                <div class="header-left">
                  <h5><b>Mix</b>ify</h5>
                </div>

                <div class="header-right">
                  <user detail="{{ json_encode($user) }}" ></user>

                  <template id="user-template">
                    <div class="user-section">
                      <h4>@{{ user.id }}</h4>
                      <img class="image-circle" height="40" width="40" src="@{{ user.images[0].url }}"/>
                    </div>
                  </template>
                </div>
              </div>
            </div>

            <div class="content">
              <div class="center-content">

                <div class="left-section" v-bind:class="{ activateMenu: mobile }">
                  <div class="playlists">
                    <h1><b>Your Playlists</b></h1>

                    <playlist list="{{ json_encode($playlists) }}"></playlist>

                    <template id ="playlists-template">
                      <div>
                        <h2>@{{ message }}</h2>
                        <ul class="list-group" v-for="playlist in list" >
                            <li class="playlist-name"><input value="@{{ playlist.id }}"type="checkbox" name="playlist" v-model="checkplaylist.playlists">@{{ playlist.name }}</li>
                        </ul>
                      </div>
                      <button v-if="!hide" v-on:click="addTracks" class="playlist" type="submit"><b>Add</b> to Playlist</button>
                      <input type="hidden" id="token" v-model="csrf" value="{{ csrf_token() }}">
                    </template>
                  </div>
                </div>


                <div class="right-section">
                  <div class="artists" v-show="hide">
                    <h3>Your Top Artists</h1>

                    <artist list="{{ json_encode($artists) }}"></artist>

                    <template id ="artists-template">
                      <div class="artist-profiles">
                        <ul class="artist-group" v-for="artist in list" >
                            <img transition="fade" class="artist-image" src ="@{{ artist.images[0].url }}"/>
                            <li class="data-name">@{{ artist.name }}</li>
                        </ul>
                      </div>
                    </template>
                  </div>

                  <div class="tracks">
                    <h3 v-if="hide"> Your Top Tracks</h3>
                    <h3 v-else>Your Mix</h3>

                    <track list="{{ json_encode($tracks) }}" mix=""></track>

                    <template id ="tracks-template">
                      <div class="track-details">
                        <h1 v-show="loading"><i class="fa fa-spinner" aria-hidden="true"></i></h1>

                        <ul class="track-group" v-for="track in list" v-show="hide">
                            <div class="track-image">
                              <img transition="fade" class="album-covers" src="@{{ track.album.images[0].url }}"/>
                            </div>
                            <div class="track-text">
                              <li class="data-name">@{{ track.name }}</li>
                              <li class="data-name">@{{ track.artists[0].name }}</li>
                              <li class="data-name">@{{ track.album.name }}</li>
                           </div>
                        </ul>
                        <ul transition="fade" id="mix-group" class="track-group" v-for="track in mix" v-show="hidemix">
                            <div class="track-image">
                              <input type="checkbox" v-on:click="selectTrack" name="mix-track" v-model="checktrack" value="@{{ track.id }}"><img transition="mix-fade" class="mix-covers" src="@{{ track.album.images[0].url }}"/>
                            </div>
                            <div class="track-text">
                              <li class="mix-name">@{{ track.name }}</li>
                              <li class="mix-name">@{{ track.artists[0].name }}</li>
                              <li class="mix-name">@{{ track.album.name }}</li>
                           </div>
                        </ul>
                      </div>
                      <button v-if="hide" class="mix" v-on:click="mixTracks" type="submit"><b>Get</b> Mix</button>
                      <button v-else class="mix" v-on:click="refreshMix" type="submit"><b>Refresh</b> Mix</button>
                    </template>

                 </div>
              </div>
            </div>
          </div>
          <div class="footer">&copy; 2016 Mixify</div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.8/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.13/vue-resource.min.js"></script>
        <script src="/js/main.js"></script>
    </body>
</html>
