<!DOCTYPE html>
<html>
    <head>
        <title>Spotify</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="content">
              <user detail="{{ json_encode($user) }}" ></user>

                <template id="user-template">
                  <div>
                    <li>@{{ user.display_name }}</li>
                    <li><img src="@{{ user.images[0].url }}"/></li>
                  </div>
                </template>

                <h1>Your Playlists</h1>

                <personal list="{{ json_encode($playlists) }}"></personal>

                <template id ="personal-template">
                  <div>
                  <ul class="list-group" v-for="playlist in list" >
                      <li>@{{ playlist.name }}</li>
                  </ul>
                  <div>
                </template>

                <h3> My Top Artists</h1>

                <personal list="{{ json_encode($artists) }}"></personal>

                <template id ="personal-template">
                  <div>
                  <ul class="list-group" v-for="artist in list" >
                      <li>@{{ artist.name }}</li>
                  </ul>
                  <div>
                </template>

                <h3> My Top Tracks</h1>

                <personal list="{{ json_encode($tracks) }}"></personal>

                <template id ="personal-template">
                  <div>
                  <ul class="list-group" v-for="track in list" >
                      <li>@{{ track.name }}</li>
                  </ul>
                  <div>
                </template>


              <form method="POST" id="recommended" action="/recommended">
                  {{ csrf_field() }}
                  <button type="submit">Get Your Recommended Tracks</button>
              </form>

            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.8/vue.js"></script>
        <script src="/js/main.js"></script>
    </body>
</html>
