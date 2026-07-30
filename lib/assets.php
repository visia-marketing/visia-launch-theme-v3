<?php

namespace Roots\Sage\Assets;

/**
 * Public URL for a theme asset
 *
 * Paths are given relative to the theme's /assets/ directory, with or without
 * a leading slash.
 *
 * Example:
 * - Input:  asset_path('dist/styles/main.min.css')
 * - Output: http://example.com/wp-content/themes/theme/assets/dist/styles/main.min.css
 *
 * @param string $filename Path to the asset, relative to /assets/
 * @return string Full URL to the asset
 */
function asset_path($filename) {
  return get_template_directory_uri() . '/assets/' . ltrim($filename, '/');
}

/**
 * Cache-busting version string for a theme asset
 *
 * Returns the file's last modified time, which changes only when the compiled
 * file actually changes. rsync -a preserves mtimes, so the same committed bytes
 * produce the same version on local, staging and production.
 *
 * Returns null for a missing file so that wp_enqueue_* omits the ?ver= parameter
 * entirely rather than emitting a broken one.
 *
 * @param string $filename Path to the asset, relative to /assets/
 * @return int|null Unix timestamp, or null if the file does not exist
 */
function asset_version($filename) {
  $path = get_template_directory() . '/assets/' . ltrim($filename, '/');

  return file_exists($path) ? filemtime($path) : null;
}
