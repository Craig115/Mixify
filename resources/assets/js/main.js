import Blazy from 'blazy'

Vue.component('releases', {
  template: '#release-template',

  props: ['list'],

  ready: function() {
    this.$http.get('/api', function (data) {
      this.list = data.albums.items;

      var bLazy = new Blazy({
          breakpoints: [{
              width: 420,
              src: 'data-src-small'
          }],
          success: function(element){
            setTimeout(function(){
                var parent = element.parentNode;
                parent.className = parent.className.replace(/\bloading\b/,'');
            }, 200);
          }
      });

    }).error(function (data, status, request) {
        console.log(request);
    });
  },

});


Vue.component('artist', {
  template: '#artists-template',

  props: ['list'],

  created() {
    this.parseJSON();
  },

  methods: {
    parseJSON: function(){
      this.list = JSON.parse(this.list);

      var bLazy = new Blazy({
          breakpoints: [{
              width: 420,
              src: 'data-src-small'
          }],

          success: function(element){
            setTimeout(function(){
                var parent = element.parentNode;
                parent.className = parent.className.replace(/\bloading\b/,'');
            }, 200);
          }
      });

    }
  }

});

Vue.component('track', {
  template: '#tracks-template',

  props: ['list', 'mix'],

  data: function(){
    return {
      loading: false,
      hide: true,
      hidemix: true,
      mixmessage: '',
      showMixModal: false,
      showMixifyModal: false,
      checktrack: []
    }
  },

  created() {
    this.parseJSON();

    var bLazy = new Blazy({
        breakpoints: [{
            width: 420,
            src: 'data-src-small'
        }],

        success: function(element){
          setTimeout(function(){
              var parent = element.parentNode;
              parent.className = parent.className.replace(/\bloading\b/,'');
          }, 200);
        }
    });

  },

  methods: {
    parseJSON: function() {
      this.list = JSON.parse(this.list);
    },

    mixTracks: function(){
      //Hide Elements and display Loading Bar
      this.$root.hidehelp = true;
      this.$root.hide = false;
      this.hidemix = false;
      this.hide = false;
      this.loading = true;
      //Make Request to get Mix then hide the loading bar
      this.$http.get('/recommended', function (data) {

          this.$root.mix = data;
          this.loading = false;
          this.hidemix = true;
          this.mix = this.$root.mix;

          var bLazy = new Blazy({
              breakpoints: [{
          	      width: 420,
                  src: 'data-src-small'
          	  }],

              success: function(element){

          	    setTimeout(function(){
          		      var parent = element.parentNode;
          		      parent.className = parent.className.replace(/\bloading\b/,'');
          	    }, 200);

              }
          });
          console.log(bLazy);
      }).error(function (data, status, request) {
        console.log(request);
      })
    },

    selectTrack: function() {
      var position = this.$root.$children.indexOf("Playlist");
      if(position == -1){
        this.$root.$children[0].$data.checkplaylist.tracks = this.checktrack;
      } else {
        this.$root.$children[position].$data.checkplaylist.tracks = this.checktrack;
      }
    },

    // This will create a new playlist with some random songs; some popular, some not.
    mixify: function() {

      //Hide Elements and display Loading Bar
      this.$root.hide = false;
      this.$root.hidehelp = false;
      this.hidemix = false;
      this.hide = false;
      this.loading = true;

      this.$http.get('/mixify', function (data) {

        this.loading = false;
        this.hidemix = true;

        this.createMixify();

      }).error(function (data, status, request) {
        console.log(request.responseText);
      });
  },

  createMixify: function() {

    this.$http.get('/createPlaylist', function (data) {
      console.log(data);
      this.mixmessage = "Created new mixify Playlist."
      console.log(this.message);
    }).error(function (data, status, request) {
      console.log(request.responseText);
    });

  },

  hidemessage: function() {
    this.mixmessage = '';
  }
}
});

Vue.component('playlist', {
  template: '#playlists-template',

  props: ['list'],

  created() {
    this.parseJSON();
  },

  data: function () {
    return {
      checkplaylist: {
        playlists: [],
        tracks: [],
      },
      csrf: '',
      message: '',
      errormsg: ''
    }
  },

  methods: {

    parseJSON: function(){
      this.list = JSON.parse(this.list);
    },

    validateInput: function() {

      if(this.checkplaylist.playlists.length && this.checkplaylist.tracks.length){
        this.addTracks();
      } else {
        this.message = 'You must select at least one Track AND one Playlist.';
      }

    },

    addTracks: function() {

      this.$http.headers.common['X-CSRF-TOKEN'] = this.csrf;
      var data = JSON.stringify(this.checkplaylist);

      this.$http.post('/addTracks/' + data).then((response) => {

      this.message = 'Successfully Added.';
      window.scrollTo(500, 0);

      }, (response) => {

        var error = JSON.stringify(response.responseText);
        this.message = 'There was an error.';

        console.log(response.responseText);

      });
    },

    sendError: function(error) {

      this.$http.headers.common['X-CSRF-TOKEN'] = this.csrf;

      this.$http.post('/error/' + error).then((response) => {
      }, (response) => {

       console.log("died");

      });
    }

  }
});


Vue.component('user', {

  template: '#user-template',

  props: ['detail'],

  data: function(){
    return {
      user: []
    }
  },

  created() {
      this.parseJSON();
    },

  methods: {
    parseJSON: function () {

      this.user = JSON.parse(this.detail);
      this.$dispatch('child-user', this.user);

    }
  }

});

Vue.component('modal', {
  template: '#modal-template'
})

new Vue({
  el: '.container',

  data: function () {
    return {
      mix: [],
      hide: true,
      hidehelp: false,
      mobile: false,
      menu: document.getElementById("left-section"),
      slideout: false
    }
  },

  methods: {

    showMenu: function(){

      this.mobile = true;
      this.slideout = false;

      Velocity(this.menu, { translateX: '50%' }, { duration: 350 });

      window.scrollTo(500, 0);
    },

    hideMenu: function(){

      Velocity(this.menu, "reverse", 350);

      setTimeout(this.hideDelay, 350);

    },
    hideDelay: function(){

      this.mobile = false;
      this.slideout = true;

    },
    hideHelp: function(){

      document.getElementById("help").style.display = "none";

    }

  }
});
