# LABAS LIETAS Green Storefront 2.0

Theme folder: `wp-bbtheme-child-woo-tech-shop-labaslietas`

A modern green/navy WooCommerce storefront for Labas Lietas, Smiltene. The visual direction uses a large catalogue/search layout and dense product discovery common to Latvian hardware/e-commerce sites, while the branding, artwork, demo products and code are original.

## Install

1. Keep the parent theme `wp-bbtheme` installed.
2. WordPress → Appearance → Themes → Add New → Upload Theme.
3. Upload `wp-bbtheme-child-woo-tech-shop-labaslietas-v2.0.0.zip` and activate/replace the existing Labas Lietas child theme.
4. The theme keeps existing WooCommerce orders, products, payment configuration and delivery configuration.
5. Open Appearance → **LABAS LIETAS Demo** to install/refresh or remove the bundled demo catalogue.

## What changed

- Green + navy Labas Lietas identity and bundled SVG logo.
- New desktop/mobile header, large WooCommerce search and catalogue menu.
- Homepage hero, category cards, benefit bar, promotions, featured/sale/new product grids.
- Cleaner WooCommerce product cards and green buttons.
- Footer with information/customer/contact/delivery/payment sections and Facebook link.
- KurPirkt.lv and Salidzini.lv cooperation badges in the footer.
- Theme-native XML feeds:
  - `/kurpirkt.xml`
  - `/salidzini.xml`
- Original demo catalogue with tools, workshop equipment, garden machinery and trimmer accessories.

## Demo products

The bundled demo catalogue is original; it does not copy product photos/descriptions from Tava-Tehnika, VEX, Ksenukai or other shops. Demo SKUs start with `LL-DEMO-` and can be removed from Appearance → LABAS LIETAS Demo.

Demo products are intentionally excluded from KurPirkt.lv and Salidzini.lv exports until their real stock, pricing, manufacturer/model details and product specifications are verified. On a real product, edit Product data → the comparison-feed fields and do not select "Izslēgt no cenu salīdzināšanas".

## KurPirkt.lv / Salidzini.lv

The theme provides the technical XML endpoints and footer banners, but registration with the comparison portals is still a merchant/account step. Use the real Labas Lietas company details, legal pages and contact details when applying.

## Existing commerce features retained

- Latvia-only checkout.
- Free local pickup in Smiltene.
- Omniva / Unisend / Latvijas Pasts parcel delivery.
- Courier and oversized-order logic from the existing project.
- Cash/local-pickup and bank transfer support.
- EveryPay / Swedbank compatibility when the payment gateway plugin is installed and configured.
- Native wishlist and product comparison.

## Admin settings

Appearance → **LABAS LIETAS Theme Settings** contains logo/contact/social/theme/feed settings.

Appearance → **LABAS LIETAS Demo** contains demo-catalog actions and the current public XML feed URLs.


## 2.1.0
- Fixed WooCommerce archive/category product grid collapse.
- Archive page now uses the green storefront cards.
- Empty sidebar column is no longer rendered.
- Header search/cart alignment tightened.
- Demo product seeding made safe against duplicate SKU fatals.


## 2.2.0
- Added proper theme screenshot for Appearance → Themes.
- Added bundled favicon/site icon fallback assets.
- Refined header/footer/homepage alignment to better match the approved preview.
- Hid stray Business demo admin notices from the storefront workflow.
- Front-end language switch now shows only Latvian and English (when Polylang switcher is present).


## 2.3.0
- Homepage/header/footer refined to follow the approved Ksenukai-style preview more closely.
- Added category dropdown search, popular keyword links and expanded navigation.
- Replaced theme screenshot with the approved storefront-style preview.
- Bundled preview-based hero card assets and more realistic demo product images for several key products.


## 2.3.1
- Fixed footer alignment, spacing and column positions.
- Moved the small comparison logos into a cleaner bottom comparison row.
- Improved footer list/menu positioning and contact block alignment.


## 2.3.2 hotfix
- Disabled automatic demo catalogue seeding on admin_init.
- Demo product images no longer call expensive Imagick thumbnail generation during import.
- Prevents 30-second PHP timeout / wp-admin fatal errors on shared hosting.


## 2.3.3
- Removes extra Polylang languages on first admin load, keeping Latvian as default and English second.
- Lightweight demo product repair without Imagick/thumbnail generation.
- Fixes duplicated hero text and side-card overlays.
- Adds a final stable width/grid layer for homepage and WooCommerce category archives.
- Demo products use bundled theme imagery directly to avoid broken media thumbnails.


## 2.3.4 SAFE SYNC
- Starter Setup is now recognised as a WooCommerce Labas Lietas project, not Business/legacy.
- Parent Business/Woo demo importer is bypassed, preventing Imagick media generation.
- Starter Setup syncs only Latvian + English and makes Latvian the default.
- Generic Woo starter products/categories are removed when they are explicitly marked as generated demo content.
- Labas Lietas demo products are synced without WordPress attachments; cards use bundled theme images by SKU.
- Homepage and product-category grids use a final responsive layout lock to prevent narrow/one-column desktop breakage.


## 2.3.5
- Polylang hard-sync: Latvian default + English only.
- Assigns the current storefront, WooCommerce core pages, catalogue and product taxonomies to Latvian.
- Removes stale starter languages and hides stale language columns during cleanup.
- Yoast SEO integration: no duplicate theme meta descriptions, Yoast breadcrumbs, product/category description fallbacks, and product social-image fallbacks.
- Starter Setup now runs the stronger LV/EN language sync.


