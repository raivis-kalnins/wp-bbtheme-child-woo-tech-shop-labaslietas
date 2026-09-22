<?php
/**
 * Labas Lietas 3.0.15 — full-width storefront shell, compact desktop header,
 * hero alignment and static comparison-feed publishing.
 */
defined('ABSPATH') || exit;

function labaslietas_v315_is_demo_sku($sku) {
    return is_string($sku) && strpos($sku, 'LL-DEMO-') === 0;
}

/**
 * The current demo catalogue is the catalogue visible on the storefront.
 * Publish it to the comparison feeds when the user explicitly uses this build,
 * while keeping unrelated DEMO-BUSINESS starter products blocked.
 */
function labaslietas_v315_prepare_feed_products() {
    if (!class_exists('WooCommerce') || !function_exists('wc_get_products')) { return; }
    $ids = wc_get_products(array(
        'status' => 'publish',
        'limit' => -1,
        'return' => 'ids',
    ));
    foreach ((array) $ids as $id) {
        $product = wc_get_product($id);
        if (!$product) { continue; }
        $sku = (string) $product->get_sku();
        if (!labaslietas_v315_is_demo_sku($sku)) { continue; }
        update_post_meta($id, '_labaslietas_demo_product', '1');
        update_post_meta($id, '_labaslietas_feed_exclude', 'no');
        update_post_meta($id, '_labaslietas_feed_used', 'yes');
        if (get_post_meta($id, '_labaslietas_feed_brand', true) === '') {
            update_post_meta($id, '_labaslietas_feed_brand', 'LABAS PRO');
        }
        if (get_post_meta($id, '_labaslietas_feed_model', true) === '') {
            update_post_meta($id, '_labaslietas_feed_model', preg_replace('/^LL-DEMO-/', '', $sku));
        }
    }
    if (function_exists('labaslietas_compare_feed_invalidate')) {
        labaslietas_compare_feed_invalidate();
    }
}

function labaslietas_v315_atomic_write($file, $contents) {
    $dir = dirname($file);
    if (!is_dir($dir) || !is_writable($dir)) { return false; }
    $tmp = $file . '.tmp-' . wp_generate_password(8, false, false);
    if (@file_put_contents($tmp, $contents, LOCK_EX) === false) {
        @unlink($tmp);
        return false;
    }
    @chmod($tmp, 0644);
    if (!@rename($tmp, $file)) {
        @unlink($tmp);
        return false;
    }
    return true;
}

/**
 * Create actual /kurpirkt.xml and /salidzini.xml files in the WordPress root.
 * Some hosts treat missing .xml files as static 404s before WordPress routing,
 * so a real file is the most reliable endpoint for comparison services.
 */
function labaslietas_v315_publish_static_feeds() {
    static $running = false;
    if ($running || !class_exists('WooCommerce') || !function_exists('labaslietas_compare_feed_build')) { return false; }
    $running = true;
    $ok = true;
    foreach (array('kurpirkt','salidzini') as $feed) {
        $xml = labaslietas_compare_feed_build($feed);
        if (!is_string($xml) || trim($xml) === '') { $ok = false; continue; }
        $root_file = trailingslashit(ABSPATH) . $feed . '.xml';
        if (!labaslietas_v315_atomic_write($root_file, $xml)) { $ok = false; }
        // Keep the uploads cache in sync as well.
        if (function_exists('labaslietas_compare_feed_ensure_cache_dir') && labaslietas_compare_feed_ensure_cache_dir()) {
            $cache_file = labaslietas_compare_feed_cache_file($feed);
            if ($cache_file) { labaslietas_v315_atomic_write($cache_file, $xml); }
        }
    }
    $running = false;
    return $ok;
}

