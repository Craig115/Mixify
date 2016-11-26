//var Mix = Vue.extend({

//});

Vue.component('artist', {
  template: '#artists-template',

  props: ['list'],

  created() {
    this.parseJSON();
  },

  methods: {
    parseJSON: function(){
      this.list = JSON.parse(this.list);
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
      checktrack: []
    }
  },

  created() {
    this.parseJSON();
  },

  methods: {
    parseJSON: function(){
      this.list = JSON.parse(this.list);
    },

    mixTracks: function(){
      //Hide Elements and display Loading Bar
      vm.$data.hide = false;
      this.hide = false;
      this.loading = true;
      //Make Request to get Mix then hide the loading bar
      this.$http.get('/recommended', function (data) {
          vm.$data.mix = data;
          this.loading = false;
          this.mix = vm.$data.mix;
      }).error(function (data, status, request) {
          console.log(request);
      })
    },

    refreshMix: function(){
      this.loading = true;
      this.hidemix = false;
      //Make Request to get Mix then hide the loading bar
      this.$http.get('/recommended', function (data) {
          vm.$data.mix = data;
          this.loading = false;
          this.hidemix = true;
          this.mix = vm.$data.mix;
      }).error(function (data, status, request) {
          console.log(request);
      })
    },

    selectTrack: function(){
      vm.$children[1].$data.checkplaylist.tracks = this.checktrack;
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
        status: ''
      },
      csrf: ''
    }
  },

  methods: {
    parseJSON: function(){
      this.list = JSON.parse(this.list);
    },

    addTracks: function () {
      this.$http.headers.common['X-CSRF-TOKEN'] = this.csrf;
      var data = JSON.stringify(this.checkplaylist);
      this.$http.post('/addTracks/' + data).then((response) => {
        this.status = 'Successfully Added.';
      }, (response) => {
        console.log(response.data);
        this.status = 'There was an error';
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
    }
  }

});

vm = new Vue({
  el: '.container',

  data: function () {
    return {
      mix: [],
      hide: true
    }
  },
});