## 2.3.6
- Replaces the parent Starter Setup page with a Labas Lietas-owned safe sync.
- Removes generated DEMO-BUSINESS products and empty generic Woo demo categories.
- Forces WooCommerce store visibility live (disables Store coming soon).
- Keeps only Latvian + English through the safe sync path and prevents the parent 14-language setup from running.
- Excludes parent demo products from frontend queries even before cleanup finishes.
- Uses image tags with fixed aspect ratios for the approved homepage hero/promo imagery, preventing crop/alignment breakage.
- No Imagick or attachment thumbnail generation is used by the repair.

## 3.0.0 CLEAN REBUILD
- Replaced the accumulated 648 KB/8k-line presentation stylesheet with a clean 26 KB storefront stylesheet to remove conflicting legacy layout rules.
- Rebuilt header/home/footer/archive responsive layout around the approved Labas Lietas preview.
- Replaced the bundled logo with the green gift-box Labas Lietas identity and reset stale custom-logo settings during repair.
- Hero now uses a visual-only mower asset; no screenshot text is baked into the hero background.
- Product/category grids use explicit CSS Grid widths and no Bootstrap column-width dependency.
- Parent Business demo products are blocked and removed on repair; generic demo categories are removed once empty.
- Polylang database state is trimmed to LV + EN, Latvian default, active-theme menu mappings only.
- Starter Setup is child-owned and database-only. It does not create media thumbnails or use Imagick.


## 3.0.3
- Forced the approved Labas Lietas logo from the accepted preview, ignoring stale imported logo IDs.
- Rebuilt header proportions to match the accepted desktop preview: larger logo, full search, actions and top contact row.
- Footer columns, contact details and comparison badges aligned consistently.
- Restored six-card desktop product rows and kept safe WhatsApp icon sizing without hiding page ancestors.


## 3.0.4
- Rebuilt desktop header alignment: clean category selector chevron, green search button, all four account actions in one row.
- Moved KurPirkt.lv / Salidzini.lv comparison badges into the main footer grid.
- Added a floating scroll-to-top button.
- Replaced malformed third-party WhatsApp output with a clean theme WhatsApp button.
- Unified homepage, category archive and generic product grids using the same responsive card sizing system.


## 3.0.6 Final storefront polish
- Header search selector/button and AJAX results redesigned with product images, category, stock and price.
- All Akcijas links enforce WooCommerce sale-product IDs only.
- Footer price-comparison area now links to KurPirkt.lv, Salidzini.lv and both XML product feeds.
- My Quote floating button moved to the left-bottom corner; WhatsApp and scroll-to-top remain on the right.
- Added safe cleanup for malformed third-party WhatsApp output without touching body/html.
- Rebuilt single-product layout and gallery presentation.
- Standardized product-category grids to four equal columns on desktop.
- Added managed Latvian and English About us + Delivery & payment pages and Polylang translation links.
- Styled My Account login/register as two equal desktop columns and standardized Cart, Checkout and Quote widths.


## 3.0.6 edge-gap and developer-credit fix
- Removes the intentional 42px white footer margin.
- Removes stale WordPress admin-bar top offset only when the admin bar is not actually visible.
- Hides only malformed third-party WhatsApp wrappers injected after the footer so they cannot create a white page tail.
- Adds a footer developer credit linking to https://digitalpulse.click/.


## 3.0.8 header/mobile/template-part repair
- Removes empty `<p>`/`<br>` spacer markup from Header/Footer block template parts.
- Uses direct shortcode templates to avoid nested `header.wp-block-template-part` / `footer.wp-block-template-part` wrappers.
- Adds a responsive mobile navigation drawer and compact mobile header/search/actions layout.
- Resets Gutenberg block-gap margins around theme header/footer while preserving wp-admin bar spacing.
- Keeps desktop header/footer and commerce layout intact.


## 3.0.8 header/mobile gap hardening
- Prints the top-gap reset after WordPress admin-bar bump CSS.
- Removes only true empty BR/P spacer nodes around theme header/footer.
- Compacts desktop header whitespace.
- Mobile account actions stay in one four-item row, including <=430px.
- Mobile category/menu bar is an even 50/50 row with a compact single-row search.


## 3.0.9 English storefront + isolated mobile header
- `/en/` now renders the Labas Lietas storefront instead of the parent Journal/blog screen.
- Creates/links an English front-page translation to the Latvian static homepage when Polylang is active.
- English homepage/header/footer copy and demo-product labels are localized at render time.
- Replaces the inherited mobile header with an isolated mobile-only shell: compact logo, one-row search, four equal shop actions, and 50/50 category/menu bar.
- Removes Gutenberg/root block padding before the header and after the footer.
- Keeps desktop header markup separate so old responsive rules cannot force the mobile actions into a 2x2 layout.

## 3.0.10
- Rebuilt the header search submit button so parent/browser button styling cannot leak through.
- AJAX suggestions now render one product per full-width row.
- Search prices use clean current/regular WooCommerce values instead of accessibility helper text.
- English AJAX "view all" searches stay under /en/.

## 3.0.11
- Hardens the desktop/mobile search submit button against parent-theme and browser button styles.
- Uses a CSS-drawn white magnifier inside a full-height green button.
- Forces mobile Account / Wishlist / Compare / Cart into one non-wrapping four-item row.
- Keeps the mobile category/menu bar at a strict 50/50 split.
- Preserves the single-row AJAX suggestion layout introduced in 3.0.10.

## 3.0.12
- Rebuilt the WooCommerce mini-cart drawer with bounded product images, clear product rows, subtotal and two primary actions.
- Rebuilt the cart into a robust product table/card plus sticky order summary layout, including bundled demo image fallback.
- Rebuilt checkout into a two-column billing/delivery + sticky order summary layout with polished shipping and payment methods.
- Rebuilt logged-in My Account navigation/dashboard and normalized forms, tables, addresses, orders and account details.
- Added responsive transaction-page layouts for tablet/mobile without relying on parent Bootstrap widths.
