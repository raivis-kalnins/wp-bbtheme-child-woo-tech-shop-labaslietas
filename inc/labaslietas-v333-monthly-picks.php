<?php
/**
 * Labas Lietas 3.0.33
 * Monthly "3Lietas" homepage promotion.
 *
 * The promotion replaces the large seasonal homepage advert only during the
 * configured month. Three WooCommerce products can be changed from
 * Appearance -> 3Lietas mēneša piedāvājums. The public block only promotes
 * products that currently have an active WooCommerce sale price.
 */
defined('ABSPATH') || exit;

if (!defined('LABASLIETAS_MONTHLY_PICKS_OPTION')) {
    define('LABASLIETAS_MONTHLY_PICKS_OPTION', 'labaslietas_monthly_picks_v333');
}

function labaslietas_v333_monthly_current_month() {
    return wp_date('Y-m', current_time('timestamp'));
}

function labaslietas_v333_month_name($month, $case_name = 'dative', $is_en = false) {
    $number = (int) substr((string) $month, 5, 2);
    if ($number < 1 || $number > 12) { $number = (int) wp_date('n', current_time('timestamp')); }

    if ($is_en) {
        $names = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
        return $names[$number];
    }

    if ($case_name === 'locative') {
        $names = array(1=>'janvari',2=>'februari',3=>'marta',4=>'aprili',5=>'maija',6=>'junija',7=>'julija',8=>'augusta',9=>'septembri',10=>'oktobri',11=>'novembri',12=>'decembri');
    } else {
        $names = array(1=>'janvarim',2=>'februarim',3=>'martam',4=>'aprilim',5=>'maijam',6=>'junijam',7=>'julijam',8=>'augustam',9=>'septembrim',10=>'oktobrim',11=>'novembrim',12=>'decembrim');
    }

    // Restore Latvian diacritics without relying on locale packages.
    $replace = array(
        'janvari'=>'janvārī','februari'=>'februārī','marta'=>'martā','aprili'=>'aprīlī','maija'=>'maijā','junija'=>'jūnijā','julija'=>'jūlijā','augusta'=>'augustā','septembri'=>'septembrī','oktobri'=>'oktobrī','novembri'=>'novembrī','decembri'=>'decembrī',
        'janvarim'=>'janvārim','februarim'=>'februārim','martam'=>'martam','aprilim'=>'aprīlim','maijam'=>'maijam','junijam'=>'jūnijam','julijam'=>'jūlijam','augustam'=>'augustam','septembrim'=>'septembrim','oktobrim'=>'oktobrim','novembrim'=>'novembrim','decembrim'=>'decembrim',
    );
    return isset($replace[$names[$number]]) ? $replace[$names[$number]] : $names[$number];
}

function labaslietas_v333_monthly_default_ids() {
    $ids = array();
    if (!function_exists('wc_get_product_id_by_sku')) { return $ids; }

    $preferred = array('LL-DEMO-H10','LL-DEMO-G3500','LL-DEMO-A1500');
    foreach ($preferred as $sku) {
        $id = absint(wc_get_product_id_by_sku($sku));
        if ($id && !in_array($id, $ids, true)) { $ids[] = $id; }
    }

    if (count($ids) < 3 && function_exists('wc_get_product_ids_on_sale')) {
        foreach ((array) wc_get_product_ids_on_sale() as $id) {
            $id = absint($id);
            if ($id && !in_array($id, $ids, true)) { $ids[] = $id; }
            if (count($ids) >= 3) { break; }
        }
    }
    return array_slice($ids, 0, 3);
}

function labaslietas_v333_monthly_defaults() {
    $ids = labaslietas_v333_monthly_default_ids();
    return array(
        'enabled' => '1',
        'month' => labaslietas_v333_monthly_current_month(),
        'custom_title' => '',
        'product_1' => isset($ids[0]) ? (string) absint($ids[0]) : '',
        'product_2' => isset($ids[1]) ? (string) absint($ids[1]) : '',
        'product_3' => isset($ids[2]) ? (string) absint($ids[2]) : '',
    );
}

function labaslietas_v333_monthly_get_settings() {
    $saved = get_option(LABASLIETAS_MONTHLY_PICKS_OPTION, array());
    if (!is_array($saved)) { $saved = array(); }
    return wp_parse_args($saved, labaslietas_v333_monthly_defaults());
}

