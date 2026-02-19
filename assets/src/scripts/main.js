/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

// Import styles
import '../styles/main.scss';

import $ from 'jquery';
import UIkit from 'uikit';
import Icons from 'uikit/dist/js/uikit-icons';
import 'slick-carousel';
import 'simple-lightbox';
import AOS from 'aos';
import { CountUp } from 'countup.js';

// If you only need specific modules:
// import { Foundation, Accordion, Tabs } from 'foundation-sites';
(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
      
        UIkit.use(Icons);

        AOS.init({
          duration: 1000,
          once: true,
        },
          console.log('AOS loaded')
        );



        if (document.querySelector('.countup-animated-number')) {
          const animatedNumbers = document.querySelectorAll('.countup-animated-number .number-span');
        
          animatedNumbers.forEach((element) => {
            let targetNumber = parseInt(element.getAttribute('data-target'), 10);
            let delayMs = parseInt(element.getAttribute('data-delay'), 10);
            let startVal = parseInt(element.getAttribute('data-start'), 10) || 0;
        
            let countUp = new CountUp(element, targetNumber, {
              duration: 2,
              separator: ',',
              enableScrollSpy: true,
              scrollSpyOnce: true,
              scrollSpyDelay: 0,
              startVal: startVal,
            });
        
            if (countUp.error) {
              console.error(countUp.error);
            }
            console.log('CountUpJS loaded')
          });
        }

        //jQuery(function($) {
          // Initialize all carousels
          $('.carousel-wrapper').each(function() {
            var $this = $(this);
            var $slidesToShow = $this.data('slides-to-show');
            var $duration = $this.data('duration');
        
            console.log('Slides to show: ' + $slidesToShow);
            console.log('Duration: ' + $duration);
        
            $this.slick({
              infinite: true,
              slidesToShow: $slidesToShow,
              duration: $duration,
              slidesToScroll: 1,
              arrows: true,
              dots: false,
              prevArrow: '<button type="button" class="slick-prev cards-next"><svg xmlns="http://www.w3.org/2000/svg" width="23" height="41" viewBox="0 0 23 41" fill="none"> <path d="M21.123 1.5L2.12129 20.5018L21.123 39.5035" stroke="#062F6E" stroke-width="3" stroke-linecap="round"/></svg></button>',
              nextArrow: '<button type="button" class="slick-next cards-prev"><svg xmlns="http://www.w3.org/2000/svg" width="23" height="41" viewBox="0 0 23 41" fill="none"><path d="M1.5 39.5034L20.5018 20.5017L1.5 1.4999" stroke="#062F6E" stroke-width="3" stroke-linecap="round"/></svg></button>',
              responsive: [
                {
                  breakpoint: 1024,
                  settings: {
                    slidesToShow: 2,
                  }
                },
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 1,
                  }
                },
              ]
            });
          });
        //});

        
      
      },
      finalize: function() {
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // All Other Pages.
    'page': {
      init: function() {
        
        // Accordion
        $('.accordion-topic').click(function(){
          $(this).next('.accordion-response').slideToggle(500).toggleClass('current');
          $(this).toggleClass('current');
          $(this).parents('.accordion').siblings().find('.accordion-topic').slideUp(500);
          $(this).parents('.accordion').siblings().find('.accordion-response').removeClass('current');
        });
        

      }
    },
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.