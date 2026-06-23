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
import SimpleLightbox from 'simple-lightbox';
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
      );

        document.querySelectorAll('.lightbox-gallery').forEach(function(gallery) {
            var anchors = gallery.querySelectorAll('a.lightbox-anchor');
            if (anchors.length) {
                new SimpleLightbox({
                    elements: anchors,
                    showCaptions: true,
                    captionAttribute: 'data-caption',
                });
            }
        });


        // ── Modal Links ────────────────────────────────────────────────────────
        // Lets ANY button or link open a UIkit modal by pointing at the modal's
        // #id — e.g. <a href="#contact-popup">. Also opens a modal automatically
        // when the page is loaded with a matching URL hash. Pairs with the
        // "Lightbox Popup" flexible layout (flexible/button_with_popup.php).
        function initModalLinks() {
          function openModal(hash) {
            if (!hash || hash.charAt(0) !== '#' || hash.length < 2) return false;
            var id = hash.slice(1);
            var modal = null;

            // 1. A popup section whose editor-set anchor matches.
            try {
              modal = document.querySelector('[uk-modal][data-modal-anchor="' + id + '"]');
            } catch (e) {
              modal = null;
            }

            // 2. Fall back to an element with that id — either a modal itself
            //    or a wrapper (e.g. a section) that contains one.
            if (!modal) {
              var target = document.getElementById(id);
              if (target) {
                modal = target.hasAttribute('uk-modal')
                  ? target
                  : target.querySelector('[uk-modal]');
              }
            }

            if (!modal) return false;
            UIkit.modal(modal).show();
            return true;
          }

          // Delegate clicks so links/buttons added anywhere on the page work,
          // including content rendered after load. We match every <a> and read
          // its .hash property, which returns the "#..." fragment even when the
          // editor saved an absolute URL (e.g. https://site.com/page/#popup).
          document.addEventListener('click', function(e) {
            var trigger = e.target.closest('a[href], [data-modal-target]');
            if (!trigger) return;
            var hash = trigger.getAttribute('data-modal-target') || trigger.hash || '';
            if (openModal(hash)) {
              e.preventDefault();
            }
          });

          // Open on arrival if the URL hash targets a modal.
          if (window.location.hash) {
            openModal(window.location.hash);
          }
        }

        initModalLinks();
        // ── End Modal Links ────────────────────────────────────────────────────


      function hoverCardsInit(){
        // get all elements with data-hover-card
        const hoverCards = document.querySelectorAll('.cards-style--primary');

        hoverCards.forEach(card => {
          // get the height of card-p ( if it exists )
          const cardP = card.querySelector('.hover-panel');
          let cardPHeight = 0;
          if (cardP) {
            cardPHeight = cardP.offsetHeight;
          }
          // set p height to 0 and overflow to hidden
          if (cardP) {
            cardP.style.height = '0';
            cardP.style.overflow = 'hidden';
          }
          // add mouseenter event to card
          card.addEventListener('mouseenter', () => {
            if (cardP) {
              cardP.style.height = cardPHeight + 'px';
            }
          });
          // add mouseleave event to card
          card.addEventListener('mouseleave', () => {
            if (cardP) {
              cardP.style.height = '0';
            }
          });

        });
        
      }

      // wait until whole page is loaded
      jQuery(document).ready(function($) {
        setTimeout(
          hoverCardsInit(),
          500
        )
      });



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
          });
        }

        // ── Card Carousels (Slick) ──────────────────────────────────────────────
        // Initializes the "Carousel" cards layout (flexible/section_cards.php).
        // Per-instance settings (slidesToShow + responsive breakpoints) are read
        // natively by Slick from each .cards-slick element's data-slick attribute,
        // so a single init call covers every carousel on the page.
        function initCardCarousels() {
          var prevArrow = '<button type="button" class="cards-arrow cards-prev" aria-label="Previous slide">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="41" viewBox="0 0 23 41" fill="none"><path d="M21.123 1.5L2.12129 20.5018L21.123 39.5035" stroke="#062F6E" stroke-width="3" stroke-linecap="round"/></svg></button>';
          var nextArrow = '<button type="button" class="cards-arrow cards-next" aria-label="Next slide">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="41" viewBox="0 0 23 41" fill="none"><path d="M1.5 39.5034L20.5018 20.5017L1.5 1.4999" stroke="#062F6E" stroke-width="3" stroke-linecap="round"/></svg></button>';

          $('.cards-slick').each(function() {
            var $slider = $(this);
            if ($slider.hasClass('slick-initialized')) return;

            $slider.slick({
              infinite: true,
              slidesToShow: 3,    // overridden per-instance by data-slick
              slidesToScroll: 1,
              dots: false,
              arrows: true,
              prevArrow: prevArrow,
              nextArrow: nextArrow,
            });
          });
        }

        initCardCarousels();

        
      
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