<?php
/**
 * Labas Lietas 3.0.28
 * Site-wide storefront grid unification.
 *
 * No home/front-page selectors are used here. The exact same grid owns the
 * utility bar, main header, navigation and primary storefront sections on all
 * routes.
 */
defined('ABSPATH') || exit;

function labaslietas_v328_grid_css() {
    if (is_admin()) { return; }
    ?>
    <style id="labaslietas-v328-grid-css">
    :root{--ll28-grid:1460px;--ll28-gutter:40px}
    @media (min-width:1050px){
      body.labaslietas-theme .llg-topbar>.llg-topbar-inner.labaslietas-container,
      body.labaslietas-theme #labaslietas-desktop-mainbar,
      body.labaslietas-theme .llg-nav-row>.llg-nav-inner.labaslietas-container,
      body.labaslietas-theme .llg-home>.llg-home-stage.labaslietas-container,
      body.labaslietas-theme .llg-home>.llg-benefit-strip.labaslietas-container,
      body.labaslietas-theme .llg-home>.llg-home-products.labaslietas-container{
        width:min(calc(100% - var(--ll28-gutter)),var(--ll28-grid))!important;
        max-width:var(--ll28-grid)!important;
        margin-left:auto!important;
        margin-right:auto!important;
      }

      body.labaslietas-theme .llg-topbar>.llg-topbar-inner.labaslietas-container{
        display:flex!important;
        align-items:center!important;
        justify-content:space-between!important;
        gap:24px!important;
        min-height:36px!important;
        padding-left:0!important;
        padding-right:0!important;
      }
      body.labaslietas-theme .llg-topbar-left{
        display:flex!important;
        align-items:center!important;
        justify-content:flex-start!important;
        flex:0 1 auto!important;
        gap:22px!important;
        width:auto!important;
        min-width:0!important;
        margin:0!important;
      }
      body.labaslietas-theme .llg-topbar-right{
        display:flex!important;
        align-items:center!important;
        justify-content:flex-end!important;
        flex:0 0 auto!important;
        gap:22px!important;
        width:auto!important;
        min-width:0!important;
        margin:0 0 0 auto!important;
        text-align:right!important;
      }
      body.labaslietas-theme .llg-topbar-left>span,
      body.labaslietas-theme .llg-topbar-right>a{
        flex:0 0 auto!important;
        margin:0!important;
        white-space:nowrap!important;
      }

      body.labaslietas-theme #labaslietas-desktop-mainbar.ll24-mainbar{
        grid-template-columns:190px minmax(0,1fr) 320px!important;
        column-gap:22px!important;
        padding-left:0!important;
        padding-right:0!important;
      }
      body.labaslietas-theme .llg-nav-row>.llg-nav-inner.labaslietas-container{
        padding-left:0!important;
        padding-right:0!important;
      }

      /* Prevent a front-page block/container from re-introducing side offsets. */
      body.labaslietas-theme .llg-home,
      body.labaslietas-theme .llg-clean-home{
        width:100%!important;
        max-width:none!important;
        margin-left:0!important;
        margin-right:0!important;
      }
      body.labaslietas-theme .llg-home-stage,
      body.labaslietas-theme .llg-benefit-strip,
      body.labaslietas-theme .llg-home-products{
        padding-left:0!important;
        padding-right:0!important;
      }
    }

    @media (min-width:1050px) and (max-width:1279px){
      :root{--ll28-gutter:28px}
      body.labaslietas-theme #labaslietas-desktop-mainbar.ll24-mainbar{
        grid-template-columns:170px minmax(0,1fr) 292px!important;
        column-gap:16px!important;
      }
      body.labaslietas-theme #labaslietas-desktop-mainbar>.ll24-logo{
        width:170px!important;max-width:170px!important;
      }
      body.labaslietas-theme #labaslietas-desktop-mainbar>.ll24-logo img,
      body.labaslietas-theme #labaslietas-desktop-mainbar>.ll24-logo .labaslietas-logo,
      body.labaslietas-theme #labaslietas-desktop-mainbar>.ll24-logo .labaslietas-approved-logo{
        width:160px!important;max-width:160px!important;
      }
      body.labaslietas-theme #labaslietas-desktop-mainbar>.ll24-actions{
        width:292px!important;min-width:292px!important;max-width:292px!important;
      }
    }
    </style>
    <?php
}
add_action('wp_head', 'labaslietas_v328_grid_css', PHP_INT_MAX);

function labaslietas_v328_cache_purge_once() {
    if (!is_admin() || !current_user_can('manage_options')) { return; }
    if ((string)get_option('labaslietas_v328_cache_purged','') === '3.0.28') { return; }
    if (function_exists('wp_theme_purge_all_theme_cache')) { wp_theme_purge_all_theme_cache(); }
    if (function_exists('wp_cache_flush')) { wp_cache_flush(); }
    if (function_exists('wc_delete_product_transients')) { wc_delete_product_transients(); }
    update_option('labaslietas_v328_cache_purged','3.0.28',false);
}
add_action('admin_init','labaslietas_v328_cache_purge_once',1002);
