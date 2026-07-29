# Naming Standardization — visia-launch-theme-v3

## Context

This Sage 8 theme accumulated naming from multiple sources: stock Sage/Roots boilerplate, tutorial-copied code (`my_`, `wpb_` prefixes), unprefixed global functions that risk colliding with plugins (notably `acf_field()`, which shadows the ACF plugin's naming space), and inconsistent text domains left over from an incomplete rename pass (git `5baf9df`). The goal is one consistent theme identity, dead code removed, and the Customizer preview pipeline made functional.

**Decisions made with the user:**
- **Keep** `Roots\Sage\*` PHP namespaces, `sage/` hook tags & asset handles, `SageWrapping`, and **everything with "roots" in the name** (`Roots_Nav_Walker`, `roots_nav_menu_css_class`, `roots_nav_menu_args`, `'roots/wp_nav_menu_item'`).
- **Keep** `get_flexible_content()` name as-is.
- **Keep** `g1-` and `fc-` CSS classes (no front-end churn).
- **Keep** lib/customizer.php and lib/shortcodes.php — and **make the customizer functional** (webpack entry + fixed enqueue).
- **Rename** remaining boilerplate/unprefixed global functions to the `visia_` prefix.
- **Delete** dead/unused functions.
- **Standardize** the text domain to `visia_marketing`.

## 1. Rename map

Collision-checked against existing `visia_*` names — all clear.

| Old | New | Definition | Registration/call sites |
|---|---|---|---|
| `my_acf_admin_head` | `visia_acf_admin_head` | lib/acf.php:16 | string callback :15 |
| `create_anchor` | `visia_create_anchor` | lib/acf.php:41 | call site lib/acf.php:76 |
| `add_security_headers` | `visia_add_security_headers` | lib/misc.php:21 | string callback :20 |
| `add_nonce_to_scripts` | `visia_add_nonce_to_scripts` | lib/misc.php:40 | string callback :39 |
| `my_theme_register_required_plugins` | `visia_register_required_plugins` | lib/misc.php:53 | string callback :137 |
| `my_mce_color_options` | `visia_mce_color_options` | lib/tinymce.php:11 | `__NAMESPACE__` string :24 — function-name portion must change |
| `wpb_mce_buttons_2` | `visia_mce_buttons_2` | lib/tinymce.php:32 | `__NAMESPACE__` string :36 |
| `my_mce_before_init_insert_formats` | `visia_mce_before_init_insert_formats` | lib/tinymce.php:44 | `__NAMESPACE__` string :59 |

**No changes**: all `roots_*` / `Roots_Nav_Walker` / `roots/wp_nav_menu_item`, `get_flexible_content`, `visia_top_nav_link_classes`, the six `visia_*` functions in lib/woocommerce.php, `visia_image_sizes_set` option key, `'tgmpa'` text domain in lib/class-tgm-plugin-activation.php (3rd-party), lib/shortcodes.php (kept as-is for future shortcodes).

## 2. Dead-code removal (verified zero references)

| Item | Location | Evidence |
|---|---|---|
| `acf_attachment_img()`, `acf_field()`, `acf_button()` + the "ACF Helper Functions" comment block | lib/acf.php:174–238 | grep across all theme PHP: definitions only, zero call sites |
| `sidebar_path()` | lib/wrapper.php:16–18 | never called (base.php:88 uses only `template_path()`); points at nonexistent `templates/sidebar.php` |
| Unused `use Roots\Sage\Setup;` | lib/tinymce.php:5 | import never referenced (file already being edited for renames) |

## 3. Make lib/customizer.php functional

Currently broken: it enqueues `Assets\asset_path('scripts/customizer.js')` → `assets/scripts/customizer.js`, which doesn't exist; the source lives at `assets/src/scripts/customizer.js` and isn't a webpack entry. Also, its JS binds `blogname` to a `.brand` element that doesn't exist in this theme's markup — the site title only renders as logo `alt` text ([partials/site-header.php:95](partials/site-header.php#L95), [partials/site-footer.php:17](partials/site-footer.php#L17)).

1. **webpack.config.js** — add a second entry:
   ```js
   entry: {
       main: ['./assets/src/scripts/main.js'],
       customizer: ['./assets/src/scripts/customizer.js']
   }
   ```
   Existing `output.filename: 'assets/dist/scripts/[name].min.js'` already handles multi-entry naming → produces `assets/dist/scripts/customizer.min.js`. (`MiniCssExtractPlugin` emits nothing for a JS-only entry.)
2. **lib/customizer.php:19** — fix the path to match the setup.php convention:
   `wp_enqueue_script('sage/customizer', Assets\asset_path('dist/scripts/customizer.min.js'), ['customize-preview', 'jquery'], null, true);` (handle stays `sage/customizer` per keep-sage decision).
3. **assets/src/scripts/customizer.js** — update the `blogname` binding to target markup that actually exists: update the header/footer logo `alt` attributes (`.site-header a img` / logo selectors from the partials) instead of `$('.brand').text(to)`. This makes the existing `postMessage` transport in `customize_register()` genuinely live. Structure the file so future settings can add bindings alongside.
4. Run `npm run build` and verify `assets/dist/scripts/customizer.min.js` is emitted.

## 4. Text domain → `visia_marketing` (~26 sites)

- lib/setup.php:36 `load_theme_textdomain('visia_marketing', ...)` (note: `/lang` dir doesn't exist; no `.po/.mo` to regenerate — change is behaviorally inert)
- lib/setup.php:58–66 (incl. one commented line), :145, :158, :171, :184
- functions.php:47 · 404.php:10 · index.php:16 · lib/titles.php:13,18,20 · lib/extras.php:35 · partials/content-single.php (commented line) · flexible/section_product_grid.php:49
- lib/misc.php:129,130 — stray `'visia'` → `'visia_marketing'`
- **style.css**: add `Text Domain: visia_marketing` header (missing entirely today)

## 5. Folded-in cleanups (same identity/staleness theme)

1. **webpack.config.js:17** — `publicPath` `'/wp-content/themes/visia-launch-theme-uikit/'` → `'/wp-content/themes/visia-launch-theme-v3/'`. Functional bug (asset URLs 404). Same file as the customizer entry change.
2. **Stale `templates/` paths** — the `templates/` dir doesn't exist (Sage's dir became `partials/`), so these `get_template_part()` calls silently render nothing:
   - page.php:6 and 404.php:3 → `partials/page-header`
   - single.php:6 → `partials/content-single`; commented lines single.php:2,:10 → `partials/page-breadcrumbs`, `partials/post-sidebar`
   - **template-flexible.php:12 → delete the line** (not repoint): `get_flexible_content()` already renders `partials/page-header` at lib/acf.php:71 — repointing would double-render the header
   - **index.php:23** (`templates/content`) — no partials equivalent exists; repointing to content-single would visibly change the blog archive. Update path to `partials/content` with a `// TODO: partial does not exist` comment so it stops looking intentional.
3. **style.css Theme Name** — `Sunflower: Visia Launch Theme 3.0` → `Visia Launch Theme 3.0` ("Sunflower" is a foreign identity token; the directory slug is the real identity so this is safe).

**Deferred (flagged, not in this change):** ruleset.xml "Roots" name; README Nexcess-vs-Kinsta docs mismatch; package.json name/version drift.

## 6. Execution order (per-file passes)

Each function's definition and its string-callback registration live in the same file — per-file passes avoid half-renamed states.

- **Pass A**: lib/acf.php — rename `my_acf_admin_head` + `create_anchor` (def + call site :76); delete lines 174–238 (three dead helpers + comment block)
- **Pass B**: lib/misc.php — 3 renames
- **Pass C**: lib/tinymce.php — 3 renames + drop unused import
- **Pass D**: lib/wrapper.php — delete `sidebar_path()`
- **Pass E**: customizer pipeline — webpack.config.js (entry + publicPath), lib/customizer.php enqueue path, customizer.js selector; `npm run build`
- **Pass F**: text domain sweep (files in §4) + style.css headers
- **Pass G**: stale template paths — page.php, 404.php, single.php, index.php, template-flexible.php

After each pass: `php -l` on changed files, then zero-hit greps:
```
grep -rnE "my_acf_admin_head|\bcreate_anchor\(|my_theme_register|my_mce_|wpb_mce_|add_security_headers|add_nonce_to_scripts" --include='*.php' . | grep -v visia_
grep -rnE "acf_attachment_img|\bacf_field\(|\bacf_button\(|sidebar_path" --include='*.php' .
grep -rn "visia_starter_theme" . ; grep -rn "'visia'" lib/
grep -rn "get_template_part('templates/" --include='*.php' .
```
Final sweep: `grep -rnE "^function [a-z]" lib/*.php | grep -vE "visia_|roots_"` — remaining unprefixed defs should only be inside namespaced files.

## 7. Verification (DevKinsta site)

1. `php -l` all changed files: lib/acf.php, lib/misc.php, lib/tinymce.php, lib/wrapper.php, lib/customizer.php, lib/setup.php, lib/titles.php, lib/extras.php, functions.php, 404.php, index.php, page.php, single.php, template-flexible.php, partials/content-single.php, flexible/section_product_grid.php.
2. Load front page + `/wp-admin` with `WP_DEBUG` — a missed string callback surfaces as a fatal; check `wp-content/debug.log`.
3. **Customizer**: Appearance → Customize → Site Identity → edit Site Title — preview updates the logo alt attributes live without a full refresh (proves `customizer.min.js` builds, enqueues, and binds). Check browser console for 404s on the script.
4. **Flexible template page**: `fc-wrapper` renders with anchor IDs (`visia_create_anchor`); page header appears **once**, not twice.
5. **Regular page + 404**: page header now renders (it was silently missing before the `templates/` fix) — visible change, expected.
6. **ACF admin**: edit a flexible page — layouts auto-collapse (`visia_acf_admin_head`); accent color save still writes accent-color.css.
7. **TGMPA**: admin notice for required plugins appears (`visia_register_required_plugins`).
8. **Security headers**: `curl -sI http://<site>/ | grep -iE 'x-frame|content-security|referrer'`; view source for script nonces (`visia_add_nonce_to_scripts`).
9. **TinyMCE/WYSIWYG**: custom color palette, second button row, Formats dropdown entries present.
10. **Nav menus + WooCommerce product page**: unchanged behavior (roots walker and `visia_*` WC functions untouched).
11. After `npm run build`: dist asset URLs reference `visia-launch-theme-v3`; both `main.min.js` and `customizer.min.js` present in `assets/dist/scripts/`.
