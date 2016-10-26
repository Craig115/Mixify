<!DOCTYPE html>
<html>
    <head>
        <title>Spotify</title>

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
              <h2>Hi <?php echo e($user->id); ?></h2>
              <ul>
                <h1>Your playlists</h1>
                <?php foreach($playlists as $play): ?>
              <li><?php echo e($play->name); ?></li>
                <?php endforeach; ?>
              </ul>

              <ul>
                <h1>Your Top Artists</h1>
                <?php foreach($artists as $artist): ?>
              <li><?php echo e($artist->name); ?></li>
                <?php endforeach; ?>
              </ul>

              <ul>
                <h1>Your Top Tracks</h1>
                <?php foreach($tracks as $track): ?>
              <li><?php echo e($track->name); ?></li>
                <?php endforeach; ?>
              </ul>

              <form method="POST" id="recommended" action="/recommended">
                  <?php echo e(csrf_field()); ?>

                  <button type="submit">Get Your Recommended Tracks</button>
              </form>

            </div>
        </div>
    </body>
</html>