function labaslietas_v333_monthly_seed_once() {
    if (!class_exists('WooCommerce')) { return; }
    if (get_option(LABASLIETAS_MONTHLY_PICKS_OPTION, false) !== false) { return; }
    update_option(LABASLIETAS_MONTHLY_PICKS_OPTION, labaslietas_v333_monthly_defaults(), false);
}
add_action('init', 'labaslietas_v333_monthly_seed_once', 40);

function labaslietas_v333_monthly_sanitize($input) {
    $input = is_array($input) ? $input : array();
    $out = array();
    $out['enabled'] = !empty($input['enabled']) ? '1' : '0';

    $month = isset($input['month']) ? sanitize_text_field(wp_unslash($input['month'])) : '';
    if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])$/', $month)) {
        $month = labaslietas_v333_monthly_current_month();
    }
    $out['month'] = $month;
    $out['custom_title'] = isset($input['custom_title']) ? sanitize_text_field(wp_unslash($input['custom_title'])) : '';

    $used = array();
    for ($i = 1; $i <= 3; $i++) {
        $key = 'product_' . $i;
        $id = isset($input[$key]) ? absint($input[$key]) : 0;
        if ($id && in_array($id, $used, true)) { $id = 0; }
        if ($id && get_post_type($id) !== 'product') { $id = 0; }
        if ($id) { $used[] = $id; }
        $out[$key] = $id ? (string) $id : '';
    }
    return $out;
}

add_action('admin_init', function() {
    register_setting('labaslietas_monthly_picks_group', LABASLIETAS_MONTHLY_PICKS_OPTION, array('sanitize_callback'=>'labaslietas_v333_monthly_sanitize'));
});

add_action('admin_menu', function() {
    add_theme_page('3Lietas mēneša piedāvājums', '3Lietas mēneša piedāvājums', 'manage_options', 'labaslietas-monthly-picks', 'labaslietas_v333_monthly_admin_page');
}, 30);

function labaslietas_v333_monthly_product_select($key, $label, $settings) {
    $id = !empty($settings[$key]) ? absint($settings[$key]) : 0;
    $product = $id && function_exists('wc_get_product') ? wc_get_product($id) : false;
    echo '<tr><th scope="row"><label for="ll-monthly-' . esc_attr($key) . '">' . esc_html($label) . '</label></th><td>';
    echo '<select id="ll-monthly-' . esc_attr($key) . '" class="wc-product-search" style="width:460px;max-width:100%;" name="' . esc_attr(LABASLIETAS_MONTHLY_PICKS_OPTION) . '[' . esc_attr($key) . ']" data-placeholder="Meklēt preci..." data-action="woocommerce_json_search_products" data-allow_clear="true">';
    if ($product) {
        echo '<option value="' . esc_attr($id) . '" selected="selected">' . esc_html(wp_strip_all_tags($product->get_formatted_name())) . '</option>';
    }
    echo '</select>';
    if ($product) {
        $edit = get_edit_post_link($id, '');
        if ($product->is_on_sale()) {
            echo '<p class="description" style="color:#1e7d3c"><strong>Aktīva atlaide.</strong> ' . wp_kses_post($product->get_price_html()) . '</p>';
        } else {
            echo '<p class="description" style="color:#a55b00"><strong>Brīdinājums:</strong> šai precei pašlaik nav aktīvas WooCommerce atlaides. Publiskais 3Lietas bloks to aizstāj ar citu akcijas preci, līdz atlaide būs aktīva.</p>';
        }
        if ($edit) {
            echo '<p><a class="button button-small" href="' . esc_url($edit) . '">Atvērt preci un iestatīt akcijas cenu / datumus</a></p>';
        }
    }
    echo '</td></tr>';
}

