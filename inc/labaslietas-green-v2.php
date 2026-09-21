<?php
/**
 * LABAS LIETAS Green Storefront v2.
 * Distinct Latvian WooCommerce storefront with an optional original demo catalogue.
 */
defined('ABSPATH') || exit;

if (!defined('LABASLIETAS_GREEN_V2')) {
    define('LABASLIETAS_GREEN_V2', '2.3.4');
}

function labaslietas_green_icon($name, $class = '') {
    $paths = array(
        'location' => '<path d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/><circle cx="12" cy="9" r="2.5"/>',
        'truck' => '<path d="M3 6h11v10H3z"/><path d="M14 9h4l3 3v4h-7z"/><circle cx="7" cy="18" r="2"/><circle cx="18" cy="18" r="2"/>',
        'store' => '<path d="M4 10v10h16V10"/><path d="M3 10 5 4h14l2 6"/><path d="M8 20v-6h8v6"/><path d="M3 10c0 2 3 2 3 0 0 2 3 2 3 0 0 2 3 2 3 0 0 2 3 2 3 0 0 2 3 2 3 0 0 2 3 2 3 0"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v6l4 2"/>',
        'search' => '<circle cx="11" cy="11" r="6.5"/><path d="m16 16 5 5"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c1-5 4-7 8-7s7 2 8 7"/>',
        'heart' => '<path d="M20.5 5.5a5 5 0 0 0-7.1 0L12 6.9l-1.4-1.4a5 5 0 0 0-7.1 7.1L12 21l8.5-8.4a5 5 0 0 0 0-7.1Z"/>',
        'compare' => '<path d="M7 7h13l-3-3m3 3-3 3M17 17H4l3 3m-3-3 3-3"/>',
        'cart' => '<path d="M3 4h2l2 11h10l3-8H7"/><circle cx="9" cy="19" r="1.5"/><circle cx="17" cy="19" r="1.5"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'facebook' => '<path d="M14 8h3V4h-3c-3 0-5 2-5 5v3H6v4h3v5h4v-5h3l1-4h-4V9c0-.7.3-1 1-1Z" fill="currentColor" stroke="none"/>',
        'shield' => '<path d="M12 3 5 6v5c0 5 3 8 7 10 4-2 7-5 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/>',
        'return' => '<path d="M9 7H5v-4"/><path d="M5 7c2-3 5-4 8-3a8 8 0 1 1-5 15"/>',
        'arrow' => '<path d="M5 12h14m-5-5 5 5-5 5"/>',
        'phone' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3.1a2 2 0 0 1-.6 1.8L7.7 10a16 16 0 0 0 6.3 6.3l1.4-1.3a2 2 0 0 1 1.8-.6l3.1.5a2 2 0 0 1 1.7 2Z"/>',
        'mail' => '<path d="M4 6h16v12H4z"/><path d="m4 8 4 3 8-6"/>',
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17" cy="7" r="1"/>',
        'percent' => '<circle cx="7" cy="7" r="2"/><circle cx="17" cy="17" r="2"/><path d="M18 6 6 18"/>',
    );
    if (!isset($paths[$name])) { return ''; }
    return '<svg class="labaslietas-green-icon ' . esc_attr($class) . '" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $paths[$name] . '</svg>';
}

function labaslietas_green_language_switcher_234() {
    $links = array();
    if (function_exists('pll_the_languages')) {
        $raw = pll_the_languages(array('raw'=>1,'hide_if_empty'=>0,'hide_if_no_translation'=>0));
        if (is_array($raw)) {
            foreach (array('lv','en') as $slug) {
                if (!empty($raw[$slug]) && is_array($raw[$slug])) {
                    $links[] = '<a class="llg-lang-link' . (!empty($raw[$slug]['current_lang']) ? ' is-current' : '') . '" href="' . esc_url($raw[$slug]['url']) . '">' . esc_html(strtoupper($slug)) . '</a>';
                }
            }
        }
    }
    if (!$links) { $links = array('<span class="llg-lang-link is-current">LV</span>','<span class="llg-lang-link">EN</span>'); }
    return '<span class="llg-lang-switcher">' . implode('<span class="llg-lang-sep">/</span>', $links) . '</span>';
}