/** Fallback for hosts where the WordPress root is not writable. */
function labaslietas_v315_install_feed_htaccess_rules() {
    $htaccess = trailingslashit(ABSPATH) . '.htaccess';
    if (!file_exists($htaccess) && !is_writable(ABSPATH)) { return false; }
    if (file_exists($htaccess) && !is_writable($htaccess)) { return false; }
    if (!function_exists('insert_with_markers')) {
        $misc = ABSPATH . 'wp-admin/includes/misc.php';
        if (file_exists($misc)) { require_once $misc; }
    }
    if (!function_exists('insert_with_markers')) { return false; }
    $rules = array(
        '<IfModule mod_rewrite.c>',
        'RewriteEngine On',
        'RewriteRule ^kurpirkt\\.xml$ index.php?labaslietas_compare_feed=kurpirkt [L,QSA]',
        'RewriteRule ^salidzini\\.xml$ index.php?labaslietas_compare_feed=salidzini [L,QSA]',
        '</IfModule>',
    );
    return (bool) insert_with_markers($htaccess, 'Labas Lietas comparison feeds', $rules);
}

function labaslietas_v315_feed_upgrade() {
    if (!current_user_can('edit_theme_options')) { return; }
    $version = (string) get_option('labaslietas_v315_feed_version', '');
    if ($version !== '3.0.15') {
        labaslietas_v315_prepare_feed_products();
        if (!labaslietas_v315_publish_static_feeds()) {
            $fallback = labaslietas_v315_install_feed_htaccess_rules();
            if (function_exists('labaslietas_compare_feed_register_rewrites')) {
                labaslietas_compare_feed_register_rewrites();
                flush_rewrite_rules(false);
            }
            update_option('labaslietas_v315_feed_publish_error', $fallback ? 'static-root-not-writable-htaccess-installed' : 'root-and-htaccess-not-writable', false);
        } else {
            delete_option('labaslietas_v315_feed_publish_error');
        }
        update_option('labaslietas_v315_feed_version', '3.0.15', false);
    }
}
add_action('after_switch_theme', 'labaslietas_v315_feed_upgrade', 220);
add_action('admin_init', 'labaslietas_v315_feed_upgrade', 220);


add_action('admin_notices', function() {
    if (!current_user_can('manage_options')) { return; }
    $error = get_option('labaslietas_v315_feed_publish_error', '');
    if (!$error) { return; }
    $msg = $error === 'static-root-not-writable-htaccess-installed'
        ? 'Labas Lietas: XML root files could not be written, so an .htaccess route was installed instead. Test /kurpirkt.xml and /salidzini.xml after clearing server cache.'
        : 'Labas Lietas: the server did not allow writing the XML root files or .htaccess. Ask the host to make the WordPress root/.htaccess writable so /kurpirkt.xml and /salidzini.xml can be published.';
    echo '<div class="notice notice-warning"><p>' . esc_html($msg) . '</p></div>';
});

/** Ensure missing static feeds are created after any normal WordPress page hit. */
add_action('wp_loaded', function() {
    if (is_admin() || !class_exists('WooCommerce')) { return; }
    $missing = !file_exists(trailingslashit(ABSPATH) . 'kurpirkt.xml') || !file_exists(trailingslashit(ABSPATH) . 'salidzini.xml');
    if ($missing) { labaslietas_v315_publish_static_feeds(); }
}, 120);

/** Refresh the actual public XML files immediately after catalogue changes. */
function labaslietas_v315_queue_static_feed_refresh() {
    static $queued = false;
    if ($queued) { return; }
    $queued = true;
    add_action('shutdown', 'labaslietas_v315_publish_static_feeds', 900);
}
add_action('save_post_product', 'labaslietas_v315_queue_static_feed_refresh', 200);
add_action('woocommerce_update_product', 'labaslietas_v315_queue_static_feed_refresh', 200);
add_action('woocommerce_update_product_variation', 'labaslietas_v315_queue_static_feed_refresh', 200);
add_action('woocommerce_product_set_stock', 'labaslietas_v315_queue_static_feed_refresh', 200);
add_action('woocommerce_variation_set_stock', 'labaslietas_v315_queue_static_feed_refresh', 200);
add_action('update_option_labaslietas_theme_options', 'labaslietas_v315_queue_static_feed_refresh', 200);

