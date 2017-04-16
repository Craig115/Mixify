(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/*!
  hey, [be]Lazy.js - v1.8.2 - 2016.10.25
  A fast, small and dependency free lazy load script (https://github.com/dinbror/blazy)
  (c) Bjoern Klinggaard - @bklinggaard - http://dinbror.dk/blazy
*/
;
(function(root, blazy) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register bLazy as an anonymous module
        define(blazy);
    } else if (typeof exports === 'object') {
        // Node. Does not work with strict CommonJS, but
        // only CommonJS-like environments that support module.exports,
        // like Node.
        module.exports = blazy();
    } else {
        // Browser globals. Register bLazy on window
        root.Blazy = blazy();
    }
})(this, function() {
    'use strict';

    //private vars
    var _source, _viewport, _isRetina, _supportClosest, _attrSrc = 'src', _attrSrcset = 'srcset';

    // constructor
    return function Blazy(options) {
        //IE7- fallback for missing querySelectorAll support
        if (!document.querySelectorAll) {
            var s = document.createStyleSheet();
            document.querySelectorAll = function(r, c, i, j, a) {
                a = document.all, c = [], r = r.replace(/\[for\b/gi, '[htmlFor').split(',');
                for (i = r.length; i--;) {
                    s.addRule(r[i], 'k:v');
                    for (j = a.length; j--;) a[j].currentStyle.k && c.push(a[j]);
                    s.removeRule(0);
                }
                return c;
            };
        }

        //options and helper vars
        var scope = this;
        var util = scope._util = {};
        util.elements = [];
        util.destroyed = true;
        scope.options = options || {};
        scope.options.error = scope.options.error || false;
        scope.options.offset = scope.options.offset || 100;
        scope.options.root = scope.options.root || document;
        scope.options.success = scope.options.success || false;
        scope.options.selector = scope.options.selector || '.b-lazy';
        scope.options.separator = scope.options.separator || '|';
        scope.options.containerClass = scope.options.container;
        scope.options.container = scope.options.containerClass ? document.querySelectorAll(scope.options.containerClass) : false;
        scope.options.errorClass = scope.options.errorClass || 'b-error';
        scope.options.breakpoints = scope.options.breakpoints || false;
        scope.options.loadInvisible = scope.options.loadInvisible || false;
        scope.options.successClass = scope.options.successClass || 'b-loaded';
        scope.options.validateDelay = scope.options.validateDelay || 25;
        scope.options.saveViewportOffsetDelay = scope.options.saveViewportOffsetDelay || 50;
        scope.options.srcset = scope.options.srcset || 'data-srcset';
        scope.options.src = _source = scope.options.src || 'data-src';
        _supportClosest = Element.prototype.closest;
        _isRetina = window.devicePixelRatio > 1;
        _viewport = {};
        _viewport.top = 0 - scope.options.offset;
        _viewport.left = 0 - scope.options.offset;


        /* public functions
         ************************************/
        scope.revalidate = function() {
            initialize(scope);
        };
        scope.load = function(elements, force) {
            var opt = this.options;
            if (elements && elements.length === undefined) {
                loadElement(elements, force, opt);
            } else {
                each(elements, function(element) {
                    loadElement(element, force, opt);
                });
            }
        };
        scope.destroy = function() {            
            var util = scope._util;
            if (scope.options.container) {
                each(scope.options.container, function(object) {
                    unbindEvent(object, 'scroll', util.validateT);
                });
            }
            unbindEvent(window, 'scroll', util.validateT);
            unbindEvent(window, 'resize', util.validateT);
            unbindEvent(window, 'resize', util.saveViewportOffsetT);
            util.count = 0;
            util.elements.length = 0;
            util.destroyed = true;
        };

        //throttle, ensures that we don't call the functions too often
        util.validateT = throttle(function() {
            validate(scope);
        }, scope.options.validateDelay, scope);
        util.saveViewportOffsetT = throttle(function() {
            saveViewportOffset(scope.options.offset);
        }, scope.options.saveViewportOffsetDelay, scope);
        saveViewportOffset(scope.options.offset);

        //handle multi-served image src (obsolete)
        each(scope.options.breakpoints, function(object) {
            if (object.width >= window.screen.width) {
                _source = object.src;
                return false;
            }
        });

        // start lazy load
        setTimeout(function() {
            initialize(scope);
        }); // "dom ready" fix

    };


    /* Private helper functions
     ************************************/
    function initialize(self) {
        var util = self._util;
        // First we create an array of elements to lazy load
        util.elements = toArray(self.options);
        util.count = util.elements.length;
        // Then we bind resize and scroll events if not already binded
        if (util.destroyed) {
            util.destroyed = false;
            if (self.options.container) {
                each(self.options.container, function(object) {
                    bindEvent(object, 'scroll', util.validateT);
                });
            }
            bindEvent(window, 'resize', util.saveViewportOffsetT);
            bindEvent(window, 'resize', util.validateT);
            bindEvent(window, 'scroll', util.validateT);
        }
        // And finally, we start to lazy load.
        validate(self);
    }

    function validate(self) {
        var util = self._util;
        for (var i = 0; i < util.count; i++) {
            var element = util.elements[i];
            if (elementInView(element, self.options) || hasClass(element, self.options.successClass)) {
                self.load(element);
                util.elements.splice(i, 1);
                util.count--;
                i--;
            }
        }
        if (util.count === 0) {
            self.destroy();
        }
    }

    function elementInView(ele, options) {
        var rect = ele.getBoundingClientRect();

        if(options.container && _supportClosest){
            // Is element inside a container?
            var elementContainer = ele.closest(options.containerClass);
            if(elementContainer){
                var containerRect = elementContainer.getBoundingClientRect();
                // Is container in view?
                if(inView(containerRect, _viewport)){
                    var top = containerRect.top - options.offset;
                    var right = containerRect.right + options.offset;
                    var bottom = containerRect.bottom + options.offset;
                    var left = containerRect.left - options.offset;
                    var containerRectWithOffset = {
                        top: top > _viewport.top ? top : _viewport.top,
                        right: right < _viewport.right ? right : _viewport.right,
                        bottom: bottom < _viewport.bottom ? bottom : _viewport.bottom,
                        left: left > _viewport.left ? left : _viewport.left
                    };
                    // Is element in view of container?
                    return inView(rect, containerRectWithOffset);
                } else {
                    return false;
                }
            }
        }      
        return inView(rect, _viewport);
    }

    function inView(rect, viewport){
        // Intersection
        return rect.right >= viewport.left &&
               rect.bottom >= viewport.top && 
               rect.left <= viewport.right && 
               rect.top <= viewport.bottom;
    }

    function loadElement(ele, force, options) {
        // if element is visible, not loaded or forced
        if (!hasClass(ele, options.successClass) && (force || options.loadInvisible || (ele.offsetWidth > 0 && ele.offsetHeight > 0))) {
            var dataSrc = getAttr(ele, _source) || getAttr(ele, options.src); // fallback to default 'data-src'
            if (dataSrc) {
                var dataSrcSplitted = dataSrc.split(options.separator);
                var src = dataSrcSplitted[_isRetina && dataSrcSplitted.length > 1 ? 1 : 0];
                var srcset = getAttr(ele, options.srcset);
                var isImage = equal(ele, 'img');
                var parent = ele.parentNode;
                var isPicture = parent && equal(parent, 'picture');
                // Image or background image
                if (isImage || ele.src === undefined) {
                    var img = new Image();
                    // using EventListener instead of onerror and onload
                    // due to bug introduced in chrome v50 
                    // (https://productforums.google.com/forum/#!topic/chrome/p51Lk7vnP2o)
                    var onErrorHandler = function() {
                        if (options.error) options.error(ele, "invalid");
                        addClass(ele, options.errorClass);
                        unbindEvent(img, 'error', onErrorHandler);
                        unbindEvent(img, 'load', onLoadHandler);
                    };
                    var onLoadHandler = function() {
                        // Is element an image
                        if (isImage) {
                            if(!isPicture) {
                                handleSources(ele, src, srcset);
                            }
                        // or background-image
                        } else {
                            ele.style.backgroundImage = 'url("' + src + '")';
                        }
                        itemLoaded(ele, options);
                        unbindEvent(img, 'load', onLoadHandler);
                        unbindEvent(img, 'error', onErrorHandler);
                    };
                    
                    // Picture element
                    if (isPicture) {
                        img = ele; // Image tag inside picture element wont get preloaded
                        each(parent.getElementsByTagName('source'), function(source) {
                            handleSource(source, _attrSrcset, options.srcset);
                        });
                    }
                    bindEvent(img, 'error', onErrorHandler);
                    bindEvent(img, 'load', onLoadHandler);
                    handleSources(img, src, srcset); // Preload

                } else { // An item with src like iframe, unity games, simpel video etc
                    ele.src = src;
                    itemLoaded(ele, options);
                }
            } else {
                // video with child source
                if (equal(ele, 'video')) {
                    each(ele.getElementsByTagName('source'), function(source) {
                        handleSource(source, _attrSrc, options.src);
                    });
                    ele.load();
                    itemLoaded(ele, options);
                } else {
                    if (options.error) options.error(ele, "missing");
                    addClass(ele, options.errorClass);
                }
            }
        }
    }

    function itemLoaded(ele, options) {
        addClass(ele, options.successClass);
        if (options.success) options.success(ele);
        // cleanup markup, remove data source attributes
        removeAttr(ele, options.src);
        removeAttr(ele, options.srcset);
        each(options.breakpoints, function(object) {
            removeAttr(ele, object.src);
        });
    }

    function handleSource(ele, attr, dataAttr) {
        var dataSrc = getAttr(ele, dataAttr);
        if (dataSrc) {
            setAttr(ele, attr, dataSrc);
            removeAttr(ele, dataAttr);
        }
    }

    function handleSources(ele, src, srcset){
        if(srcset) {
            setAttr(ele, _attrSrcset, srcset); //srcset
        }
        ele.src = src; //src 
    }

    function setAttr(ele, attr, value){
        ele.setAttribute(attr, value);
    }

    function getAttr(ele, attr) {
        return ele.getAttribute(attr);
    }

    function removeAttr(ele, attr){
        ele.removeAttribute(attr); 
    }

    function equal(ele, str) {
        return ele.nodeName.toLowerCase() === str;
    }

    function hasClass(ele, className) {
        return (' ' + ele.className + ' ').indexOf(' ' + className + ' ') !== -1;
    }

    function addClass(ele, className) {
        if (!hasClass(ele, className)) {
            ele.className += ' ' + className;
        }
    }

    function toArray(options) {
        var array = [];
        var nodelist = (options.root).querySelectorAll(options.selector);
        for (var i = nodelist.length; i--; array.unshift(nodelist[i])) {}
        return array;
    }

    function saveViewportOffset(offset) {
        _viewport.bottom = (window.innerHeight || document.documentElement.clientHeight) + offset;
        _viewport.right = (window.innerWidth || document.documentElement.clientWidth) + offset;
    }

    function bindEvent(ele, type, fn) {
        if (ele.attachEvent) {
            ele.attachEvent && ele.attachEvent('on' + type, fn);
        } else {
            ele.addEventListener(type, fn, { capture: false, passive: true });
        }
    }

    function unbindEvent(ele, type, fn) {
        if (ele.detachEvent) {
            ele.detachEvent && ele.detachEvent('on' + type, fn);
        } else {
            ele.removeEventListener(type, fn, { capture: false, passive: true });
        }
    }

    function each(object, fn) {
        if (object && fn) {
            var l = object.length;
            for (var i = 0; i < l && fn(object[i], i) !== false; i++) {}
        }
    }

    function throttle(fn, minDelay, scope) {
        var lastCall = 0;
        return function() {
            var now = +new Date();
            if (now - lastCall < minDelay) {
                return;
            }
            lastCall = now;
            fn.apply(scope, arguments);
        };
    }
});

},{}],2:[function(require,module,exports){
'use strict';

var _blazy = require('blazy');

var _blazy2 = _interopRequireDefault(_blazy);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

Vue.component('releases', {
  template: '#release-template',

  props: ['list'],

  ready: function ready() {
    this.$http.get('/api', function (data) {
      this.list = data.albums.items;

      var bLazy = new _blazy2.default({
        breakpoints: [{
          width: 420,
          src: 'data-src-small'
        }],
        success: function success(element) {
          setTimeout(function () {
            var parent = element.parentNode;
            parent.className = parent.className.replace(/\bloading\b/, '');
          }, 200);
        }
      });
    }).error(function (data, status, request) {
      console.log(request);
    });
  }

});

Vue.component('artist', {
  template: '#artists-template',

  props: ['list'],

  created: function created() {
    this.parseJSON();
  },


  methods: {
    parseJSON: function parseJSON() {
      this.list = JSON.parse(this.list);

      var bLazy = new _blazy2.default({
        breakpoints: [{
          width: 420,
          src: 'data-src-small'
        }],

        success: function success(element) {
          setTimeout(function () {
            var parent = element.parentNode;
            parent.className = parent.className.replace(/\bloading\b/, '');
          }, 200);
        }
      });
    }
  }

});

Vue.component('track', {
  template: '#tracks-template',

  props: ['list', 'mix'],

  data: function data() {
    return {
      loading: false,
      hide: true,
      hidemix: true,
      mixmessage: '',
      showMixModal: false,
      showMixifyModal: false,
      checktrack: []
    };
  },

  created: function created() {
    this.parseJSON();

    var bLazy = new _blazy2.default({
      breakpoints: [{
        width: 420,
        src: 'data-src-small'
      }],

      success: function success(element) {
        setTimeout(function () {
          var parent = element.parentNode;
          parent.className = parent.className.replace(/\bloading\b/, '');
        }, 200);
      }
    });
  },


  methods: {
    parseJSON: function parseJSON() {
      this.list = JSON.parse(this.list);
    },

    mixTracks: function mixTracks() {
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

        var bLazy = new _blazy2.default({
          breakpoints: [{
            width: 420,
            src: 'data-src-small'
          }],

          success: function success(element) {

            setTimeout(function () {
              var parent = element.parentNode;
              parent.className = parent.className.replace(/\bloading\b/, '');
            }, 200);
          }
        });
        console.log(bLazy);
      }).error(function (data, status, request) {
        console.log(request);
      });
    },

    selectTrack: function selectTrack() {
      var position = this.$root.$children.indexOf("Playlist");
      if (position == -1) {
        this.$root.$children[0].$data.checkplaylist.tracks = this.checktrack;
      } else {
        this.$root.$children[position].$data.checkplaylist.tracks = this.checktrack;
      }
    },

    // This will create a new playlist with some random songs; some popular, some not.
    mixify: function mixify() {

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

    createMixify: function createMixify() {

      this.$http.get('/createPlaylist', function (data) {
        console.log(data);
        this.mixmessage = "Created new mixify Playlist.";
        console.log(this.message);
      }).error(function (data, status, request) {
        console.log(request.responseText);
      });
    },

    hidemessage: function hidemessage() {
      this.mixmessage = '';
    }
  }
});

Vue.component('playlist', {
  template: '#playlists-template',

  props: ['list'],

  created: function created() {
    this.parseJSON();
  },


  data: function data() {
    return {
      checkplaylist: {
        playlists: [],
        tracks: []
      },
      csrf: '',
      message: '',
      errormsg: ''
    };
  },

  methods: {

    parseJSON: function parseJSON() {
      this.list = JSON.parse(this.list);
    },

    validateInput: function validateInput() {

      if (this.checkplaylist.playlists.length && this.checkplaylist.tracks.length) {
        this.addTracks();
      } else {
        this.message = 'You must select at least one Track AND one Playlist.';
      }
    },

    addTracks: function addTracks() {
      var _this = this;

      this.$http.headers.common['X-CSRF-TOKEN'] = this.csrf;
      var data = JSON.stringify(this.checkplaylist);

      this.$http.post('/addTracks/' + data).then(function (response) {

        _this.message = 'Successfully Added.';
        window.scrollTo(500, 0);
      }, function (response) {

        var error = JSON.stringify(response.responseText);
        _this.message = 'There was an error.';

        console.log(response.responseText);
      });
    },

    sendError: function sendError(error) {

      this.$http.headers.common['X-CSRF-TOKEN'] = this.csrf;

      this.$http.post('/error/' + error).then(function (response) {}, function (response) {

        console.log("died");
      });
    }

  }
});

Vue.component('user', {

  template: '#user-template',

  props: ['detail'],

  data: function data() {
    return {
      user: []
    };
  },

  created: function created() {
    this.parseJSON();
  },


  methods: {
    parseJSON: function parseJSON() {

      this.user = JSON.parse(this.detail);
      this.$dispatch('child-user', this.user);
    }
  }

});

Vue.component('modal', {
  template: '#modal-template'
});

new Vue({
  el: '.container',

  data: function data() {
    return {
      mix: [],
      hide: true,
      hidehelp: false,
      mobile: false,
      menu: document.getElementById("left-section"),
      slideout: false
    };
  },

  methods: {

    showMenu: function showMenu() {

      this.mobile = true;
      this.slideout = false;

      Velocity(this.menu, { translateX: '50%' }, { duration: 350 });

      window.scrollTo(500, 0);
    },

    hideMenu: function hideMenu() {

      Velocity(this.menu, "reverse", 350);

      setTimeout(this.hideDelay, 350);
    },
    hideDelay: function hideDelay() {

      this.mobile = false;
      this.slideout = true;
    },
    hideHelp: function hideHelp() {

      document.getElementById("help").style.display = "none";
    }

  }
});

},{"blazy":1}]},{},[2]);

//# sourceMappingURL=main.js.map
