ESTATEIN WORDPRESS THEME
========================

A responsive Bootstrap 5 WordPress theme inspired by the provided Estatein Figma home page.

REQUIREMENTS
- WordPress 6+
- PHP 7.4+
- Advanced Custom Fields (free)
- Ninja Forms (optional; install separately)

INSTALL
1. Zip the estatein-theme folder.
2. WordPress Admin > Appearance > Themes > Add New > Upload Theme.
3. Activate Estatein.
4. Install/activate ACF free.
5. Install/activate Ninja Forms if forms are needed.
6. Create a page and assign "Front page" as the site's static homepage under Settings > Reading.
7. Create Primary/Footer menus under Appearance > Menus.
8. Go to Estatein Settings and fill global header/footer fields.
9. Add Properties, FAQs, Testimonials, Team Members and Clients.
10. For the homepage, edit the front page. ACF groups are intentionally named by section.
11. If the ACF field groups do not appear immediately after activation, visit Custom Fields once and refresh the page; the theme ships both PHP local-field registration and ACF Local JSON definitions.

CONTENT MODEL
- Property CPT: archive /properties/ and single /properties/{slug}/
- FAQ CPT: archive /faqs/ and single /faqs/{slug}/
- Testimonial CPT: archive /testimonials/ and single /testimonials/{slug}/
- Team Member CPT: archive /our-team/ and single /our-team/{slug}/
- Client CPT: archive /clients/ and single /clients/{slug}/

PROPERTY TAXONOMIES
- Location
- Property Type
- Property Status
- Pricing Range
- Property Size Range
- Build Year

PROPERTY FILTERS
The property archive filters by the six taxonomies above and additionally supports min/max numeric price inputs. Taxonomy filters are deliberately separate from numeric ACF fields so editors can create clean filter buckets while preserving exact values on each property.

ACF FREE NOTE
This theme does NOT use ACF Pro-only Repeater, Gallery, Flexible Content, or Options Pro features. Galleries are represented by six individual image fields, and repeated cards are represented by numbered fields/IDs. This keeps the theme compatible with ACF Free.

FORMS
Ninja Forms is never bundled. Enter the Ninja Forms shortcode in the relevant ACF field, for example:
[form id="1"]

The theme outputs the shortcode with do_shortcode() and styles common Ninja Forms controls to match the dark Figma design.

BOOTSTRAP
Bootstrap 5.3.3 CSS/JS is enqueued from jsDelivr, with theme CSS layered on top for the Figma-specific visual system.

DESIGN TOKENS
- Font: Urbanist
- 60/600/120% hero H1
- 48/600/150% section H2
- 40/700/150% large stats
- 24/600/150% cards
- 20/500-600/150% secondary headings
- 18/500/150% body/navigation/buttons
- Background: #141414
- Surface: #191919
- Muted: #999999
- Accent: #703BF7

FILES
front-page.php
page-about-us.php
page-contact.php
archive-property.php / single-property.php
archive-faq.php / single-faq.php
archive-testimonial.php / single-testimonial.php
archive-team_member.php / single-team_member.php
archive-client.php / single-client.php
header.php / footer.php
inc/cpt.php
inc/acf-fields.php
inc/template-functions.php
assets/css/theme.css
assets/js/theme.js
