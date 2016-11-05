
Vue.component('personal', {
  template: '#personal-template',

  props: ['list'],

  created() {
    this.list = JSON.parse(this.list);
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
  el: '.content'
});
