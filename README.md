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
