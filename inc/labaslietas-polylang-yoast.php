<?php
/**
 * LABAS LIETAS Polylang + Yoast integration.
 * Keeps the storefront bilingual (LV default + EN) and avoids duplicate SEO output.
 */
defined('ABSPATH') || exit;

function labaslietas_polylang_active_235() {
    return function_exists('PLL') && function_exists('pll_get_post_language') && function_exists('pll_set_post_language');
}

function labaslietas_polylang_language_model_235() {
    if (!function_exists('PLL')) { return null; }
    $pll = PLL();
    if (!is_object($pll) || empty($pll->model) || !is_object($pll->model)) { return null; }
    if (isset($pll->model->languages) && is_object($pll->model->languages)) { return $pll->model->languages; }
    return $pll->model;
}

function labaslietas_polylang_language_slug_235($language) {
    if (is_object($language) && isset($language->slug)) { return sanitize_key((string)$language->slug); }
    if (is_array($language) && isset($language['slug'])) { return sanitize_key((string)$language['slug']); }
    return '';
}

function labaslietas_polylang_language_term_id_235($language) {
    if (is_object($language) && isset($language->term_id)) { return absint($language->term_id); }
    if (is_array($language) && isset($language['term_id'])) { return absint($language['term_id']); }
    return 0;
}

function labaslietas_polylang_list_235() {
    $model = labaslietas_polylang_language_model_235();
    if (!$model) { return array(); }
    try {
        if (method_exists($model, 'get_list')) { return (array)$model->get_list(); }
        $pll = PLL();
        if (is_object($pll) && !empty($pll->model) && is_callable(array($pll->model, 'get_languages_list'))) {
            return (array)$pll->model->get_languages_list();
        }
    } catch (Throwable $e) {}
    return array();
}

function labaslietas_polylang_ensure_language_235($slug, $name, $locale, $flag, $order) {
    foreach (labaslietas_polylang_list_235() as $lang) {
        if (labaslietas_polylang_language_slug_235($lang) === $slug) { return true; }
    }
    $model = labaslietas_polylang_language_model_235();
    if (!$model) { return false; }
    $data = array(
        'name'=>$name,
        'slug'=>$slug,
        'locale'=>$locale,
        'rtl'=>false,
        'flag'=>$flag,
        'no_default_cat'=>false,
        'term_group'=>(int)$order,
    );
    try {
        if (method_exists($model, 'add')) {
            $result = $model->add($data);
        } else {
            $pll = PLL();
            $result = is_object($pll) && !empty($pll->model) && is_callable(array($pll->model, 'add_language')) ? $pll->model->add_language($data) : false;
        }
        return (bool)($result && !is_wp_error($result));
    } catch (Throwable $e) { return false; }
}

function labaslietas_polylang_set_default_lv_235() {
    $model = labaslietas_polylang_language_model_235();
    if (!$model) { return; }
    try {
        if (method_exists($model, 'update_default')) { $model->update_default('lv'); }
        else {
            $pll = PLL();
            if (is_object($pll) && !empty($pll->model) && is_callable(array($pll->model, 'update_default_lang'))) {
                $pll->model->update_default_lang('lv');
            }
        }
    } catch (Throwable $e) {}
    $opts = get_option('polylang', array());
    if (!is_array($opts)) { $opts = array(); }
    $opts['default_lang'] = 'lv';
    $opts['hide_default'] = true;
    $opts['media_support'] = false;
    update_option('polylang', $opts, false);
    update_option('WPLANG', 'lv', false);
}

