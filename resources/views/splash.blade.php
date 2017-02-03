<!DOCTYPE html>
<html>
    <head>
        <title>Mixify</title>
        <link href="/css/app.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    </head>
    <body>
      <div id="splash-container" class="container">
          <div class="image-area">
              <releases></releases>

              <template id="release-template">
                  <div class="new-releases">
                    <ul transition="fade" class="release-group" v-for="album in list" >
                        <img class="new-release b-lazy" src="images/2.gif" data-src ="@{{ album.images[0].url }}"/>
                    </ul>
                 </div>
              </template>

              <div class="jumbotron">
                  <div class="title"><b>Mix</b>ify</div>

                  <form method="POST" id="auth" action="/auth">
                    {{ csrf_field() }}
                    <button class="start" type="submit">Get Started</button>
                  </form>
              </div>
          </div>
      </div>
        <div class="footer">&copy; <?php echo date("Y"); ?> Mixify</div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.8/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.13/vue-resource.min.js"></script>
        <script src="/js/main.js"></script>
    </body>
</html>