function labaslietas_v333_monthly_admin_page() {
    if (!current_user_can('manage_options')) { return; }
    $settings = labaslietas_v333_monthly_get_settings();
    $preview_title = !empty($settings['custom_title']) ? $settings['custom_title'] : '3Lietas ' . labaslietas_v333_month_name($settings['month'], 'dative', false) . ' iesaka';
    ?>
    <div class="wrap">
      <h1>3Lietas mēneša piedāvājums</h1>
      <p>Šī reklāmas vieta aizstāj sākumlapas "Dārza tehnika jaunai sezonai" logu tikai izvēlētajā mēnesī. Izvēlies 3 preces; atlaides cenu un akcijas sākuma/beigu datumus iestati pašas WooCommerce preces rediģēšanā.</p>
      <?php settings_errors(); ?>
      <form method="post" action="options.php">
        <?php settings_fields('labaslietas_monthly_picks_group'); ?>
        <table class="form-table" role="presentation">
          <tr><th scope="row">Ieslēgt</th><td><label><input type="checkbox" name="<?php echo esc_attr(LABASLIETAS_MONTHLY_PICKS_OPTION); ?>[enabled]" value="1" <?php checked($settings['enabled'], '1'); ?>> Rādīt mēneša 3Lietas reklāmu sākumlapā</label></td></tr>
          <tr><th scope="row"><label for="ll-monthly-month">Mēnesis</label></th><td><input id="ll-monthly-month" type="month" name="<?php echo esc_attr(LABASLIETAS_MONTHLY_PICKS_OPTION); ?>[month]" value="<?php echo esc_attr($settings['month']); ?>"><p class="description">Bloks automātiski paslēpsies, kad šis mēnesis būs beidzies.</p></td></tr>
          <tr><th scope="row"><label for="ll-monthly-title">Virsraksts</label></th><td><input id="ll-monthly-title" class="regular-text" type="text" name="<?php echo esc_attr(LABASLIETAS_MONTHLY_PICKS_OPTION); ?>[custom_title]" value="<?php echo esc_attr($settings['custom_title']); ?>" placeholder="<?php echo esc_attr($preview_title); ?>"><p class="description">Atstāj tukšu, lai virsraksts mainītos automātiski, piem. "3Lietas septembrim iesaka".</p></td></tr>
          <?php labaslietas_v333_monthly_product_select('product_1', 'Prece 1', $settings); ?>
          <?php labaslietas_v333_monthly_product_select('product_2', 'Prece 2', $settings); ?>
          <?php labaslietas_v333_monthly_product_select('product_3', 'Prece 3', $settings); ?>
        </table>
        <?php submit_button('Saglabāt mēneša piedāvājumu'); ?>
      </form>
    </div>
    <?php
}

add_action('admin_enqueue_scripts', function($hook) {
    if ($hook !== 'appearance_page_labaslietas-monthly-picks') { return; }
    if (wp_script_is('wc-enhanced-select', 'registered')) { wp_enqueue_script('wc-enhanced-select'); }
    if (wp_style_is('woocommerce_admin_styles', 'registered')) { wp_enqueue_style('woocommerce_admin_styles'); }
});

function labaslietas_v333_monthly_selected_products($settings) {
    $products = array();
    $used = array();
    if (!function_exists('wc_get_product')) { return $products; }

    for ($i = 1; $i <= 3; $i++) {
        $id = !empty($settings['product_' . $i]) ? absint($settings['product_' . $i]) : 0;
        if (!$id || in_array($id, $used, true)) { continue; }
        $product = wc_get_product($id);
        if (!$product || $product->get_status() !== 'publish' || !$product->is_on_sale()) { continue; }
        $products[] = $product;
        $used[] = $id;
    }

    if (count($products) < 3 && function_exists('wc_get_product_ids_on_sale')) {
        foreach ((array) wc_get_product_ids_on_sale() as $id) {
            $id = absint($id);
            if (!$id || in_array($id, $used, true)) { continue; }
            $product = wc_get_product($id);
            if (!$product || $product->get_status() !== 'publish' || !$product->is_on_sale()) { continue; }
            $products[] = $product;
            $used[] = $id;
            if (count($products) >= 3) { break; }
        }
    }

    return array_slice($products, 0, 3);
}

function labaslietas_v333_discount_percent($product) {
    if (!$product || !is_a($product, 'WC_Product') || $product->is_type('variable')) { return 0; }
    $regular = (float) $product->get_regular_price();
    $sale = (float) $product->get_sale_price();
    if ($regular <= 0 || $sale <= 0 || $sale >= $regular) { return 0; }
    return max(1, (int) round((($regular - $sale) / $regular) * 100));
}

