# Translations

`estatein_setup()` calls `load_theme_textdomain( 'estatein', get_template_directory() . '/languages' )`,
so `.mo` files dropped in this folder are picked up automatically.

Interface strings ("Read More", "Price", slider labels, admin field labels) are
translatable. The Figma fallback copy in `inc/content-defaults.php` is not — that
is the client's brand wording, and it is editable in WordPress rather than through
a translation file.

Generate a fresh template with WP-CLI:

```
wp i18n make-pot . languages/estatein.pot --domain=estatein
```
