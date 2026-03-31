# Font Awesome 6 Standardization Guide

## Overview
This theme uses **Font Awesome 6.5.2** loaded via CDN for consistent icon management across all pages and components.

**CDN**: `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css`

## Font Awesome 6 Syntax

Font Awesome 6 uses a different class naming convention than FA 5. Always use these updated classes:

### Available Icon Styles (Free Version)

| Style | Class | Weight | Usage |
|-------|-------|--------|-------|
| Solid | `fa-solid` | 900 (bold) | Default - use for most icons |
| Regular | `fa-regular` | 400 (light) | Alternative lighter weight |

### Icon Class Format
```html
<!-- Syntax: <i class="[style] fa-[icon-name]"></i> -->
<i class="fa-solid fa-arrow-right"></i>
<i class="fa-regular fa-search"></i>
<i class="fa-solid fa-chevron-right"></i>
```

## ❌ DO NOT Use (Font Awesome 5 Syntax)
```html
<!-- INCORRECT - These are FA 5 classes and won't work properly -->
<i class="fas fa-arrow-right"></i>     ✗ Use fa-solid instead
<i class="far fa-search"></i>          ✗ Use fa-regular instead
<i class="fal fa-icon"></i>            ✗ Light requires FA Pro
<i class="fab fa-facebook"></i>        ✗ Brands work but use fa-brands for clarity
```

## Usage Examples

### In HTML/PHP Templates
```php
<!-- Search icon (regular weight) -->
<button class="button"><i class="fa-regular fa-search"></i></button>

<!-- Arrow icon (solid weight) -->
<a href="#" class="read-more">
  Read More <i class="fa-solid fa-arrow-right"></i>
</a>

<!-- Tab icon (solid weight) -->
<div class="accordion-header">
  <i class="fa-solid fa-chevron-right"></i> Accordion Title
</div>
```

### In CSS/SCSS (::before and ::after pseudo-elements)
```scss
.button::before {
  font-family: 'Font Awesome 6 Pro', 'Font Awesome 6 Free';
  content: '\f061';  // Arrow right Unicode value
  font-weight: 900;  // fa-solid weight
  margin-right: 0.5rem;
}
```

## Icon Searching
Find the correct icon name and Unicode codepoint at: https://fontawesome.com/icons

- Search by name (e.g., "arrow right")
- Look for the icon name under the search result
- View the HTML snippet to get the correct class syntax
- Find the Unicode codepoint if using CSS content property

## Common Icon Examples

| Purpose | Code | Icon |
|---------|------|------|
| Search | `<i class="fa-solid fa-magnifying-glass"></i>` | 🔍 |
| Menu | `<i class="fa-solid fa-bars"></i>` | ☰ |
| Close | `<i class="fa-solid fa-xmark"></i>` | ✕ |
| Left Arrow | `<i class="fa-solid fa-chevron-left"></i>` | ← |
| Right Arrow | `<i class="fa-solid fa-chevron-right"></i>` | → |
| Download | `<i class="fa-solid fa-download"></i>` | ⬇️ |
| External Link | `<i class="fa-solid fa-arrow-up-right-from-square"></i>` | ⤴ |
| Plus | `<i class="fa-solid fa-plus"></i>` | ➕ |
| Check/Checkmark | `<i class="fa-solid fa-check"></i>` | ✓ |

## Pro Tips

1. **Consistency**: Use `fa-solid` for most icons unless you specifically need a lighter weight
2. **Accessibility**: Add `aria-hidden="true"` for decorative icons
3. **Performance**: Font Awesome is served via CDN, icons load with the stylesheet
4. **Fallback**: If an icon doesn't render, check the icon name is correct on fontawesome.com
5. **Sizing**: Use Font Awesome sizing utilities: `fa-lg`, `fa-2x`, `fa-3x`, etc.

## Troubleshooting

### Icons not showing?
- Check browser console for CSS loading errors
- Verify icon class name is correct (use `fa-*` naming, not old `fas fa-*`)
- Ensure you're using FA 6 class syntax, not FA 5

### Icon appears as empty square?
- The icon name may not exist in Font Awesome Free
- Search fontawesome.com to confirm icon availability
- Some icons require Font Awesome Pro (paid)

### Performance issues?
- CDN is already optimized and cached by browsers
- Consider lazy-loading if displaying many icons off-screen
- CSS is minified and gzipped by default
