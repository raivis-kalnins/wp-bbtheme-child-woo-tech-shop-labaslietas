# Labas Lietas 3.0.33

## 3.0.33 homepage search + monthly 3Lietas promotion

- Rebuilds the mobile search geometry directly in the shared header markup so the homepage cannot render a taller/wider submit button than inner pages.
- Adds a version marker (`data-ll-search-v="3033"`) and matching final CSS guard for the 46px search shell / 44px controls.
- Replaces the homepage seasonal advert with a monthly `3Lietas <month> iesaka` panel when the configured month is active.
- The monthly panel shows three discounted WooCommerce products with image, product name, old/new price and discount badge.
- Initial September picks prefer the currently discounted H10 trimmer head, G3500 generator and A1500 impact wrench when those SKUs exist. Otherwise the module fills from active sale products.
- Monthly promotion management is available in **Appearance -> 3Lietas mēneša piedāvājums**: enable/disable, choose month, optional custom title and choose three products.
- The promotion automatically disappears outside the configured month. Product sale prices and sale date ranges remain managed in the normal WooCommerce product editor.
- Includes one-time common cache purging after upgrade.

Small targeted storefront update based on v3.0.24.

Changes:
- Homepage desktop utility/top bar now spans the full viewport rhythm and pins Par mums / Piegāde un apmaksa / Kontakti / social / LV-EN to the right edge.
- Left Smiltene / delivery items remain left aligned.
- Latvian `My Quote` label shortened from `Mans cenu pieprasījums` to `Cenu pieprasījums`.
- Includes a late fallback for quote UI inserted dynamically by the Woo support plugin.
- One-time theme/object/Woo transient cache purge on first wp-admin load after upgrade.

Do not run Starter Setup or Demo Refresh for this update.
## 3.0.27 responsive header fix

- Final mobile/tablet header cascade layer below 1050px.
- Four shop actions stay on one row at phone/tablet widths.
- Mobile search is always category + query + green search button on one row.
- Category/Menu navigation stays 50/50.
- Reduced logo/header whitespace and tightened first homepage section spacing.
- Includes a footer cascade guard so older plugin/theme CSS cannot revert the mobile layout.



## 3.0.28 homepage grid cleanup

- Removed the homepage-only utility-bar width override so the homepage uses the same header grid as inner pages.
- Added one site-wide desktop grid guard for topbar, main header and navigation.
- Synced homepage stage, benefit strip and product sections to the same 1460px storefront grid.
- Theme author metadata now points to DigitalPulse.click.

## 3.0.30 mobile header repair

- Forces the mobile search control to use the same green submit button on the homepage and all inner pages.
- Restores the missing mobile category drawer containment after the 3.0.29 presentation cleanup: hidden by default, positioned below the category button, scrollable, and layered above page content.
- Adds a late footer guard against legacy/plugin CSS and purges common caches once after upgrade.

## 3.0.29 stability cleanup
- Removes the accumulated legacy mobile-header CSS hooks and replaces them with one isolated responsive source of truth.
- Mobile search is always one row and the four shop actions always stay on one row.
- Cart/checkout delivery choices fill 100% of the available summary width.
- Newsletter checkout opt-in is rebranded/translated to Labas Lietas (LV/EN), removing the incorrect WordPress wording.

## 3.0.31 homepage mobile search parity
- Fixes the remaining homepage-only grey mobile search button.
- Targets the stable mobile-search markup instead of relying on the newer `ll29-mobile-search` helper class, so older cached homepage markup is also covered.
- Adds an inline button fallback, late runtime guard, and matching rule in `assets/css/theme.css`.
- Extends the one-time cache purge to common WordPress page-cache plugins.


## 3.0.32 homepage responsive search-box containment
- Keeps the now-correct green search button fully inside the mobile search box on the homepage.
- Applies the same 46px outer / 44px inner geometry used by the working inner-page header, including older cached homepage markup that lacks the `ll29-mobile-search` helper class.
- Scopes the geometry fix to the front page so responsive inner pages are not changed.
- Adds a late runtime geometry guard and a one-time cache purge for the update.
