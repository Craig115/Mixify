
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

new Vue({
  el: '.container'
});