function labaslietas_v333_monthly_promo_html($is_en = false) {
    if (!class_exists('WooCommerce')) { return ''; }
    $settings = labaslietas_v333_monthly_get_settings();
    if (empty($settings['enabled']) || $settings['enabled'] !== '1') { return ''; }
    if ((string) $settings['month'] !== labaslietas_v333_monthly_current_month()) { return ''; }

    $products = labaslietas_v333_monthly_selected_products($settings);
    if (count($products) < 1) { return ''; }

    $month_dative = labaslietas_v333_month_name($settings['month'], 'dative', $is_en);
    $month_locative = labaslietas_v333_month_name($settings['month'], 'locative', $is_en);
    $title = !empty($settings['custom_title']) ? $settings['custom_title'] : ($is_en ? ('3 things we recommend for ' . $month_dative) : ('3Lietas ' . $month_dative . ' iesaka'));
    $eyebrow = $is_en ? ('Only in ' . $month_locative) : ('Tikai ' . $month_locative);
    $sub = $is_en ? 'Three selected products with a special price for this month only.' : 'Trīs izvēlētas preces ar īpašu cenu tikai šajā mēnesī.';

    ob_start(); ?>
    <section class="ll33-monthly-promo" aria-label="<?php echo esc_attr($title); ?>">
      <div class="ll33-monthly-head">
        <div>
          <span class="ll33-monthly-kicker"><?php echo esc_html($eyebrow); ?></span>
          <h1><?php echo esc_html($title); ?></h1>
          <p><?php echo esc_html($sub); ?></p>
        </div>
        <span class="ll33-monthly-mark" aria-hidden="true">3</span>
      </div>
      <div class="ll33-monthly-grid">
        <?php foreach ($products as $product) :
            $id = $product->get_id();
            $link = get_permalink($id);
            $name = function_exists('labaslietas_green_demo_name_309') ? labaslietas_green_demo_name_309($product) : $product->get_name();
            $discount = labaslietas_v333_discount_percent($product);
            ?>
          <article class="ll33-monthly-card product">
            <a class="ll33-monthly-image" href="<?php echo esc_url($link); ?>" aria-label="<?php echo esc_attr($name); ?>">
              <?php echo function_exists('labaslietas_green_product_image_html') ? labaslietas_green_product_image_html($product) : $product->get_image('woocommerce_thumbnail'); ?>
            </a>
            <div class="ll33-monthly-copy">
              <div class="ll33-monthly-sale"><?php echo $discount ? esc_html('-' . $discount . '%') : esc_html($is_en ? 'SALE' : 'AKCIJA'); ?></div>
              <h2><a href="<?php echo esc_url($link); ?>"><?php echo esc_html($name); ?></a></h2>
              <div class="ll33-monthly-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
              <a class="ll33-monthly-link" href="<?php echo esc_url($link); ?>"><?php echo esc_html($is_en ? 'View product' : 'Skatīt preci'); ?> <?php echo function_exists('labaslietas_green_icon') ? labaslietas_green_icon('arrow') : '&rarr;'; ?></a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

/** Clear common caches once after v3.0.33 is installed. */
function labaslietas_v333_cache_purge_once() {
    if ((string) get_option('labaslietas_v333_cache_purged', '') === '3.0.33') { return; }
    if (is_admin() && !current_user_can('manage_options')) { return; }

    if (function_exists('wp_theme_purge_all_theme_cache')) { wp_theme_purge_all_theme_cache(); }
    if (function_exists('wp_cache_flush')) { wp_cache_flush(); }
    if (function_exists('wc_delete_product_transients')) { wc_delete_product_transients(); }
    if (function_exists('rocket_clean_domain')) { rocket_clean_domain(); }
    if (function_exists('w3tc_flush_all')) { w3tc_flush_all(); }
    if (function_exists('wp_cache_clear_cache')) { wp_cache_clear_cache(); }
    if (function_exists('sg_cachepress_purge_cache')) { sg_cachepress_purge_cache(); }
    if (class_exists('autoptimizeCache') && is_callable(array('autoptimizeCache', 'clearall'))) { autoptimizeCache::clearall(); }
    if (class_exists('LiteSpeed\\Purge') && is_callable(array('LiteSpeed\\Purge', 'purge_all'))) { LiteSpeed\Purge::purge_all(); }
    delete_transient('wc_products_onsale');
    update_option('labaslietas_v333_cache_purged', '3.0.33', false);
}
add_action('admin_init', 'labaslietas_v333_cache_purge_once', 1017);