/**
 * Prefer bundled imagery for LL-DEMO products even if an old import left a stale
 * attachment ID on the product. This also keeps XML product images consistent.
 */
function labaslietas_v315_demo_asset_url($product) {
    if (!$product || !is_a($product, 'WC_Product')) { return ''; }
    $map = array(
        'LL-DEMO-D20'=>'drill.png','LL-DEMO-C50'=>'compressor.png','LL-DEMO-W200'=>'welder.png','LL-DEMO-G3500'=>'generator.png',
        'LL-DEMO-J3T'=>'jack.png','LL-DEMO-A1500'=>'impact-wrench.png','LL-DEMO-S108'=>'tool-set.png','LL-DEMO-B26'=>'blower.png',
        'LL-DEMO-BC52'=>'brushcutter.png','LL-DEMO-H10'=>'trimmer-head.png','LL-DEMO-HALU'=>'aluminum-head.png','LL-DEMO-L24'=>'trimmer-line.png',
        'LL-DEMO-CS85'=>'chain-sharpener.png','LL-DEMO-OP12'=>'oil-pump.png'
    );
    $sku = (string) $product->get_sku();
    if (empty($map[$sku])) { return ''; }
    $path = get_stylesheet_directory() . '/assets/demo-products/' . $map[$sku];
    return file_exists($path) ? get_stylesheet_directory_uri() . '/assets/demo-products/' . $map[$sku] : '';
}

/** Replace stale product thumbnails everywhere for the bundled demo catalogue. */
add_filter('woocommerce_product_get_image_id', function($image_id, $product) {
    // Keep image_id untouched: URL-level renderers below use the bundled asset.
    return $image_id;
}, PHP_INT_MAX, 2);