function labaslietas_polylang_assign_storefront_lv_235() {
    if (!labaslietas_polylang_active_235()) { return; }
    $ids = array();
    $front = absint(get_option('page_on_front'));
    if ($front) { $ids[] = $front; }
    foreach (array('woocommerce_shop_page_id','woocommerce_cart_page_id','woocommerce_checkout_page_id','woocommerce_myaccount_page_id','woocommerce_terms_page_id') as $key) {
        $id = absint(get_option($key)); if ($id) { $ids[] = $id; }
    }
    foreach (array('demo-homepage','par-mums','piegade','piegade-un-apmaksa','apmaksa','atgriesana','atgriesana-un-garantija','kontakti','pirksanas-noteikumi','biezak-uzdotie-jautajumi','garantija','sudzibu-iesniegsana','track-your-order') as $slug) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if ($page instanceof WP_Post) { $ids[] = $page->ID; }
    }
    $ids = array_unique(array_filter(array_map('absint', $ids)));
    foreach ($ids as $id) { @pll_set_post_language($id, 'lv'); }

    // The current catalogue is Latvian. English products can be translated later from the + column.
    $product_ids = get_posts(array('post_type'=>array('product','product_variation'),'post_status'=>'any','posts_per_page'=>-1,'fields'=>'ids','no_found_rows'=>true,'suppress_filters'=>true));
    foreach ((array)$product_ids as $id) { @pll_set_post_language((int)$id, 'lv'); }

    if (function_exists('pll_set_term_language')) {
        foreach (array('product_cat','product_tag') as $taxonomy) {
            if (!taxonomy_exists($taxonomy)) { continue; }
            $term_ids = get_terms(array('taxonomy'=>$taxonomy,'hide_empty'=>false,'fields'=>'ids'));
            if (is_wp_error($term_ids)) { continue; }
            foreach ((array)$term_ids as $term_id) { @pll_set_term_language((int)$term_id, 'lv'); }
        }
    }
}

function labaslietas_polylang_delete_extra_languages_235() {
    $model = labaslietas_polylang_language_model_235();
    if (!$model) { return array(); }
    $removed = array();
    foreach (labaslietas_polylang_list_235() as $lang) {
        $slug = labaslietas_polylang_language_slug_235($lang);
        $term_id = labaslietas_polylang_language_term_id_235($lang);
        if (!$slug || in_array($slug, array('lv','en'), true) || !$term_id) { continue; }
        $ok = false;
        try {
            // Polylang 3.7+ API.
            if (method_exists($model, 'delete')) { $ok = (bool)$model->delete($term_id); }
            else {
                $pll = PLL();
                if (is_object($pll) && !empty($pll->model) && is_callable(array($pll->model, 'delete_language'))) {
                    $pll->model->delete_language($term_id);
                    $ok = true;
                }
            }
        } catch (Throwable $e) { $ok = false; }
        // Last-resort cleanup for stale language terms left by older starter imports.
        if (!$ok && taxonomy_exists('language')) {
            $deleted = wp_delete_term($term_id, 'language');
            $ok = !is_wp_error($deleted) && false !== $deleted;
        }
        if ($ok) { $removed[] = $slug; }
    }
    try {
        $pll = PLL();
        if (is_object($pll) && !empty($pll->model) && method_exists($pll->model, 'clean_languages_cache')) { $pll->model->clean_languages_cache(); }
    } catch (Throwable $e) {}
    return $removed;
}

function labaslietas_polylang_trim_menu_maps_235() {
    $opts = get_option('polylang', array());
    if (!is_array($opts)) { $opts = array(); }
    if (!empty($opts['nav_menus']) && is_array($opts['nav_menus'])) {
        foreach ($opts['nav_menus'] as $theme_key => $locations) {
            if (!is_array($locations)) { continue; }
            foreach ($locations as $location => $map) {
                if (is_array($map)) {
                    $opts['nav_menus'][$theme_key][$location] = array_intersect_key($map, array('lv'=>true,'en'=>true));
                }
            }
        }
    }
    $opts['default_lang'] = 'lv';
    $opts['hide_default'] = true;
    $opts['media_support'] = false;
    update_option('polylang', $opts, false);
}

function labaslietas_polylang_sync_lv_en_235($force = false) {
    if (!labaslietas_polylang_active_235()) { return array('configured'=>false,'reason'=>'polylang_inactive'); }
    $slugs = array();
    foreach (labaslietas_polylang_list_235() as $lang) { $slug = labaslietas_polylang_language_slug_235($lang); if ($slug) { $slugs[] = $slug; } }
    sort($slugs);
    if (!$force && $slugs === array('en','lv') && get_option('labaslietas_polylang_sync_235') === 'done') {
        return array('configured'=>true,'removed'=>array());
    }
    labaslietas_polylang_ensure_language_235('lv','Latviešu','lv','lv',0);
    labaslietas_polylang_ensure_language_235('en','English','en_GB','gb',1);
    labaslietas_polylang_set_default_lv_235();
    labaslietas_polylang_assign_storefront_lv_235();
    $removed = labaslietas_polylang_delete_extra_languages_235();
    labaslietas_polylang_trim_menu_maps_235();
    update_option('wp_theme_language_switcher_enabled', '1', false);
    update_option('wp_theme_demo_language_bar_enabled', '1', false);
    update_option('labaslietas_polylang_sync_235', 'done', false);
    return array('configured'=>true,'removed'=>$removed);
}

