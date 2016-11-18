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

  props: ['list'],

  created() {
    this.parseJSON();
  },

  methods: {
    parseJSON: function(){
      this.list = JSON.parse(this.list);
    },

    mixTracks: function(){
      this.$http.get('/recommended', function (data) {
          vm.$data.mix = data;
          this.list = vm.$data.mix;
      }).error(function (data, status, request) {
          console.log(request);
      })
    }
  }
});

Vue.component('playlist', {
  template: '#playlists-template',

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
      mix: []
    }
  }
});
