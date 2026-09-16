# Estatein theme — development process and choices

This document records how the Estatein WordPress theme was built, why those choices were made, and which plugins this site actually uses. It is the project’s working history, not a marketing page.

**Theme path:** `wp-content/themes/estatein-theme`  
**Local site:** `http://estatein.local`  
**Scope today:** homepage only. Inner Figma pages (About, Properties, Services, Contact, Property Details) are not implemented.

---

## 1. What we set out to do

Build a custom WordPress theme that matches the Estatein Figma file pixel-for-pixel on the homepage, with content editable in WordPress — without a page builder, without ACF Pro, and without inventing a second visual system.

Editors should be able to change copy, images, prices, FAQs, testimonials, and the newsletter form. They should not need to edit PHP or CSS for day-to-day content.

---

## 2. Source of truth

**Figma is the visual source of truth.** Colors, type, spacing, layout, and assets come from the design file. We do not substitute another palette, font pairing, or layout “because it looks nicer.”

| | |
|---|---|
| File | [Estatein community file](https://www.figma.com/design/sB858zfXBBh0WTzfwu7H32/) |
| File key | `sB858zfXBBh0WTzfwu7H32` |
| Figma MCP account | `addina.nuriyanti@gmail.com` |

An older duplicate file (`4n8kGE4VcLbTwpobbyfwXS`) is the same template. Prefer the community file above. Theme `style.css` still lists the older URI; treat the community file as canonical.

Desktop home frame: **`46:304`** (1920×5196). Visual child order in Figma is not reading order. Hero is `121:1772`, not the first child.

| Section | Node ID | Notes |
|---|---|---|
| Header | `60:3125` | 63px announcement + 99px nav |
| Hero | `121:1772` | 814px split + 252px feature row, 20px inset |
| Featured properties | `87:1301` | 1596×1040, x = 162 |
| Testimonials | `75:599` | |
| FAQ | `75:952` | |
| CTA + Footer | `89:4284` | |

Laptop home is `139:6238` (1440, 80px inset, 1280 content). Mobile home is `139:7812` (390, 16px inset).

**Node traps:** `121:1920` is a feature-card title (“Smart Investments…”), not the homepage. `102:9580` is a Property Details paragraph.

### Design tokens (implemented in CSS)

| Token | Value | Use |
|---|---|---|
| Grey/08 | `#141414` | Page background |
| Grey/10 | `#1a1a1a` | Surfaces / cards |
| Grey/15 | `#262626` | Borders |
| Grey/60 | `#999999` | Muted text |
| Purple/60 | `#703bf7` | Accent, primary buttons, Read More |
| Purple/75 | `#a685fa` | Lighter accent where Figma uses it |
| Font | Urbanist 500 / 600 / 700 | Line-height 1.5; hero H1 60px / 1.2 |
| Content width | **1596px** | 162px side inset on 1920. Not 1621px. |

The hero feature bar is **full-bleed with 20px padding**, not constrained to 1596px.

These tokens also live in `.cursor/rules/estatein-figma.mdc` so later work reuses the same frames and colors.

---

## 3. Development process

Work followed a Figma → custom theme → live WordPress loop. Theme files were written in the repo. Live content (pages, menus, posts, ACF values, forms) was operated through WordPress / Novamira, not by writing into the database from theme PHP.

### Step 1 — Map the file, do not guess nodes

1. Confirm the Figma account matches the expected email.
2. Read metadata for the home frame and its children. Visual order ≠ children order.
3. Store file key and node IDs in the project rule.
4. Pull design context **one section at a time** (hero, then featured cards, then footer). Whole-page nodes time out and burn Figma Starter quota.
5. On rate-limit, stop calling Figma. Continue from stored tokens and already-exported assets.

Figma MCP output is a **React/Tailwind-shaped reference**. It is not pasted into the theme. Markup is PHP templates; styles are the theme’s CSS variables and classes.

### Step 2 — Scaffold a custom theme

Path: `wp-content/themes/estatein-theme/`

```
style.css                 Theme header only (WordPress reads this; not the visual stylesheet)
functions.php             require inc/* only — no logic dumped here
inc/theme-setup.php       Supports, menus, image size, settings page
inc/assets.php            Enqueue CSS/JS; bump versions after visual changes
inc/cpt.php               All register_post_type / register_taxonomy
inc/acf-fields.php        Local ACF field groups (acf/init)
inc/helpers.php           Field, image, button, currency wrappers
inc/navigation.php        Park non-home links on "#"
inc/slider.php            Homepage carousel queries and controls
inc/template-functions.php
header.php / footer.php / front-page.php / index.php
page-estatein-settings.php
template-parts/section-*.php
assets/css/theme.css      Tokens + shared UI
assets/css/front-page.css Homepage-only, enqueued on the front page
assets/js/theme.js
assets/icons/             Exported Figma assets only
```

**Why this layout:** WordPress needs `style.css` for the theme name, but dumping thousands of lines of CSS there is unmaintainable. `functions.php` stays a loader so features can be found by filename. CPTs and ACF never register from a random template.

Theme PHP/CSS/JS is edited in the theme directory. Novamira’s file-write ability is sandbox-only and is not used to ship theme code.

### Step 3 — Detect ACF Free and design around it

This site runs **Advanced Custom Fields Free** (not Pro). Free has no Repeater, Gallery, Flexible Content, or Options Pages. Those APIs were never registered — they would silently fail.

Consequences:

- Repeated hero feature cards use numbered fields (`hero_feature_1_*` … `hero_feature_4_*`).
- Global header/footer/announcement/social/newsletter live on a normal **Page** with template `page-estatein-settings.php`, not an ACF Options Page.
- `estatein_field( $key, 'option' )` resolves `'option'` to that settings page ID.
- The settings page **redirects to home** on the front end so it is not a public URL.
- Lists that are real content (properties, FAQs, testimonials) are **custom post types**, not twenty fields on the homepage.

### Step 4 — Content model, then templates

Homepage ACF groups store **section copy** (headings, descriptions, button labels, counts). They do not store the catalog.

| Group | Location | What it holds |
|---|---|---|
| Global Settings | Settings page template | Logo, announcement, contact CTA, newsletter shortcode, copyright, social URLs |
| Homepage — 01 Hero | Front page | Heading, stats, hero image, orbit badge, four feature cards |
| Homepage — 02 Featured Properties | Front page | Section copy + carousel count |
| Homepage — 03 Testimonials | Front page | Section copy + count |
| Homepage — 04 FAQ | Front page | Section copy + count |
| Homepage — 05 Footer CTA | Front page | CTA copy |
| Property — Card | `property` CPT | Price only |
| FAQ — Detail | `faq` CPT | Short answer |
| Testimonial — Detail | `testimonial` CPT | Name, location, rating, photo, quote |

`front-page.php` composes the hero, then includes:

- `template-parts/section-featured-properties.php`
- `template-parts/section-testimonials.php`
- `template-parts/section-faq.php`

Header, announcement, footer CTA, newsletter, and footer columns live in `header.php` / `footer.php`.

### Step 5 — Seed the live site

Pages, menus, CPT posts, taxonomies, featured images, and ACF values were created on the running WordPress install (via admin and Novamira), not hardcoded in templates. Templates always have sensible Figma fallbacks so an empty field still looks like the design.

### Step 6 — Forms

The footer newsletter is a **Ninja Form**, not a custom `wp_mail` handler. The shortcode is stored in ACF (`footer_newsletter_form_shortcode`) and rendered with `estatein_render_ninja_form()`. The theme does not hardcode `[ninja_form id=3]` in PHP. If the shortcode is empty, a non-submitting visual fallback field is shown so the footer still matches Figma.

Ninja Forms Free has no Mailchimp action. Subscriptions stay in Ninja Forms until a mail provider is added.

### Step 7 — Match Figma, then harden

Desktop parity against `46:304` came first (spacing, type, 1596px container, feature bar inset). Laptop/mobile frames followed in CSS. Later passes added:

- Keyboard and landmark accessibility (focus rings, form labels, slider names, `prefers-reduced-motion`)
- Hero and card entrance motion, plus light parallax on the hero image/orbit and footer CTA decoration
- Property CPT as a real WordPress loop (see below)

A skip-to-content link was tried and **removed** so the first Tab stop stays the announcement bar, matching the intended header order.

---

## 4. Theme development choices

### Custom theme, not a page builder

Gutenberg full-site editing, Elementor, and similar builders were not used. The Figma homepage is a fixed composition. A custom `front-page.php` plus ACF is simpler to keep on-spec than fighting a builder’s grid.

Bootstrap 5.3.3 is used for the grid and a few utilities (`container-xl`, flex order, nav collapse). Visual design is **custom CSS** (`theme.css` / `front-page.css`), not Bootstrap’s default look and not Tailwind. Figma MCP’s Tailwind-shaped snippets were translated into those stylesheets.

### Homepage only

Inner Figma pages exist in the file but were deliberately left unimplemented. Matching About / Properties / Services / Contact / Property Details would be a second project.

That choice shows up in code:

- No inner page templates.
- Primary and footer menus still show Figma labels (About, Properties, Services, …) so the header matches the design.
- `estatein_nav_url()` / `estatein_park_menu_links()` send every destination **other than Home** to `#`.
- The settings page and property singles redirect home (302).

When inner pages are built, parking can be lifted per destination instead of rewriting the header.

### Properties are posts, not ACF IDs on the homepage

Early on, homepage featured listings were selected by ACF post IDs. That was replaced with a normal WordPress query:

- CPT `property` supports **title, editor, thumbnail, excerpt**.
- ACF on a property is **price only**.
- Hierarchical taxonomies (checkbox UI): `featured_property`, `bedroom`, `bathroom`, `property_type`.
- Homepage carousel: `WP_Query` with `tax_query` for term slug `featured` on `featured_property`.
- Cards use `the_permalink()`, trimmed excerpt (~24 words), and a **Read More** link styled to Figma (Urbanist 18px / 500, `#703bf7`, no underline).

**Why taxonomies for bedrooms/baths/type:** editors get native checkboxes and admin columns. Those values are labels on the card, not public archive URLs (`public` is false, `rewrite` is false).

**Why property permalinks exist if singles redirect:** cards need a real `get_permalink()` for Read More. FAQ and Testimonial stay admin-only (`public` false) because they have no card link in Figma.

Unfeatured properties can exist in the catalog without appearing on the homepage carousel.

### Footer menu structure

The footer walker treats **top-level items as column headings** and **children as links**. That matches the Figma footer (Home / About Us / Properties / Services as headings). Menu “Estatein Footer” is the assigned footer location; “Estatein Primary” is the header.

### Front-end libraries

| Library | Why |
|---|---|
| Bootstrap 5.3.3 (CDN) | Layout/grid and off-canvas-style mobile nav without inventing a grid |
| Slick Carousel 1.8.1 (CDN, front page only) | Featured / testimonials / FAQ carousels with custom Figma arrows and “01 of 09” counters |
| Urbanist from Google Fonts | Matches Figma; weights 300, 500, 600, 700 |
| hamburgers.css (theme file) | Mobile menu icon |

Slick and its CSS load only on `is_front_page()`. Version query strings in `inc/assets.php` are bumped when CSS/JS change so Local’s cache does not serve stale files.

### Accessibility and motion (after visual parity)

Figma is silent on keyboard behavior. Those decisions came from standard UX practice, not a second brand:

- Visible focus rings on dark surfaces
- Visible (or visually-hidden) labels on the newsletter field
- Named slider previous/next buttons and live slide counters
- `prefers-reduced-motion` disables parallax and entrance animation
- Announcement bar is not dismissed into `sessionStorage` (it must remain a Tab stop)

Motion is CSS/JS in the theme (staggered `.hero-reveal` / `.card-reveal`, opposite-direction hero parallax). It is decorative; content does not depend on it.

---

## 5. Plugins

Split into **what the theme needs**, **what this local site uses for development**, and **what is installed but not part of the theme contract**.

### Required for the theme to work as designed

| Plugin | Version on this site | Role |
|---|---|---|
| **Advanced Custom Fields** | 6.8.8 | Structured fields for homepage sections, settings page, property price, FAQ answers, testimonials. Field groups are registered in `inc/acf-fields.php`, not only in the ACF UI. **Free** on purpose: no Repeaters, no Options Pages. |
| **Ninja Forms** | 3.15.0 | Footer newsletter. Shortcode stored in Estatein Settings. Theme CSS restyles the form to the dark Figma footer; plugin files are not edited. |

### Required for this development workflow

| Plugin | Version on this site | Role |
|---|---|---|
| **Novamira** | 1.11.4 | Live WordPress control plane used from Cursor (abilities, content seed, Ninja Forms tools). **Do not deactivate, update, or remove** on this Local site. It is not a front-end dependency for visitors, but it is how the site was operated during the build. |

### Installed on this Local site, not theme dependencies

These are active (or present) in the WordPress install. The theme does not call them.

| Plugin | Version | Notes |
|---|---|---|
| **Yoast SEO** | 28.3 | General SEO. Homepage-only site; no custom Yoast integrations in the theme. |
| **ACF Content Analysis for Yoast SEO** | 3.2 | Lets Yoast see ACF field text. Useful later; not required to render the homepage. |
| **LiteSpeed Cache** | 7.9 | Caching/optimization typical of Local / LiteSpeed stacks. Can serve stale CSS if `inc/assets.php` versions are not bumped. |
| **Backup Migration** | 2.1.7 | Site backup. Unrelated to Figma matching. |
| **Better Search Replace** | 1.4.11 | **Inactive.** URL rewrite helper for migrations; not used by the theme. |

No Mailchimp, no ACF Pro, no slider plugin (Slick is a theme enqueue), no form plugin besides Ninja Forms.

---

## 6. How to work on the site

1. Activate **Estatein**, **ACF**, and (for the newsletter) **Ninja Forms**.
2. Settings → Reading: static front page = Home (`homepage`).
3. Edit **Estatein Settings** for announcement, newsletter shortcode, copyright, social links.
4. Edit the Home page ACF groups for hero / section copy.
5. Add Properties; assign Featured Property term `featured` for homepage cards. Set featured image, excerpt, price, bedroom/bathroom/type terms.
6. Add FAQ and Testimonial posts; homepage counts come from the Home page number fields.
7. Assign menus **Estatein Primary** and **Estatein Footer**.
8. After CSS/JS edits, bump the version in `inc/assets.php`.

**Do not** invent tokens or layouts that disagree with Figma. **Do not** register ACF Pro field types while the site is on ACF Free.

---

## 7. What is intentionally unfinished

- About, Properties listing, Property Details, Services, and Contact templates
- Public property single views (permalinks exist; `template_redirect` sends them home)
- Mail provider for the newsletter
- ACF Pro (repeaters / options pages) — not needed for the current field set

Those are product decisions for a later phase, not missing pieces of the homepage build.