function labaslietas_green_header_html() {
    $shop = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
    $account = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/');
    $cart_count = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;
    $cart_total = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_total() : '';
    $wishlist_url = function_exists('labaslietas_list_url') ? labaslietas_list_url('wishlist') : home_url('/');
    $compare_url = function_exists('labaslietas_list_url') ? labaslietas_list_url('compare') : home_url('/');
    $wishlist_count = function_exists('labaslietas_get_list_items') ? count(labaslietas_get_list_items('wishlist')) : 0;
    $compare_count = function_exists('labaslietas_get_list_items') ? count(labaslietas_get_list_items('compare')) : 0;
    $facebook = labaslietas_get_theme_option('social_facebook', 'https://www.facebook.com/labas.lietas.33');
    $instagram = labaslietas_get_theme_option('social_instagram', '#');
    $phone = trim((string) labaslietas_get_theme_option('phone', '+371 29 123 456'));
    $email = trim((string) labaslietas_get_theme_option('email', 'info@labaslietas.63.lv'));
    $top_categories = get_terms(array('taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'number'=>12,'orderby'=>'count','order'=>'DESC'));
    $keywords = array('trimmera galva', 'ķēdes zāģis', 'kompresors', 'ģenerators', 'darba rīki');
    ob_start(); ?>
    <header class="llg-header llg-preview-header">
        <div class="llg-topbar">
            <div class="labaslietas-container llg-topbar-inner">
                <div class="llg-topbar-left">
                    <span><?php echo labaslietas_green_icon('location'); ?> Smiltene, LV-4729</span>
                    <span><?php echo labaslietas_green_icon('truck'); ?> Piegāde visā Latvijā</span>
                    <?php if ($phone) : ?><span><?php echo labaslietas_green_icon('phone'); ?> <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/','',$phone)); ?>"><?php echo esc_html($phone); ?></a></span><?php endif; ?>
                    <?php if ($email) : ?><span><?php echo labaslietas_green_icon('mail'); ?> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></span><?php endif; ?>
                </div>
                <div class="llg-topbar-right">
                    <a href="<?php echo esc_url(labaslietas_page_url('par-mums')); ?>">Par mums</a>
                    <a href="<?php echo esc_url(labaslietas_page_url('piegade-un-apmaksa')); ?>">Piegāde un apmaksa</a>
                    <a href="<?php echo esc_url(labaslietas_page_url('kontakti')); ?>">Kontakti</a>
                    <?php if ($facebook) : ?><a aria-label="Facebook" href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer"><?php echo labaslietas_green_icon('facebook'); ?></a><?php endif; ?>
                    <?php if ($instagram && $instagram !== '#') : ?><a aria-label="Instagram" href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer"><?php echo labaslietas_green_icon('instagram'); ?></a><?php endif; ?>
                    <?php echo labaslietas_green_language_switcher_234(); ?>
                </div>
            </div>
        </div>
        <div class="labaslietas-container llg-mainbar llg-mainbar-preview">
            <div class="llg-logo"><?php echo labaslietas_logo_html(); ?></div>
            <div class="llg-search-column">
                <form role="search" method="get" class="llg-search llg-search-extended labaslietas-ajax-search" action="<?php echo esc_url(home_url('/')); ?>" autocomplete="off">
                    <div class="llg-search-select-wrap"><select name="product_cat" aria-label="Kategorija"><option value=""><?php echo esc_html__('Visas kategorijas', 'wp-bbtheme-child-woo-tech-shop-labaslietas'); ?></option><?php if (!is_wp_error($top_categories)) : foreach ($top_categories as $term) : ?><option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option><?php endforeach; endif; ?></select></div>
                    <div class="labaslietas-search-field-wrap llg-search-field-wrap"><input type="search" name="s" placeholder="Meklēt preci, ražotāju, SKU..." value="<?php echo esc_attr(get_search_query()); ?>" aria-label="Meklēt produktus"><div class="labaslietas-search-results" hidden></div></div>
                    <input type="hidden" name="post_type" value="product">
                    <button type="submit" aria-label="Meklēt"><?php echo labaslietas_green_icon('search'); ?></button>
                </form>
                <div class="llg-popular-searches"><strong>Populāri meklējumi:</strong><?php foreach ($keywords as $kw) : ?><a href="<?php echo esc_url(add_query_arg(array('s'=>$kw,'post_type'=>'product'), home_url('/'))); ?>"><?php echo esc_html($kw); ?></a><?php endforeach; ?></div>
            </div>
            <nav class="llg-actions" aria-label="Veikala darbības">
                <a href="<?php echo esc_url($account); ?>"><?php echo labaslietas_green_icon('user'); ?><span>Konts</span></a>
                <a href="<?php echo esc_url($wishlist_url); ?>"><?php echo labaslietas_green_icon('heart'); ?><span>Vēlmes</span><em class="labaslietas-wishlist-count"><?php echo (int) $wishlist_count; ?></em></a>
                <a class="llg-compare-action" href="<?php echo esc_url($compare_url); ?>"><?php echo labaslietas_green_icon('compare'); ?><span>Salīdzināt</span><em class="labaslietas-compare-count"><?php echo (int) $compare_count; ?></em></a>
                <button class="llg-cart labaslietas-mini-cart-toggle" type="button" aria-controls="labaslietas-mini-cart-panel" aria-expanded="false"><?php echo labaslietas_green_icon('cart'); ?><span>Grozs</span><em class="labaslietas-cart-count"><?php echo (int) $cart_count; ?></em><strong class="labaslietas-cart-total"><?php echo wp_kses_post($cart_total); ?></strong></button>
            </nav>
        </div>
        <div class="llg-nav-row">
            <div class="labaslietas-container llg-nav-inner llg-nav-inner-preview">
                <div class="llg-catalog-wrap llg-catalog-wrap-fixed"><button class="llg-catalog-button" type="button" aria-expanded="false"><?php echo labaslietas_green_icon('menu'); ?><span>Visas kategorijas</span></button><div class="llg-catalog-menu"><?php echo function_exists('labaslietas_product_category_links') ? labaslietas_product_category_links(14) : '<a href="' . esc_url($shop) . '">Visas preces</a>'; ?></div></div>
                <div class="llg-main-nav"><?php echo labaslietas_nav_menu('primary', array('Sākums' => home_url('/'), 'Akcijas' => add_query_arg('onsale', '1', $shop), 'Jaunumi' => add_query_arg('orderby', 'date', $shop), 'Instrumenti' => home_url('/product-category/instrumenti/'), 'Dārzam' => home_url('/product-category/darza-tehnika/'), 'Mājai' => home_url('/product-category/saimniecibas-preces/'), 'Darbnīcai' => home_url('/product-category/servisa-aprikojums/'), 'Rezerves daļas' => home_url('/product-category/rezerves-dalas/'), 'Zīmoli' => $shop, 'Kontakti' => labaslietas_page_url('kontakti'), )); ?></div>
                <a class="llg-nav-sale-link" href="<?php echo esc_url(add_query_arg('onsale', '1', $shop)); ?>"><?php echo labaslietas_green_icon('percent'); ?><span>Akcijas</span></a>
            </div>
        </div>
        <?php echo function_exists('labaslietas_mini_cart_html') ? labaslietas_mini_cart_html() : ''; ?>
    </header>
    <?php return ob_get_clean();
}

function labaslietas_green_term_card_image($term) {
    $thumb_id = isset($term->term_id) ? absint(get_term_meta($term->term_id, 'thumbnail_id', true)) : 0;
    if ($thumb_id) {
        return wp_get_attachment_image($thumb_id, 'woocommerce_thumbnail', false, array('loading'=>'lazy'));
    }
    return function_exists('labaslietas_category_icon_html') ? labaslietas_category_icon_html($term) : '';
}

function labaslietas_green_categories($limit = 8) {
    if (!class_exists('WooCommerce')) { return ''; }
    $terms = get_terms(array('taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'number'=>absint($limit),'orderby'=>'count','order'=>'DESC'));
    if (is_wp_error($terms) || empty($terms)) { return ''; }
    if (function_exists('labaslietas_category_display_order')) { $terms = labaslietas_category_display_order($terms); }
    ob_start(); ?>
    <section class="llg-section llg-categories-section">
        <div class="llg-section-head"><div><span class="llg-eyebrow">Ātri atrodi vajadzīgo</span><h2>Preču kategorijas</h2></div><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Visas preces <?php echo labaslietas_green_icon('arrow'); ?></a></div>
        <div class="llg-category-grid">
            <?php foreach ($terms as $term) : ?>
                <a class="llg-category-card" href="<?php echo esc_url(get_term_link($term)); ?>">
                    <span class="llg-category-image"><?php echo labaslietas_green_term_card_image($term); ?></span>
                    <span class="llg-category-copy"><strong><?php echo esc_html($term->name); ?></strong><small><?php echo (int) $term->count; ?> preces</small></span>
                    <span class="llg-category-arrow">›</span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php return ob_get_clean();
}

function labaslietas_green_demo_item_by_sku($sku) {
    foreach (labaslietas_green_demo_catalog() as $item) {
        if (!empty($item['sku']) && (string) $item['sku'] === (string) $sku) { return $item; }
    }
    return null;
}

function labaslietas_green_product_image_html($product) {
    if (!$product || !is_a($product, 'WC_Product')) { return ''; }
    $sku = (string) $product->get_sku();
    $item = $sku !== '' ? labaslietas_green_demo_item_by_sku($sku) : null;
    if ($item && !empty($item['image'])) {
        $file = trailingslashit(get_stylesheet_directory()) . 'assets/demo-products/' . basename($item['image']);
        if (file_exists($file)) {
            $url = trailingslashit(get_stylesheet_directory_uri()) . 'assets/demo-products/' . rawurlencode(basename($item['image']));
            return '<img src="' . esc_url($url) . '" alt="' . esc_attr($product->get_name()) . '" loading="lazy" decoding="async">';
        }
    }
    return $product->get_image('woocommerce_thumbnail', array('loading'=>'lazy','decoding'=>'async'));
}

function labaslietas_green_product_card($product) {
    if (!$product || !is_a($product, 'WC_Product')) { return ''; }
    $id = $product->get_id();
    $link = get_permalink($id);
    $stock = $product->is_in_stock();
    $sku = $product->get_sku();
    $rating = (float) $product->get_average_rating();
    ob_start(); ?>
    <article class="llg-product-card product">
        <div class="llg-card-image-wrap">
            <?php echo function_exists('labaslietas_sale_badge') ? labaslietas_sale_badge($product) : ''; ?>
            <?php echo function_exists('labaslietas_product_quick_actions') ? labaslietas_product_quick_actions($id) : ''; ?>
            <a class="llg-card-image" href="<?php echo esc_url($link); ?>"><?php echo labaslietas_green_product_image_html($product); ?></a>
        </div>
        <div class="llg-card-body">
            <div class="llg-card-kicker"><span class="<?php echo $stock ? 'is-instock' : 'is-outstock'; ?>"><?php echo $stock ? 'Ir noliktavā' : 'Nav noliktavā'; ?></span><?php if ($sku) : ?><small>SKU: <?php echo esc_html($sku); ?></small><?php endif; ?></div>
            <h3><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($product->get_name()); ?></a></h3>
            <?php if ($rating > 0) : ?><div class="llg-card-rating"><span>★★★★★</span><small><?php echo esc_html(number_format($rating, 1)); ?></small></div><?php else : ?><div class="llg-card-rating llg-card-rating-empty"><span>★★★★★</span></div><?php endif; ?>
            <div class="llg-card-bottom"><div class="llg-card-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
            <?php if ($product->is_purchasable() && $stock) : ?><a href="<?php echo esc_url($product->add_to_cart_url()); ?>" data-quantity="1" data-product_id="<?php echo esc_attr($id); ?>" class="button add_to_cart_button ajax_add_to_cart llg-card-cart <?php echo esc_attr($product->get_type()); ?>">Pievienot grozam</a><?php else : ?><a class="button llg-card-cart llg-card-details" href="<?php echo esc_url($link); ?>">Skatīt preci</a><?php endif; ?></div>
        </div>
    </article>
    <?php return ob_get_clean();
}

function labaslietas_green_products_section($type, $title, $limit = 10, $eyebrow = '') {
    if (!class_exists('WooCommerce')) { return ''; }
    $args = array('post_type'=>'product','post_status'=>'publish','posts_per_page'=>absint($limit),'meta_query'=>WC()->query->get_meta_query(),'tax_query'=>WC()->query->get_tax_query(),'suppress_filters'=>true,'lang'=>'');
    if ($type === 'popular') { $args['meta_key'] = 'total_sales'; $args['orderby'] = array('meta_value_num'=>'DESC','date'=>'DESC'); }
    elseif ($type === 'sale') { $args['post__in'] = wc_get_product_ids_on_sale(); $args['orderby'] = 'date'; $args['order'] = 'DESC'; }
    elseif ($type === 'featured') { $args['tax_query'][] = array('taxonomy'=>'product_visibility','field'=>'name','terms'=>array('featured'),'operator'=>'IN'); $args['orderby']='date'; $args['order']='DESC'; }
    else { $args['orderby']='date'; $args['order']='DESC'; }
    $q = new WP_Query($args);
    if (!$q->have_posts()) { return ''; }
    $shop = wc_get_page_permalink('shop');
    ob_start(); ?>
    <section class="llg-section llg-products-section">
        <div class="llg-section-head"><div><?php if ($eyebrow) : ?><span class="llg-eyebrow"><?php echo esc_html($eyebrow); ?></span><?php endif; ?><h2><?php echo esc_html($title); ?></h2></div><a href="<?php echo esc_url($shop); ?>">Skatīt visas <?php echo labaslietas_green_icon('arrow'); ?></a></div>
        <div class="llg-product-grid">
            <?php while ($q->have_posts()) : $q->the_post(); global $product; echo labaslietas_green_product_card($product); endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php return ob_get_clean();
}

function labaslietas_green_home_shortcode() {
    $shop = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
    $categories = function_exists('labaslietas_product_category_links') ? labaslietas_product_category_links(12) : '';
    ob_start(); ?>
    <main class="llg-home llg-preview-home">
      <div class="labaslietas-container llg-preview-hero-wrap">
        <aside class="llg-preview-sidebar">
            <?php echo $categories ? $categories : '<a href="' . esc_url($shop) . '">Visas preces</a>'; ?>
        </aside>
        <section class="llg-preview-hero-grid llg-preview-hero-image-grid">
            <a class="llg-preview-hero-main llg-preview-hero-image-only" href="<?php echo esc_url($shop); ?>" aria-label="Dārza tehnika jaunai sezonai" style="background-image:url('<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/home-hero.jpg'); ?>');"></a>
            <div class="llg-preview-sidecards">
                <a class="llg-preview-sidecard llg-preview-sidecard-image-only" aria-label="Dārza tehnika — sezonas piedāvājumi" href="<?php echo esc_url(home_url('/product-category/darza-tehnika/')); ?>" style="background-image:url('<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/promo-garden-card.jpg'); ?>');"></a>
                <a class="llg-preview-sidecard llg-preview-sidecard-image-only" aria-label="Instrumenti profesionāļiem un mājai" href="<?php echo esc_url(home_url('/product-category/instrumenti/')); ?>" style="background-image:url('<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/promo-tools-card.jpg'); ?>');"></a>
            </div>
        </section>
      </div>
      <section class="labaslietas-container llg-benefit-strip llg-preview-benefits">
        <div><?php echo labaslietas_green_icon('truck'); ?><span><strong>Ātra piegāde</strong><small>Visa Latvijā ar Omniva, Unisend, LP</small></span></div>
        <div><?php echo labaslietas_green_icon('location'); ?><span><strong>Saņemšana Smiltenē</strong><small>Smiltene, LV-4729</small></span></div>
        <div><?php echo labaslietas_green_icon('cart'); ?><span><strong>Ērta apmaksa</strong><small>Ar bankas karti, pārskaitījumu vai skaidrā</small></span></div>
        <div><?php echo labaslietas_green_icon('shield'); ?><span><strong>Uzticams veikals</strong><small>Oriģinālas preces un garantija</small></span></div>
      </section>
      <div class="labaslietas-container"><?php echo labaslietas_green_products_section('featured', 'Populārākās preces', 12, 'Populāri'); ?></div>
      <div class="labaslietas-container"><?php echo labaslietas_green_products_section('sale', 'Akcijas', 8, 'Ietaupi'); ?></div>
      <div class="labaslietas-container"><?php echo labaslietas_green_products_section('recent', 'Jaunumi', 8, 'Jaunākie papildinājumi'); ?></div>
    </main>
    <?php return ob_get_clean();
}

function labaslietas_green_footer_html() {
    $facebook = labaslietas_get_theme_option('social_facebook', 'https://www.facebook.com/labas.lietas.33');
    $phone = trim((string) labaslietas_get_theme_option('phone', '+371 29 123 456'));
    $email = trim((string) labaslietas_get_theme_option('email', 'info@labaslietas.63.lv'));
    $hours = trim((string) labaslietas_get_theme_option('work_hours', 'P.-Pk. 9:00 - 18:00 / S. 9:00 - 14:00'));
    $address = 'Smiltene, Latvija, LV-4729';
    ob_start(); ?>
    <footer class="llg-footer llg-preview-footer llg-footer-fixed">
      <div class="labaslietas-container llg-footer-main">
        <div class="llg-footer-grid llg-footer-grid-fixed">
            <div class="llg-footer-brand llg-footer-brand-fixed"><?php echo labaslietas_logo_html(); ?><p>Praktiskas lietas mājai, darbam un dārzam. Piegāde visā Latvijā, saņemšana Smiltenē.</p><?php if ($facebook) : ?><a class="llg-footer-social" href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer"><?php echo labaslietas_green_icon('facebook'); ?> Sekot Facebook</a><?php endif; ?></div>
            <div class="llg-footer-col"><h4>Informācija</h4><?php echo labaslietas_nav_menu('footer', array('Par mums'=>labaslietas_page_url('par-mums'),'Piegāde un apmaksa'=>labaslietas_page_url('piegade-un-apmaksa'),'Atgriešana un garantija'=>labaslietas_page_url('atgriesana-un-garantija'),'Pirkšanas noteikumi'=>labaslietas_page_url('pirksanas-noteikumi'))); ?></div>
            <div class="llg-footer-col"><h4>Klientiem</h4><?php echo labaslietas_nav_menu('service', array('Kontakti'=>labaslietas_page_url('kontakti'),'Mans konts'=>(function_exists('wc_get_page_permalink')?wc_get_page_permalink('myaccount'):home_url('/my-account/')),'Grozs'=>(function_exists('wc_get_cart_url')?wc_get_cart_url():home_url('/cart/')),'Pasūtījuma izsekošana'=>labaslietas_page_url('track-your-order'))); ?></div>
            <div class="llg-footer-col"><h4>Kontakti</h4><p class="llg-contact-list"><span><?php echo labaslietas_green_icon('location'); ?> <?php echo esc_html($address); ?></span><?php if ($phone) : ?><span><?php echo labaslietas_green_icon('phone'); ?> <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/','',$phone)); ?>"><?php echo esc_html($phone); ?></a></span><?php endif; ?><?php if ($email) : ?><span><?php echo labaslietas_green_icon('mail'); ?> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></span><?php endif; ?><?php if ($hours) : ?><span><?php echo labaslietas_green_icon('clock'); ?> <?php echo esc_html($hours); ?></span><?php endif; ?></p></div>
            <div class="llg-footer-col"><h4>Piegāde un apmaksa</h4><div class="llg-footer-pills"><?php foreach (array_filter(array_map('trim', explode(',', labaslietas_get_theme_option('footer_delivery_partners','Unisend,Omniva,Latvijas Pasts,Kurjers')))) as $item) : ?><span><?php echo esc_html($item); ?></span><?php endforeach; ?></div><div class="llg-footer-pills llg-payment-pills"><?php foreach (array_filter(array_map('trim', explode(',', labaslietas_get_theme_option('footer_payment_methods','Skaidrā naudā,Bankas pārskaitījums,EveryPay / Swedbank')))) as $item) : ?><span><?php echo esc_html($item); ?></span><?php endforeach; ?></div></div>
        </div>
        <div class="llg-compare-row llg-compare-row-fixed"><div><strong>Cenu salīdzināšana</strong><small>Produktu XML plūsmas: KurPirkt.lv un Salidzini.lv</small></div><?php if (function_exists('labaslietas_compare_badges_html')) : ?><div class="labaslietas-compare-badges llg-compare-badges-fixed"><?php echo labaslietas_compare_badges_html(); ?></div><?php endif; ?></div>
      </div>
      <div class="llg-footer-bottom"><div class="labaslietas-container"><span>© <?php echo esc_html(date('Y')); ?> Labas Lietas. Visas tiesības aizsargātas.</span><span>Smiltene • Latvija</span></div></div>
    </footer>
    <?php return ob_get_clean();
}

// Replace the previous presentation layer while keeping all commerce/delivery logic intact.
add_shortcode('labaslietas_header', 'labaslietas_green_header_html');
add_shortcode('labaslietas_home', 'labaslietas_green_home_shortcode');
add_shortcode('labaslietas_footer', 'labaslietas_green_footer_html');
add_filter('body_class', function($classes){ $classes[] = 'labaslietas-green-v2'; return $classes; }, 99);

/** Demo catalogue ***********************************************************/
function labaslietas_green_demo_catalog() {
    return array(
        array('sku'=>'LL-DEMO-D20','name'=>'Akumulatora urbjmašīna 20V LABAS PRO D20','category'=>'Instrumenti','image'=>'drill.png','regular'=>'89.00','sale'=>'74.90','stock'=>18,'model'=>'D20','featured'=>1,'short'=>'Kompakta 20V urbjmašīna ikdienas montāžas un remonta darbiem.','description'=>'Oriģināla LABAS LIETAS demo prece dizaina un kataloga demonstrācijai. Komplektācija: urbjmašīna, akumulators un lādētājs. Pirms reālas tirdzniecības precizējiet tehniskos parametrus, cenu un pieejamību.'),
        array('sku'=>'LL-DEMO-C50','name'=>'Gaisa kompresors 50L 2.2kW LABAS PRO C50','category'=>'Servisa aprīkojums','image'=>'compressor.png','regular'=>'219.00','sale'=>'189.00','stock'=>7,'model'=>'C50','featured'=>1,'short'=>'50 litru kompresors darbnīcai un saimniecības darbiem.','description'=>'Oriģināla LABAS LIETAS demo prece. Paredzēta veikala izkārtojuma demonstrācijai; pirms pārdošanas aizvietojiet demonstrācijas specifikāciju ar faktiskajiem produkta datiem.'),
        array('sku'=>'LL-DEMO-W200','name'=>'Invertora metināšanas aparāts 200A LABAS PRO W200','category'=>'Specinstrumenti','image'=>'welder.png','regular'=>'129.00','sale'=>'109.00','stock'=>11,'model'=>'W200','featured'=>1,'short'=>'Kompakts invertora metināšanas aparāts mājas darbnīcai.','description'=>'LABAS LIETAS demo kataloga produkts ar oriģinālu nosaukumu un vizuālo materiālu.'),
        array('sku'=>'LL-DEMO-G3500','name'=>'Benzīna ģenerators 3.5kW LABAS PRO G3500','category'=>'Dārza tehnika','image'=>'generator.png','regular'=>'399.00','sale'=>'349.00','stock'=>5,'model'=>'G3500','featured'=>1,'short'=>'Pārvietojams benzīna ģenerators saimniecībai un izbraukuma darbiem.','description'=>'LABAS LIETAS demo kataloga produkts. Tehniskie parametri un pieejamība pirms reālas pārdošanas jāaizstāj ar faktiskajiem datiem.'),
        array('sku'=>'LL-DEMO-J3T','name'=>'Hidrauliskais domkrats 3T LABAS PRO J3T','category'=>'Servisa aprīkojums','image'=>'jack.png','regular'=>'79.00','sale'=>'64.90','stock'=>13,'model'=>'J3T','featured'=>0,'short'=>'Zema profila hidrauliskais domkrats garāžai un servisam.','description'=>'Oriģināla demo prece veikala izskata demonstrācijai.'),
        array('sku'=>'LL-DEMO-A1500','name'=>'Pneimatiskais triecienatslēga 1/2 LABAS PRO A1500','category'=>'Instrumenti','image'=>'impact-wrench.png','regular'=>'99.00','sale'=>'84.90','stock'=>16,'model'=>'A1500','featured'=>1,'short'=>'Pneimatiskais triecieninstruments riteņu un servisa darbiem.','description'=>'Oriģināla demo prece veikala izskata demonstrācijai.'),
        array('sku'=>'LL-DEMO-S108','name'=>'Instrumentu komplekts 108 gab. LABAS PRO S108','category'=>'Instrumenti','image'=>'tool-set.png','regular'=>'119.00','sale'=>'99.00','stock'=>22,'model'=>'S108','featured'=>1,'short'=>'Universāls instrumentu komplekts koferī darbnīcai un mājai.','description'=>'Oriģināla demo prece veikala izskata demonstrācijai.'),
        array('sku'=>'LL-DEMO-B26','name'=>'Lapu pūtējs 2-in-1 LABAS PRO B26','category'=>'Dārza tehnika','image'=>'blower.png','regular'=>'89.00','sale'=>'','stock'=>9,'model'=>'B26','featured'=>0,'short'=>'Kompakts lapu pūtējs dārza un pagalma kopšanai.','description'=>'Oriģināla demo prece veikala izskata demonstrācijai.'),
        array('sku'=>'LL-DEMO-BC52','name'=>'Benzīna krūmgriezis 52cc LABAS PRO BC52','category'=>'Dārza tehnika','image'=>'brushcutter.png','regular'=>'139.00','sale'=>'119.00','stock'=>8,'model'=>'BC52','featured'=>1,'short'=>'Krūmgriezis zāles, nezāļu un pagalma kopšanas darbiem.','description'=>'Oriģināla demo prece veikala izskata demonstrācijai.'),
        array('sku'=>'LL-DEMO-H10','name'=>'Pusautomātiskā auklas galva M10x1.25 LABAS PRO H10','category'=>'Rezerves daļas','image'=>'trimmer-head.png','regular'=>'19.90','sale'=>'15.90','stock'=>34,'model'=>'H10','featured'=>1,'short'=>'Universāla trimmera auklas galva ar M10x1.25 vītni.','description'=>'Oriģināla demo prece, iedvesmota no populāras dārza piederumu kategorijas. Pārbaudiet savietojamību ar konkrēto tehniku pirms pārdošanas.'),
        array('sku'=>'LL-DEMO-HALU','name'=>'Universālā alumīnija trimmera galva LABAS PRO HALU','category'=>'Rezerves daļas','image'=>'aluminum-head.png','regular'=>'24.90','sale'=>'19.90','stock'=>28,'model'=>'HALU','featured'=>0,'short'=>'Kompakta alumīnija trimmera galva intensīvākiem darbiem.','description'=>'Oriģināla demo prece veikala izskata demonstrācijai.'),
        array('sku'=>'LL-DEMO-L24','name'=>'Trimmera aukla 2.4 mm × 100 m LABAS PRO L24','category'=>'Rezerves daļas','image'=>'trimmer-line.png','regular'=>'18.90','sale'=>'14.90','stock'=>42,'model'=>'L24','featured'=>0,'short'=>'Izturīga trimmera aukla ikdienas zāles pļaušanas darbiem.','description'=>'Oriģināla demo prece veikala izskata demonstrācijai.'),
        array('sku'=>'LL-DEMO-CS85','name'=>'Zāģa ķēdes asināšanas iekārta LABAS PRO CS85','category'=>'Specinstrumenti','image'=>'chain-sharpener.png','regular'=>'59.00','sale'=>'49.00','stock'=>10,'model'=>'CS85','featured'=>0,'short'=>'Kompakta iekārta motorzāģa ķēžu apkopes darbiem.','description'=>'Oriģināla demo prece veikala izskata demonstrācijai.'),
        array('sku'=>'LL-DEMO-OP12','name'=>'Eļļas maiņas sūknis 12V LABAS PRO OP12','category'=>'Servisa aprīkojums','image'=>'oil-pump.png','regular'=>'29.90','sale'=>'24.90','stock'=>25,'model'=>'OP12','featured'=>0,'short'=>'12V sūknis eļļas pārsūknēšanas un apkopes darbiem.','description'=>'Oriģināla demo prece veikala izskata demonstrācijai.'),
    );
}

function labaslietas_green_demo_attachment($filename, $title) {
    $existing = get_posts(array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_key'       => '_labaslietas_demo_asset',
        'meta_value'     => $filename,
    ));
    if ($existing) { return absint($existing[0]); }

    $source = trailingslashit(get_stylesheet_directory()) . 'assets/demo-products/' . basename($filename);
    if (!file_exists($source)) { return 0; }

    $contents = @file_get_contents($source);
    if ($contents === false) { return 0; }

    $bits = wp_upload_bits('labaslietas-' . basename($filename), null, $contents);
    if (!empty($bits['error'])) { return 0; }

    $filetype = wp_check_filetype($bits['file']);
    $attachment_id = wp_insert_attachment(array(
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_text_field($title),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ), $bits['file']);
    if (is_wp_error($attachment_id) || !$attachment_id) { return 0; }

    // Avoid wp_generate_attachment_metadata() here. On low-resource shared hosting
    // Imagick can exceed the 30-second PHP limit while creating many WooCommerce
    // sub-sizes during demo import. Basic metadata is enough for the original image.
    $size = @getimagesize($bits['file']);
    if (is_array($size) && !empty($size[0]) && !empty($size[1])) {
        $meta = array(
            'width'  => absint($size[0]),
            'height' => absint($size[1]),
            'file'   => function_exists('_wp_relative_upload_path') ? _wp_relative_upload_path($bits['file']) : basename($bits['file']),
            'sizes'  => array(),
        );
        wp_update_attachment_metadata($attachment_id, $meta);
    }
    update_post_meta($attachment_id, '_labaslietas_demo_asset', $filename);
    return absint($attachment_id);
}

function labaslietas_green_find_demo_product_id($item) {
    $sku = isset($item['sku']) ? sanitize_text_field((string) $item['sku']) : '';
    if ($sku !== '') {
        $by_sku = wc_get_product_id_by_sku($sku);
        if ($by_sku) { return absint($by_sku); }
        $by_key = get_posts(array(
            'post_type'      => 'product',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_key'       => '_labaslietas_demo_key',
            'meta_value'     => $sku,
        ));
        if (!empty($by_key)) { return absint($by_key[0]); }
    }
    return 0;
}

function labaslietas_green_seed_demo_catalog() {
    if (!class_exists('WooCommerce') || !class_exists('WC_Product_Simple')) { return new WP_Error('woocommerce_missing', 'WooCommerce nav aktīvs.'); }
    $category_ids = array();
    foreach (array('Instrumenti','Specinstrumenti','Servisa aprīkojums','Dārza tehnika','Rezerves daļas','Saimniecības preces') as $category) {
        $term = term_exists($category, 'product_cat');
        if (!$term) { $term = wp_insert_term($category, 'product_cat'); }
        if (!is_wp_error($term)) { $category_ids[$category] = absint(is_array($term) ? $term['term_id'] : $term); }
    }
    $created = 0; $updated = 0;
    foreach (labaslietas_green_demo_catalog() as $item) {
        $demo_key = sanitize_text_field((string) $item['sku']);
        $id = labaslietas_green_find_demo_product_id($item);
        $product = $id ? wc_get_product($id) : new WC_Product_Simple();
        if (!$product || !is_a($product, 'WC_Product')) { continue; }
        $is_new = !$id;
        $product->set_name($item['name']);
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        if ($demo_key !== '') {
            try {
                if (!$id || $product->get_sku() !== $demo_key) {
                    $product->set_sku($demo_key);
                }
            } catch (Exception $e) {
                if (!$id || $product->get_sku() !== $demo_key) { continue; }
            }
        }
        $product->set_regular_price($item['regular']);
        $product->set_sale_price($item['sale']);
        $product->set_manage_stock(true);
        $product->set_stock_quantity(absint($item['stock']));
        $product->set_stock_status('instock');
        $product->set_short_description($item['short']);
        $product->set_description($item['description']);
        $product->set_featured(!empty($item['featured']));
        if (isset($category_ids[$item['category']])) { $product->set_category_ids(array($category_ids[$item['category']])); }
        $image_id = labaslietas_green_demo_attachment($item['image'], $item['name']);
        if ($image_id) { $product->set_image_id($image_id); }
        try {
            $saved_id = $product->save();
        } catch (Exception $e) {
            continue;
        }
        if (!$saved_id) { continue; }
        update_post_meta($saved_id, '_labaslietas_demo_product', '1');
        update_post_meta($saved_id, '_labaslietas_demo_key', $demo_key);
        update_post_meta($saved_id, '_labaslietas_feed_brand', 'LABAS PRO');
        update_post_meta($saved_id, '_labaslietas_feed_model', $item['model']);
        update_post_meta($saved_id, '_labaslietas_feed_mpn', $item['sku']);
        update_post_meta($saved_id, '_labaslietas_feed_exclude', 'yes');
        $is_new ? $created++ : $updated++;
        if ($image_id && isset($category_ids[$item['category']]) && !get_term_meta($category_ids[$item['category']], 'thumbnail_id', true)) {
            update_term_meta($category_ids[$item['category']], 'thumbnail_id', $image_id);
        }
    }
    update_option('labaslietas_green_demo_seeded', LABASLIETAS_GREEN_V2, false);
    if (function_exists('labaslietas_compare_feed_invalidate')) { labaslietas_compare_feed_invalidate(); }
    return array('created'=>$created,'updated'=>$updated);
}

function labaslietas_green_remove_demo_catalog() {
    $ids = get_posts(array('post_type'=>'product','post_status'=>'any','posts_per_page'=>-1,'fields'=>'ids','meta_key'=>'_labaslietas_demo_product','meta_value'=>'1'));
    $deleted = 0;
    foreach ($ids as $id) { if (wp_delete_post($id, true)) { $deleted++; } }
    delete_option('labaslietas_green_demo_seeded');
    if (function_exists('labaslietas_compare_feed_invalidate')) { labaslietas_compare_feed_invalidate(); }
    return $deleted;
}

function labaslietas_green_migrate_v2() {
    $done = get_option('labaslietas_green_version', '');
    if ($done === LABASLIETAS_GREEN_V2) { return; }
    $opts = get_option('labaslietas_theme_options', array());
    if (!is_array($opts)) { $opts = array(); }
    $opts['accent_color'] = '#2f8b49';
    $opts['dark_header'] = '0';
    $opts['show_topbar'] = '1';
    $opts['sticky_header'] = '1';
    $opts['logo_id'] = '';
    $opts['logo_width'] = '300';
    $opts['logo_height'] = '86';
    $opts['hero_title'] = 'Praktiskas lietas darbam, dārzam un mājai';
    $opts['hero_subtitle'] = 'Instrumenti, tehnika un noderīgi piederumi ar ērtu piegādi visā Latvijā un saņemšanu Smiltenē.';
    $opts['hero_button'] = 'Apskatīt piedāvājumu';
    update_option('labaslietas_theme_options', $opts, false);
    remove_theme_mod('custom_logo');
    update_option('labaslietas_green_version', LABASLIETAS_GREEN_V2, false);
}
add_action('after_switch_theme', 'labaslietas_green_migrate_v2', 20);
add_action('init', 'labaslietas_green_migrate_v2', 99);
add_action('admin_init', function(){
    // Keep admin loads lightweight. Demo catalogue import is manual only.
    labaslietas_green_migrate_v2();
}, 30);


/** 2.3.4 Starter Setup helpers ************************************************/
function labaslietas_green_sync_languages_lv_en() {
    if (!function_exists('PLL')) { return array('configured'=>false, 'reason'=>'polylang_inactive'); }
    $pll = PLL();
    if (!is_object($pll) || empty($pll->model) || !is_object($pll->model)) { return array('configured'=>false, 'reason'=>'model_unavailable'); }

    $language_model = isset($pll->model->languages) && is_object($pll->model->languages) ? $pll->model->languages : $pll->model;
    $modern = method_exists($language_model, 'get_list') && method_exists($language_model, 'add');
    $legacy = is_callable(array($pll->model, 'get_languages_list')) && is_callable(array($pll->model, 'add_language'));
    if (!$modern && !$legacy) { return array('configured'=>false, 'reason'=>'api_unavailable'); }

    try { $list = $modern ? $language_model->get_list() : $pll->model->get_languages_list(); }
    catch (Throwable $e) { return array('configured'=>false, 'reason'=>'list_failed'); }

    $defs = array(
        'lv' => array('name'=>'Latviešu','locale'=>'lv','flag'=>'lv','term_group'=>0),
        'en' => array('name'=>'English','locale'=>'en_GB','flag'=>'gb','term_group'=>1),
    );
    $by_slug = array();
    foreach ((array)$list as $lang) {
        $slug = sanitize_key(is_object($lang) ? (isset($lang->slug) ? $lang->slug : '') : (isset($lang['slug']) ? $lang['slug'] : ''));
        if ($slug) { $by_slug[$slug] = $lang; }
    }

    foreach ($defs as $slug=>$def) {
        if (isset($by_slug[$slug])) { continue; }
        $data = array('name'=>$def['name'],'slug'=>$slug,'locale'=>$def['locale'],'rtl'=>false,'flag'=>$def['flag'],'no_default_cat'=>false,'term_group'=>$def['term_group']);
        try { $created = $modern ? $language_model->add($data) : $pll->model->add_language($data); }
        catch (Throwable $e) { $created = false; }
        if ($created && !is_wp_error($created)) { $by_slug[$slug] = $created; }
    }

    // Latvian is the project default before any other languages are removed.
    try {
        if ($modern && method_exists($language_model, 'update_default')) { $language_model->update_default('lv'); }
        elseif (is_callable(array($pll->model, 'update_default_lang'))) { $pll->model->update_default_lang('lv'); }
    } catch (Throwable $e) {}

    // Refresh once, then use Polylang's own deletion API so translation/menu state is cleaned correctly.
    try { $list = $modern ? $language_model->get_list() : $pll->model->get_languages_list(); } catch (Throwable $e) { $list = array(); }
    $removed = array();
    foreach ((array)$list as $lang) {
        $slug = sanitize_key(is_object($lang) ? (isset($lang->slug) ? $lang->slug : '') : '');
        $term_id = absint(is_object($lang) && isset($lang->term_id) ? $lang->term_id : 0);
        if (!$slug || in_array($slug, array('lv','en'), true) || !$term_id) { continue; }
        try {
            $ok = $modern && method_exists($language_model, 'delete') ? $language_model->delete($term_id) : (is_callable(array($pll->model,'delete_language')) ? $pll->model->delete_language($term_id) : false);
            if ($ok) { $removed[] = $slug; }
        } catch (Throwable $e) {}
    }

    $opts = get_option('polylang', array());
    if (!is_array($opts)) { $opts = array(); }
    $opts['default_lang'] = 'lv';
    $opts['hide_default'] = true;
    $opts['media_support'] = false;
    if (!empty($opts['nav_menus']) && is_array($opts['nav_menus'])) {
        foreach ($opts['nav_menus'] as $theme_key=>$locations) {
            if (!is_array($locations)) { continue; }
            foreach ($locations as $location=>$map) {
                if (is_array($map)) {
                    $opts['nav_menus'][$theme_key][$location] = array_intersect_key($map, array('lv'=>true,'en'=>true));
                }
            }
        }
    }
    update_option('polylang', $opts, false);
    update_option('WPLANG', 'lv', false);
    update_option('wp_theme_language_switcher_enabled', '1', false);
    update_option('wp_theme_demo_language_bar_enabled', '1', false);
    update_option('wp_theme_demo_polylang_setup_version', 'labaslietas-2.3.4', false);
    update_option('labaslietas_lv_en_sync_234', gmdate('c'), false);
    return array('configured'=>true,'removed'=>$removed);
}

function labaslietas_green_cleanup_parent_demo() {
    if (!class_exists('WooCommerce')) { return 0; }
    $ids = get_posts(array(
        'post_type'=>'product','post_status'=>'any','posts_per_page'=>-1,'fields'=>'ids',
        'meta_key'=>'_wpbb_child_woo_demo_product','meta_value'=>'1',
    ));
    $deleted = 0;
    foreach ($ids as $id) {
        if (get_post_meta($id, '_labaslietas_demo_product', true) === '1') { continue; }
        if (wp_delete_post($id, true)) { $deleted++; }
    }
    foreach (array('accessories','apparel','bundles','home-living','office','tech') as $slug) {
        $term = get_term_by('slug', $slug, 'product_cat');
        if ($term && !is_wp_error($term) && (int)$term->count === 0) { wp_delete_term($term->term_id, 'product_cat'); }
    }
    return $deleted;
}

function labaslietas_green_seed_demo_products_lightweight() {
    if (!class_exists('WooCommerce') || !class_exists('WC_Product_Simple')) { return array('created'=>0,'updated'=>0); }
    $category_ids = array();
    foreach (array('Instrumenti','Specinstrumenti','Servisa aprīkojums','Dārza tehnika','Rezerves daļas','Saimniecības preces') as $category) {
        $term = term_exists($category, 'product_cat');
        if (!$term) { $term = wp_insert_term($category, 'product_cat'); }
        if (!is_wp_error($term)) {
            $tid = absint(is_array($term) ? $term['term_id'] : $term);
            $category_ids[$category] = $tid;
            if (function_exists('pll_set_term_language')) { @pll_set_term_language($tid, 'lv'); }
        }
    }
    $created=0; $updated=0;
    foreach (labaslietas_green_demo_catalog() as $item) {
        $sku = sanitize_text_field((string)$item['sku']);
        $id = wc_get_product_id_by_sku($sku);
        $is_new = !$id;
        if (!$id) {
            $p = new WC_Product_Simple();
            try { $p->set_sku($sku); } catch (Exception $e) { continue; }
        } else { $p = wc_get_product($id); }
        if (!$p || !is_a($p,'WC_Product')) { continue; }
        $p->set_name($item['name']);
        $p->set_status('publish');
        $p->set_catalog_visibility('visible');
        $p->set_regular_price($item['regular']);
        $p->set_sale_price($item['sale']);
        $p->set_manage_stock(true);
        $p->set_stock_quantity(absint($item['stock']));
        $p->set_stock_status('instock');
        $p->set_short_description($item['short']);
        $p->set_description($item['description']);
        $p->set_featured(!empty($item['featured']));
        if (isset($category_ids[$item['category']])) { $p->set_category_ids(array($category_ids[$item['category']])); }
        // Deliberately do not create/update attachments here. Demo cards read the bundled theme image by SKU.
        $p->set_image_id(0);
        $p->set_gallery_image_ids(array());
        try { $saved_id=$p->save(); } catch (Exception $e) { continue; }
        if (!$saved_id) { continue; }
        update_post_meta($saved_id,'_labaslietas_demo_product','1');
        update_post_meta($saved_id,'_labaslietas_demo_key',$sku);
        update_post_meta($saved_id,'_labaslietas_feed_brand','LABAS PRO');
        update_post_meta($saved_id,'_labaslietas_feed_model',$item['model']);
        update_post_meta($saved_id,'_labaslietas_feed_mpn',$sku);
        update_post_meta($saved_id,'_labaslietas_feed_exclude','yes');
        if (function_exists('pll_set_post_language')) { @pll_set_post_language($saved_id,'lv'); }
        if ($is_new) { $created++; } else { $updated++; }
    }
    update_option('labaslietas_light_demo_seed_234', gmdate('c'), false);
    if (function_exists('wc_delete_product_transients')) { wc_delete_product_transients(); }
    return array('created'=>$created,'updated'=>$updated);
}

add_action('admin_menu', function(){
    add_theme_page('LABAS LIETAS Demo', 'LABAS LIETAS Demo', 'manage_options', 'labaslietas-green-demo', 'labaslietas_green_demo_admin_page');
}, 30);

function labaslietas_green_demo_admin_page() {
    if (!current_user_can('manage_options')) { return; }
    $notice = isset($_GET['ll_demo_notice']) ? sanitize_text_field(wp_unslash($_GET['ll_demo_notice'])) : '';
    ?>
    <div class="wrap"><h1>LABAS LIETAS Green Storefront</h1>
      <?php if ($notice) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html($notice); ?></p></div><?php endif; ?>
      <p>Theme v<?php echo esc_html(LABASLIETAS_GREEN_V2); ?> — zaļš WooCommerce veikala dizains ar oriģinālu demo katalogu.</p><p><strong>Drošības labojums:</strong> demo katalogs vairs netiek importēts automātiski wp-admin ielādes laikā. Importu palaidiet tikai ar zemāk esošo pogu.</p>
      <div class="card" style="max-width:900px"><h2>Demo katalogs</h2><p>Demo preces ir oriģinālas un nav kopētas no citiem veikaliem. Tās ir redzamas veikalā, bet drošības dēļ ir izslēgtas no KurPirkt.lv un Salidzini.lv XML plūsmām, līdz jūs apstiprināt reālu cenu, noliktavas atlikumu un produkta parametrus.</p>
      <p><a class="button button-primary" href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=labaslietas_green_seed_demo'),'labaslietas_green_seed_demo')); ?>">Uzstādīt / atjaunot demo katalogu</a> <a class="button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=labaslietas_green_remove_demo'),'labaslietas_green_remove_demo')); ?>" onclick="return confirm('Dzēst visas LABAS LIETAS demo preces?');">Dzēst demo preces</a></p></div>
      <div class="card" style="max-width:900px"><h2>KurPirkt.lv un Salidzini.lv</h2><p><strong>KurPirkt.lv XML:</strong> <code><?php echo esc_html(home_url('/kurpirkt.xml')); ?></code><br><strong>Salidzini.lv XML:</strong> <code><?php echo esc_html(home_url('/salidzini.xml')); ?></code></p><p>Theme ģenerē un atjauno XML plūsmas un rāda sadarbības banerus kājenē. Lai portāli sāktu importēt preces, veikals vēl jāreģistrē pie katra pakalpojuma ar jūsu īstajiem uzņēmuma rekvizītiem un jāiesniedz attiecīgā XML saite.</p></div>
    </div><?php
}

add_action('admin_post_labaslietas_green_seed_demo', function(){
    if (!current_user_can('manage_options')) { wp_die('Nav tiesību.'); }
    check_admin_referer('labaslietas_green_seed_demo');
    $result = labaslietas_green_seed_demo_catalog();
    $message = is_wp_error($result) ? $result->get_error_message() : sprintf('Demo katalogs atjaunots: %d jaunas, %d atjaunotas preces.', $result['created'], $result['updated']);
    wp_safe_redirect(add_query_arg(array('page'=>'labaslietas-green-demo','ll_demo_notice'=>$message), admin_url('themes.php'))); exit;
});
add_action('admin_post_labaslietas_green_remove_demo', function(){
    if (!current_user_can('manage_options')) { wp_die('Nav tiesību.'); }
    check_admin_referer('labaslietas_green_remove_demo');
    $deleted = labaslietas_green_remove_demo_catalog();
    wp_safe_redirect(add_query_arg(array('page'=>'labaslietas-green-demo','ll_demo_notice'=>sprintf('Dzēstas %d demo preces.', $deleted)), admin_url('themes.php'))); exit;
});
