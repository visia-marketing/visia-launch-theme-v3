/**
 * Customizer live-preview bindings
 *
 * Loaded only inside the Customizer preview frame (see lib/customizer.php).
 * Settings with 'postMessage' transport update the page here without a full
 * refresh. Add a wp.customize('setting_id', ...) block per live setting.
 */
(function($) {
  // Site title — rendered only as the header/footer logo alt text in this theme
  wp.customize('blogname', function(value) {
    value.bind(function(to) {
      $('.main-logo img, .footer-logo img').attr('alt', to);
    });
  });
})(jQuery);
