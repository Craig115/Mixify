<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
              <h2>Hi {{ $user->id }}</h2>
              <ul>
                <h1>Your playlists</h1>
                @foreach($playlists as $play)
              <li>{{ $play->name }}</li>
                @endforeach
              </ul>

              <ul>
                <h1>Your Top Artists</h1>
                @foreach($artists as $artist)
              <li>{{ $artist->name }}</li>
                @endforeach
              </ul>

              <ul>
                <h1>Your Top Tracks</h1>
                @foreach($tracks as $track)
              <li>{{ $track->name }}</li>
                @endforeach
              </ul>

            </div>
        </div>
    </body>
</html>
