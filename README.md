# Labas Lietas 3.0.29

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

## 3.0.29 stability cleanup
- Removes the accumulated legacy mobile-header CSS hooks and replaces them with one isolated responsive source of truth.
- Mobile search is always one row and the four shop actions always stay on one row.
- Cart/checkout delivery choices fill 100% of the available summary width.
- Newsletter checkout opt-in is rebranded/translated to Labas Lietas (LV/EN), removing the incorrect WordPress wording.
