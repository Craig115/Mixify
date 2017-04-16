<!DOCTYPE html>
<html>
    <head>
        <title>Mixify</title>

        <link href="/css/app.css" rel="stylesheet">
        <link rel='shortcut icon' type='image/x-icon' href='images/favicon.ico'/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
        <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <meta name="description" content="Discover new music">
        <meta name="keywords" content="Spotify, Music, Discover, Mix">
        <meta name="viewport" content="width=device-width">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    </head>
    <body>
        <div id="profile-container" class="container">

            <div class="header">
              <div class="mobile-menu">
                <i v-on:click="showMenu" class="fa fa-bars fa-2x" aria-hidden="true" v-show="!mobile"></i>
                <i v-on:click="hideMenu" class="fa fa-bars fa-2x" aria-hidden="true" v-show="mobile"></i>
              </div>
              <div class="head-container">
                <div class="header-left">
                  <h5><a href="/"><b>Mix</b>ify</a></h5>
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

                <div id="left-section" class="left-section" v-bind:class="{ activateMenu: mobile }">
                  <div class="playlists">
                    <h1><b>Your Playlists</b></h1>

                    <playlist list="{{ json_encode($playlists) }}"></playlist>

                    <template id ="playlists-template">
                      <div>
                        <h2 class="message" v-show="message.length">
                          @{{ message }}
                          @{{ errormsg }}
                        </h2>
                        <ul class="list-group" v-for="playlist in list" >
                            <li class="playlist-name">
                              <input value="@{{ playlist.id }}" id="@{{ playlist.name }}" type="checkbox" name="playlist" v-model="checkplaylist.playlists">
                              <label for="@{{ playlist.name }}">
                                <span class="fa-stack">
                                    <i class="fa fa-circle-o fa-stack-1x"></i>
                                    <i class="fa fa-circle fa-stack-1x"></i>
                                </span>
                                @{{ playlist.name }}
                              </label>
                            </li>
                        </ul>
                      </div>
                      <button v-if="!hide" v-on:click="validateInput" class="playlist" type="submit"><b>Add</b> to Playlist</button>
                      <input type="hidden" id="token" v-model="csrf" value="{{ csrf_token() }}">
                    </template>
                  </div>
                </div>


                <div id="right-section" class="right-section" v-bind:class="{ hideRight: mobile, 'showRight': slideout }">
                  <div class="artists" v-show="hide">
                    <h3>Your Top Artists</h3>

                    <artist list="{{ json_encode($artists) }}"></artist>

                    <template id ="artists-template">
                      <div class="artist-profiles">
                        <ul class="artist-group" v-for="artist in list" >
                            <img transition="fade" class="artist-image b-lazy" src="images/2.gif" data-src ="@{{ artist.images[0].url }}"/>
                            <li class="data-name">@{{ artist.name }}</li>
                        </ul>
                      </div>
                    </template>
                  </div>

<<<<<<< HEAD
              <form method="POST" id="recommended" action="/recommended">
                  {{ csrf_field() }}
                  <button type="submit">Get Your Recommended Tracks</button>
              </form>

=======
                  <div class="tracks">
                    <h3 v-if="hide"> Your Top Tracks</h3>
                    <h3 v-else>Your Mix</h3>
                    <div v-if="hidehelp" id="help" class="help">Select a song, then select a playlist.<i class="fa fa-times" v-on:click="hideHelp" aria-hidden="true"></i></div>

                    <track list="{{ json_encode($tracks) }}" mix=""></track>

                    <template id ="tracks-template">
                      <div v-show="mixmessage.length" class="help">@{{ mixmessage }}<i class="fa fa-times" v-on:click="hidemessage" aria-hidden="true"></i></div>
                      <div class="track-details">
                        <h1 v-show="loading"><i class="fa fa-spinner" aria-hidden="true"></i></h1>

                        <ul class="track-group" v-for="track in list" v-show="hide">
                            <div class="track-image">
                              <img class="album-covers b-lazy" src="images/2.gif" data-src="@{{ track.album.images[0].url }}"/>
                            </div>
                            <div class="track-text">
                              <li class="data-name">@{{ track.name }}</li>
                              <li class="data-name">@{{ track.artists[0].name }}</li>
                              <li class="data-name">@{{ track.album.name }}</li>
                           </div>
                        </ul>
                        <ul id="mix-group" class="track-group" v-for="track in mix" v-show="hidemix">
                            <div class="track-image">
                              <input type="checkbox" v-on:click="selectTrack" id="@{{ track.name }}" name="mix-track" v-model="checktrack" value="@{{ track.id }}">
                              <label for="@{{ track.name }}">
                                <span class="fa-stack">
                                    <i class="fa fa-circle-o fa-stack-1x"></i>
                                    <i class="fa fa-circle fa-stack-1x"></i>
                                </span>
                                <img class="mix-covers b-lazy" src="images/2.gif" data-src ="@{{ track.album.images[0].url }}"/>
                              </label>
                            </div>
                            <div class="track-text">
                              <li class="mix-name">@{{ track.name }}</li>
                              <li class="mix-name">@{{ track.artists[0].name }}</li>
                              <li class="mix-name">@{{ track.album.name }}</li>
                           </div>
                        </ul>
                      </div>
                      <div class="button-containers">
                        <div class="contain-modal" v-if="hide"><i id="show-modal" class="fa fa-info-circle" v-on:click="showMixModal = true" aria-hidden="true"></i><button class="mix" v-on:click="mixTracks" type="submit"><b>Get</b> Mix</button></div>
                        <div class="contain-modal" v-if="hide"><i id="show-modal" class="fa fa-info-circle" v-on:click="showMixifyModal = true" aria-hidden="true"></i><button class="mix" v-on:click="mixify" type="submit"><b>Mix</b>ify</button></div>

                        <button v-if="!hide" class="refresh-mix" v-on:click="mixTracks" type="submit"><b>Refresh</b> Mix</button>

                        <modal v-if="showMixModal" @close="showMixModal = false">
                        </modal>

                        <modal v-if="showMixifyModal" @close="showMixifyModal = false">
                          <h3 slot="header">Mixify</h3>
                          <div slot="body">Automatically creates a new playlist with a less popular selection of songs you may like, ranging from familiar artists
                            to lesser known ones. Designed for discovering new music.</slot>
                        </modal>
                      </div>
                    </template>

                 </div>
              </div>
>>>>>>> vue
            </div>
          </div>
        </div>
        <div class="footer">&copy; <?php echo date("Y"); ?> Mixify</div>
        <script type="text/x-template" id="modal-template">
          <transition name="modal">
            <div class="modal-mask">
              <div class="modal-wrapper">
                <div class="modal-container">

                  <div class="modal-header">
                    <slot name="header">
                      <h3>Get Mix</h3>
                    </slot>
                  </div>

                  <div class="modal-body">
                    <slot name="body">
                      Generate a list of 20 songs which are recommended to your music taste. Songs can be selected individually and added to playlists.
                    </slot>
                  </div>

                  <div class="modal-footer">
                    <slot name="footer">
                      <button class="modal-default-button" @click="$emit('close')">
                        OK
                      </button>
                    </slot>
                  </div>
                </div>
              </div>
            </div>
          </transition>
        </script>
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-91744558-1', 'auto');
          ga('send', 'pageview');

        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.8/vue.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.13/vue-resource.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.4.1/velocity.min.js"></script>
        <script src="/js/main.js"></script>
    </body>
</html>