// Run once late in admin after Polylang and widgets have initialized. It is lightweight and does no media work.
add_action('admin_init', function() {
    if (!current_user_can('manage_options')) { return; }
    labaslietas_polylang_sync_lv_en_235(false);
}, 999);

// Keep only the two intended language columns even during the one request in which stale terms are being removed.
function labaslietas_polylang_admin_columns_235($columns) {
    if (!is_array($columns)) { return $columns; }
    foreach (array_keys($columns) as $key) {
        if (0 === strpos((string)$key, 'language_') && !in_array($key, array('language_lv','language_en'), true)) { unset($columns[$key]); }
    }
    return $columns;
}
foreach (array('page','post','product') as $pt) {
    add_filter('manage_' . $pt . '_posts_columns', 'labaslietas_polylang_admin_columns_235', 999);
}

// Restrict the Polylang admin-bar language list to All + LV + EN while stale starter languages are being cleaned.
add_filter('pll_admin_languages_filter', function($items, $all_items) {
    if (!is_array($items)) { return $items; }
    return array_values(array_filter($items, function($item) {
        $slug = '';
        if (is_object($item) && isset($item->slug)) { $slug = sanitize_key((string)$item->slug); }
        elseif (is_array($item) && isset($item['slug'])) { $slug = sanitize_key((string)$item['slug']); }
        return in_array($slug, array('all','lv','en'), true);
    }));
}, 999, 2);

/** Yoast SEO ***************************************************************/
function labaslietas_yoast_active_235() {
    return defined('WPSEO_VERSION') || class_exists('WPSEO_Options') || class_exists('Yoast\\WP\\SEO\\Main');
}

function labaslietas_seo_breadcrumbs_235() {
    if (labaslietas_yoast_active_235() && function_exists('yoast_breadcrumb')) {
        return yoast_breadcrumb('<nav class="labaslietas-breadcrumbs yoast-breadcrumbs" aria-label="Breadcrumbs">','</nav>', false);
    }
    if (function_exists('woocommerce_breadcrumb') && (function_exists('is_woocommerce') && is_woocommerce())) {
        ob_start();
        woocommerce_breadcrumb(array('wrap_before'=>'<nav class="labaslietas-breadcrumbs" aria-label="Breadcrumbs">','wrap_after'=>'</nav>'));
        return ob_get_clean();
    }
    return '';
}

// Yoast owns description/canonical/OpenGraph/schema. The theme only supplies a description fallback when Yoast has none.
add_filter('wpseo_metadesc', function($description) {
    if (is_string($description) && trim($description) !== '') { return $description; }
    if (function_exists('is_product') && is_product()) {
        global $product;
        if ($product instanceof WC_Product) {
            $text = wp_strip_all_tags($product->get_short_description());
            if (!$text) { $text = wp_strip_all_tags($product->get_description()); }
            return wp_trim_words($text, 28, '…');
        }
    }
    if (function_exists('is_product_category') && is_product_category()) {
        $term = get_queried_object();
        if ($term instanceof WP_Term && !empty($term->description)) { return wp_trim_words(wp_strip_all_tags($term->description), 28, '…'); }
    }
    if (is_page() && function_exists('labaslietas_information_page_definitions')) {
        $post = get_post();
        $defs = labaslietas_information_page_definitions();
        if ($post && isset($defs[$post->post_name]['lead'])) { return wp_strip_all_tags($defs[$post->post_name]['lead']); }
    }
    return $description;
}, 20);

// If an individual product has no Yoast social image, use its WooCommerce featured image.
add_filter('wpseo_opengraph_image', function($url) {
    if ($url) { return $url; }
    if (function_exists('is_product') && is_product()) {
        global $product;
        if ($product instanceof WC_Product && $product->get_image_id()) {
            $src = wp_get_attachment_image_url($product->get_image_id(), 'full');
            if ($src) { return $src; }
        }
    }
    return $url;
}, 20);

add_filter('wpseo_twitter_image', function($url) {
    if ($url) { return $url; }
    if (function_exists('is_product') && is_product()) {
        global $product;
        if ($product instanceof WC_Product && $product->get_image_id()) {
            $src = wp_get_attachment_image_url($product->get_image_id(), 'full');
            if ($src) { return $src; }
        }
    }
    return $url;
}, 20);

// Keep Yoast's schema for products and organisation intact; do not print competing theme schema.
add_filter('wpseo_breadcrumb_separator', function() { return '›'; });
