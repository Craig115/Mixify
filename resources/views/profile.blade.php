<!DOCTYPE html>
<html>
    <head>
        <title>Mixify</title>

        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">

            <div class="header">
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

                <div class="left-section">
                  <div class="playlists">
                    <h1><b>My Playlists</b></h1>

                    <playlist list="{{ json_encode($playlists) }}"></playlist>

                    <template id ="playlists-template">
                      <div>
                      <ul class="list-group" v-for="playlist in list" >
                          <li class="playlist-name"><input type="checkbox" name="playlist" value="@{{ playlist.id }}">@{{ playlist.name }}</li>
                      </ul>
                    </div>
                    </template>
                  </div>
                </div>

                <div class="right-section">
                  <div class="artists">
                    <h3> My Top Artists</h1>

                    <artist list="{{ json_encode($artists) }}"></artist>

                    <template id ="artists-template">
                      <div class="artist-profiles">
                        <ul class="artist-group" v-for="artist in list" >
                            <img class="artist-image" src ="@{{ artist.images[0].url }}"/>
                            <li class="data-name">@{{ artist.name }}</li>
                        </ul>
                      </div>
                    </template>
                  </div>

                  <div class="tracks">
                    <h3> My Top Tracks</h1>

                    <track list="{{ json_encode($tracks) }}"></track>

                    <template id ="tracks-template">
                      <div class="track-details">
                        <ul class="track-group" v-for="track in list" >
                            <div class="track-image">
                              <img class="album-covers" src="@{{ track.album.images[0].url }}"/>
                            </div>
                            <div class="track-text">
                              <li class="data-name">@{{ track.name }}</li>
                              <li class="data-name">@{{ track.artists[0].name }}</li>
                              <li class="data-name">@{{ track.album.name }}</li>
                           </div>
                        </ul>
                        <button class="mix" v-on:click="mixTracks" v-if="!mix" type="submit"><b>Get</b> Mix</button>
                      </div>
                    </template>
                 </div>
              </div>

              <template id ="mix-template" v-show="mix">
                <ul class="track-group" transition name="fade" v-for="track in mix" >
                    <div class="track-image">
                      <input type="checkbox" name="mix-track" value="@{{ track.id }}"><img class="album-covers" src="@{{ track.album.images[0].url }}"/>
                    </div>
                    <div class="track-text">
                      <li class="data-name">@{{ track.name }}</li>
                      <li class="data-name">@{{ track.artists[0].name }}</li>
                      <li class="data-name">@{{ track.album.name }}</li>
                   </div>
                </ul>
              </template>

            </div>
          </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.8/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.13/vue-resource.min.js"></script>
        <script src="/js/main.js"></script>
    </body>
</html>
