;
/**
 * Presenter jQuery Plugin
 * This version doesn't loads the slides with ajax. We need all slides on on HTML page
 */
(function($) {

  /**
   * plugin body
   * @param element
   * @param options
   */
  $.presenter = function(element, options) {

    var defaults = {
      ratio : 4 / 3,
      fontscale : 80,
      animclass : 'animate',
      animshow : 'animated',
      topMargin : 0,
      transitions : [ 'default','roll3d', 'default', 'impress']
    };

    var intern = {
      cPage : 0,
      vPage : 0,
      count : 0,
      screenX : 0,
      screenY : 0
    };

    var plugin = this;

    /**
     * init plugin
     */
    var init = function() {

      // init setting
      plugin.element = element;
      plugin.settings = $.extend({}, defaults, options);
      plugin.intern = intern;
      plugin.intern.count = $('article').length;

      // setup visual Controls
      setupControlBar();

      // default animation
      rotateAnimations();

      // setup controls
      setupControls();

      // rescale slide
      setDimensions();

      // start slide
      slideTo('load');
    };

    /**
     * calculate next slide number
     * @param move
     */
    var setSlide = function(move) {

      switch (move) {
        case 'next':
          if (fadeHasHidden()) {
            fadeIn();
            return false;
          }

          if (plugin.intern.cPage < plugin.intern.count - 1) {
            plugin.intern.cPage++;
          }
          break;
        case 'prev':
          if (plugin.intern.cPage > 0) {
            plugin.intern.cPage--;
          }
          break;
        case 'load':
          if ( $(location).attr('href').indexOf('#') === $(location).attr('href').length - 1 ) {
            plugin.intern.cPage = 0;
            plugin.intern.vPage = 0;
          } else if (localStorage && localStorage.getItem('cPage')) {
            plugin.intern.cPage = parseInt(localStorage.getItem('cPage'));
            plugin.intern.vPage = parseInt(localStorage.getItem('cPage'));
          }
          break;
        case 'start':
          plugin.intern.cPage = 0;
          plugin.intern.vPage = 0;
          fadeOutAll();
          break;
      }

      return plugin.intern.cPage;
    };

    /**
     * slide next prev
     * @param move
     */
    function slideTo(move) {
      if (setSlide(move) === false) {
        return;
      }
      showSlide(plugin.intern.cPage);
    }

    /**
     * show given slide number
     * @param pagenum
     */
    function showSlide(pagenum) {
      
      $('.slide').removeClass('prevprev prev show next nextnext');
      $('.slide' + (pagenum - 2)).addClass('prevprev');
      $('.slide' + (pagenum - 1)).addClass('prev');
      $('.slide' + pagenum).addClass('show');
      $('.slide' + (pagenum + 1)).addClass('next');
      $('.slide' + (pagenum + 2)).addClass('nextnext');

      // we already have seen this page : show all content
      if (plugin.intern.vPage >= plugin.intern.cPage && plugin.intern.cPage > 0) {
        fadeInAll();
      }

      // remember which pages not to animate
      if (plugin.intern.cPage > plugin.intern.vPage && plugin.intern.cPage > 0) {
        plugin.intern.vPage = plugin.intern.cPage;
      }

      // remember Page on reload
      // todo: check if we can set localStorage first (ipad private browsing)
      if (localStorage) {
        localStorage.setItem('cPage', plugin.intern.cPage);
      }
    }

    /**
     * handle visual controls
     */
    function setupControlBar() {
      $('.presentation').before('<nav class="nav hide"><div>' +
        '<button class="toFirst">First</button>' +
        '<button class="toPrev">Prev</button>' +
        '<button class="changeAnimation">animation : <span class="animation"></span></button>' +
        '<button class="toNext">Next</button>' +
        '</div></nav>');
    }

    /**
     *  chose Controls
     */
    function setupControls() {
      if (Modernizr.touch) {
        bindTouch();
      } else {
        bindKeyboard();
        bindMouse();
      }
    }

    /**
     * setup mouse controls
     */
    function bindMouse() {

      /*
      $('body').click(function(e) {

        if (!$('.nav').hasClass('hide')) {
          return
        }

        var marginX = plugin.intern.screenX * plugin.settings.clickMove;
        var marginY = plugin.intern.screenY * (plugin.settings.clickMove * 2);

        if (e.screenX < marginX) {
          slideTo('prev');
        } else if ((plugin.intern.screenX - e.screenX) < marginX) {
          slideTo('next');
        } else {
          if (e.screenY < marginY) {
            slideTo('start');
          } else if ((plugin.intern.screenY - e.screenY) < marginY) {
            fadeInAll();
          }
        }
      });
      */

      $('.toFirst').click(function() {
        slideTo('start');
      });
      $('.toPrev').click(function() {
        slideTo('prev');
      });
      $('.toNext').click(function() {
        slideTo('next');
      });
      $('.changeAnimation').click(function() {
        rotateAnimations();
      });

      $('body').dblclick(function() {
        toggleNaviagtion();
      });
    }

    /**
     * toggle navigation menu
     */
    function toggleNaviagtion() {
      if ($('.nav').hasClass('hide')) {
        plugin.settings.topMargin = 70;
      } else {
        plugin.settings.topMargin = 0;
      }

      setDimensions();
      $('.nav').toggleClass('hide');
    }

    /**
     * rotate animation array and set them
     */
    function rotateAnimations() {
      $('.presentation').removeClass($(plugin.settings.transitions).last());
      var what = plugin.settings.transitions.shift();
      plugin.settings.transitions.push(what);
      $('.presentation').addClass(what);
      $('span.animation').text(what);
    }

    /**
     * setup touch controls
     */
    function bindTouch() {
      $('body').bind('touchmove',
        function(e) {
          e.preventDefault();
        }).doubleTap(
        function() {
          toggleNaviagtion();
        }).swipeRight(
        function() {
          slideTo('next');
        }).swipeLeft(
        function() {
          slideTo('prev');
        }).swipeUp(
        function() {
          slideTo('start');
        }).swipeDown(
        function() {
          fadeInAll();
        });

      $('.toFirst').tap(function() {
        slideTo('start');
      });
      $('.toPrev').tap(function() {
        slideTo('prev');
      });
      $('.toNext').tap(function() {
        slideTo('next');
      });
      $('.changeAnimation').tap(function() {
        rotateAnimations();
      });

    }

    /**
     * setup keyboard controls
     */
    function bindKeyboard() {
      $(document).keyup(function(event) {
        event.preventDefault();
        switch (event.keyCode) {
          case 37: // left arrow
            slideTo('prev');
            break;
          case 32: // space
          case 39: // right arrow
            slideTo('next');
            break;
          case 40: // down arrow
            fadeInAll();
            break;
          case 38: // up arrow
          case 36: // pos1
          case 33: // page up
            slideTo('start');
            break;
          case 77: // m
          case 78: // n
            toggleNaviagtion();
            break;
        }
      });
    }

    /**
     * dynamic page/slide scaling
     */
    function setDimensions() {

      // test document against window because zepto handles it the other way
      plugin.intern.screenX = $(document).width() ? $(document).width() : $(window).width();
      plugin.intern.screenY = $(document).height() ? $(document).height() : $(window).height();

      plugin.intern.screenY = plugin.intern.screenY - plugin.settings.topMargin;

      var newwidth = (plugin.intern.screenX > plugin.intern.screenY * plugin.settings.ratio) ? plugin.intern.screenY * plugin.settings.ratio : plugin.intern.screenX;
      var newheight = (plugin.intern.screenX > plugin.intern.screenY * plugin.settings.ratio) ? plugin.intern.screenY : plugin.intern.screenX / plugin.settings.ratio;

      // scale a bit smaller
      newwidth = newwidth * 0.95;
      newheight = newheight * 0.95;

      var topmargin = Math.floor((plugin.intern.screenY - newheight) / 2);
      var fontsize = (newheight + newwidth) / plugin.settings.fontscale;

      $('.slides').css({
        'width' : newwidth,
        'height' : newheight,
        'marginTop' : topmargin,
        'fontSize' : fontsize
      });
    }

    /**
     * detect if page has hidden objects
     */
    function fadeHasHidden() {
      return $(".show ." + plugin.settings.animclass).length > 0;
    }

    /**
     * fade in page objects - one at each time
     */
    function fadeIn() {
      var e = $(".show ." + plugin.settings.animclass).first();

      if (e.length < 1) {
        return;
      }

      var oldClass = $(e).attr("class").split(' ')[0];
      var newClass = $(e).attr("class").substring(4).split(' ')[0];
      $(e).removeClass(plugin.settings.animclass).removeClass(oldClass).addClass(newClass).addClass(plugin.settings.animshow);
    }

    /**
     * fade out page objects - one at each time
     */
    function fadeOut() {
      var e = $(".show ." + plugin.settings.animshow).first();

      if (e.length < 1) {
        return;
      }

      var oldClass = $(e).attr("class").split(' ')[0];
      var newClass = 'Off-' + oldClass;
      $(e).removeClass(plugin.settings.animshow).removeClass(oldClass).addClass(newClass).addClass(plugin.settings.animclass);
    }

    /**
     * fade in whole page
     */
    function fadeInAll() {
      $(".slide.show ." + plugin.settings.animclass).each(function() {
        fadeIn();
      });
    }

    /**
     * fade whole presentation out
     */
    function fadeOutAll() {
      $(".slide ." + plugin.settings.animshow).each(function() {
        fadeOut();
      });
    }

    // call scaling on resize
    $(window).resize(setDimensions);

    // init plugin
    init();
  };

  /**
   * setup plugin
   * @param options
   */
  $.fn.presenter = function(options) {
    return this.each(function() {
      if ($(this).data('presenter') == undefined) {
        var plugin = new $.presenter(this, options);
        $(this).data('presenter', plugin);
      }
    });
  };

})(jQuery);