/** Late CSS wins over WPBBuilder/Bootstrap and the older theme repair layers. */
function labaslietas_v315_css() {
    ?>
    <style id="labaslietas-v315-final-layout-css">
    :root{--ll-v315-shell:1480px;--ll-v315-gutter:24px}

    /* One storefront width everywhere. */
    body.labaslietas-theme .llg-archive-page>.labaslietas-container,
    body.labaslietas-theme .llg-single-container,
    body.labaslietas-theme .llg-account-shell>.labaslietas-container,
    body.labaslietas-theme .llg-commerce-shell>.labaslietas-container,
    body.labaslietas-theme .labaslietas-page-shell,
    body.labaslietas-theme .labaslietas-search-page>.labaslietas-container,
    body.labaslietas-theme .llg-v313-catalog-page>.labaslietas-container,
    body.labaslietas-theme .labaslietas-cart-page,
    body.labaslietas-theme .labaslietas-checkout-page,
    body.labaslietas-theme .llg-woo-page{
      width:min(var(--ll-v315-shell),calc(100% - (var(--ll-v315-gutter) * 2)))!important;
      max-width:var(--ll-v315-shell)!important;
      margin-left:auto!important;margin-right:auto!important;
      padding-left:0!important;padding-right:0!important;
    }
    body.labaslietas-theme .labaslietas-page-shell .entry-content,
    body.labaslietas-theme .labaslietas-page-shell .labaslietas-page-card,
    body.woocommerce-account .llg-myaccount-layout,
    body.single-product .llg-single-product-card,
    body.labaslietas-theme .labaslietas-archive-products-col{width:100%!important;max-width:none!important}
    body.labaslietas-theme .labaslietas-page-card{margin-left:0!important;margin-right:0!important}
    body.labaslietas-theme .labaslietas-page-card>.entry-content{margin-left:0!important;margin-right:0!important}

    /* Compact desktop header: remove the empty band under popular searches. */
    @media(min-width:821px){
      body.labaslietas-theme .llg-mainbar-preview{
        min-height:0!important;height:auto!important;
        grid-template-columns:250px minmax(520px,1fr) 340px!important;
        gap:24px!important;align-items:center!important;
        padding:10px 0 9px!important;
      }
      body.labaslietas-theme .llg-logo .labaslietas-logo{width:226px!important;max-width:226px!important}
      body.labaslietas-theme .llg-logo .labaslietas-logo img{max-height:104px!important;object-position:left center!important}
      body.labaslietas-theme .llg-search-column{gap:6px!important;align-self:center!important;justify-self:stretch!important}
      body.labaslietas-theme .llg-search-extended{height:50px!important;grid-template-columns:164px minmax(0,1fr) 56px!important;border-radius:9px!important;padding-right:0!important;overflow:visible!important}
      body.labaslietas-theme .llg-search-select-wrap select,
      body.labaslietas-theme .llg-search-field-wrap input[type=search]{height:48px!important}
      body.labaslietas-theme .llg-search-select-wrap:after{top:13px!important}
      body.labaslietas-theme .llg-search-submit,
      body.labaslietas-theme .llg-search-extended>button{
        position:relative!important;inset:auto!important;transform:none!important;
        width:56px!important;min-width:56px!important;max-width:56px!important;
        height:48px!important;min-height:48px!important;
        margin:0!important;padding:0!important;
        display:grid!important;place-items:center!important;
        border:0!important;border-left:1px solid #d8e0e5!important;
        border-radius:0 8px 8px 0!important;
        background:#f1f4f5!important;color:#3d5261!important;
        box-shadow:none!important;line-height:1!important;
      }
      body.labaslietas-theme .llg-search-submit:hover,
      body.labaslietas-theme .llg-search-submit:focus{background:#e8edef!important;color:#1f7b3a!important}
      body.labaslietas-theme .llg-search-submit .labaslietas-green-icon,
      body.labaslietas-theme .llg-search-extended>button .labaslietas-green-icon{
        width:21px!important;height:21px!important;margin:0!important;display:block!important;position:static!important;transform:none!important
      }
      body.labaslietas-theme .llg-search-submit:before,body.labaslietas-theme .llg-search-submit:after{content:none!important;display:none!important}
      body.labaslietas-theme .llg-popular-searches{min-height:20px!important;padding:0 4px!important;margin:0!important;line-height:1.25!important}
      body.labaslietas-theme .llg-actions{width:340px!important;min-width:340px!important;align-self:center!important;gap:4px!important}
      body.labaslietas-theme .llg-actions>a,body.labaslietas-theme .llg-actions>button{
        height:70px!important;min-height:70px!important;padding:4px 4px!important;justify-content:center!important;gap:3px!important
      }
      body.labaslietas-theme .llg-actions .labaslietas-green-icon{width:24px!important;height:24px!important}
      body.labaslietas-theme .llg-actions em{top:3px!important;right:8px!important}
      body.labaslietas-theme .llg-actions .labaslietas-cart-total{font-size:10px!important;line-height:1!important}
      body.labaslietas-theme .llg-header .labaslietas-search-results{top:56px!important;left:-165px!important;right:-55px!important}
    }

    /* Homepage hero keeps the category column, artwork and trust list on one baseline. */
    body.labaslietas-theme .llg-home-stage{align-items:stretch!important;gap:18px!important}
    body.labaslietas-theme .llg-home-hero{height:100%!important;min-height:420px!important;align-self:stretch!important}
    body.labaslietas-theme .llg-home-hero-copy{padding-top:38px!important;padding-bottom:38px!important;justify-content:center!important}
    body.labaslietas-theme .llg-home-hero-art{height:100%!important;min-height:420px!important}
    body.labaslietas-theme .llg-home-hero-art img{height:100%!important;min-height:420px!important;object-position:center center!important}
    body.labaslietas-theme .llg-home-hero-trust{top:50%!important;right:24px!important;transform:translateY(-50%)!important;min-width:220px!important}
    body.labaslietas-theme .llg-clean-benefits{align-items:stretch!important}
    body.labaslietas-theme .llg-clean-benefits>div{height:100%!important}

    /* Archive/info/account/single page rhythm now matches the homepage grid. */
    body.labaslietas-theme .llg-archive-page,
    body.labaslietas-theme .llg-v313-catalog-page,
    body.labaslietas-theme .labaslietas-search-page,
    body.labaslietas-theme .llg-commerce-shell,
    body.labaslietas-theme .labaslietas-page-shell,
    body.single-product.labaslietas-theme .llg-single-page{padding-top:24px!important;padding-bottom:48px!important}
    body.labaslietas-theme .labaslietas-archive-hero,
    body.labaslietas-theme .labaslietas-page-heading,
    body.labaslietas-theme .labaslietas-search-hero,
    body.labaslietas-theme .llg-v313-page-hero{width:100%!important;max-width:none!important;margin-left:0!important;margin-right:0!important}
    body.labaslietas-theme .labaslietas-page-card{padding:24px!important}
    body.woocommerce-account .llg-myaccount-layout{grid-template-columns:270px minmax(0,1fr)!important;gap:22px!important}
    body.woocommerce-account .llg-myaccount-content-card{min-width:0!important;padding:24px!important}

    /* Product page gets the same wide shell instead of a short centered island. */
    body.single-product.labaslietas-theme .llg-single-product-card{grid-template-columns:minmax(0,1.05fr) minmax(430px,.95fr)!important;gap:24px!important}
    body.single-product.labaslietas-theme .llg-single-gallery-panel,
    body.single-product.labaslietas-theme .llg-single-summary-panel{min-width:0!important;max-width:none!important}
    body.single-product.labaslietas-theme .llg-single-summary-panel{padding:24px!important}
    body.single-product.labaslietas-theme .llg-single-buybox{padding:16px!important;border:1px solid #dfe7ec!important;background:#f8faf9!important;border-radius:10px!important}

    @media(max-width:1180px){
      body.single-product.labaslietas-theme .llg-single-product-card{grid-template-columns:1fr!important}
      body.woocommerce-account .llg-myaccount-layout{grid-template-columns:230px minmax(0,1fr)!important}
    }
    @media(max-width:820px){
      :root{--ll-v315-gutter:10px}
      body.labaslietas-theme .llg-archive-page>.labaslietas-container,
      body.labaslietas-theme .llg-single-container,
      body.labaslietas-theme .llg-account-shell>.labaslietas-container,
      body.labaslietas-theme .llg-commerce-shell>.labaslietas-container,
      body.labaslietas-theme .labaslietas-page-shell,
      body.labaslietas-theme .labaslietas-search-page>.labaslietas-container,
      body.labaslietas-theme .llg-v313-catalog-page>.labaslietas-container,
      body.labaslietas-theme .labaslietas-cart-page,
      body.labaslietas-theme .labaslietas-checkout-page,
      body.labaslietas-theme .llg-woo-page{width:calc(100% - 20px)!important;max-width:none!important}
      body.woocommerce-account .llg-myaccount-layout{grid-template-columns:1fr!important}
      body.single-product.labaslietas-theme .llg-single-summary-panel{padding:18px!important}
      body.labaslietas-theme .llg-home-hero-trust{right:12px!important;min-width:0!important}
    }
    </style>
    <?php
}
add_action('wp_head', 'labaslietas_v315_css', PHP_INT_MAX);

/**
 * Override the v38 gallery just before rendering by marking LL-DEMO products as
 * demo products. This makes the existing gallery renderer prefer the SKU asset.
 */
add_action('wp', function() {
    if (!function_exists('is_product') || !is_product() || !function_exists('wc_get_product')) { return; }
    $product = wc_get_product(get_queried_object_id());
    if (!$product) { return; }
    if (labaslietas_v315_is_demo_sku((string) $product->get_sku()) && get_post_meta($product->get_id(), '_labaslietas_demo_product', true) !== '1') {
        update_post_meta($product->get_id(), '_labaslietas_demo_product', '1');
    }
}, 1);
