ESTATEIN WORDPRESS THEME
========================

Homepage-only theme from the Estatein Figma home frames. Inner pages are not implemented.

REQUIREMENTS
- WordPress 6+
- PHP 7.4+
- Advanced Custom Fields (free)
- Ninja Forms (optional; footer newsletter)

SETUP
1. Activate Estatein.
2. Install/activate ACF free.
3. Assign the Home page as the static front page under Settings > Reading.
4. Create the Primary menu (About / Properties / Services stay as "#" placeholders).
5. Edit Estatein Settings for header, announcement, footer, newsletter, and social links.
6. Add Property, FAQ, and Testimonial posts. The homepage carousels pull from those CPTs.

CONTENT MODEL
- Property, FAQ, and Testimonial are admin-only. They have no public single or archive URLs.
- Homepage ACF groups store section copy and which property IDs to feature.
- Property cards use featured image plus price, excerpt, bedrooms, bathrooms, and building label.

ACF FREE NOTE
This theme does not use Repeater, Gallery, Flexible Content, or Options Pages. Repeated cards use numbered fields. Global settings live on the Estatein Settings page.

DESIGN TOKENS
- Font: Urbanist
- Background: #141414
- Surface: #1A1A1A
- Border: #262626
- Muted: #999999
- Accent: #703BF7
- Content width: 1596px
