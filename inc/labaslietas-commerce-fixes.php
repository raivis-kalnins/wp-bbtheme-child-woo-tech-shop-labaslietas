<?php
/** LABAS LIETAS commerce/content fixes: footer pages, delivery, checkout customer type. PHP 7.4 compatible. */
defined('ABSPATH') || exit;

function labaslietas_information_page_definitions() {
    return array(
        'par-mums' => array(
            'title'=>'Par mums',
            'lead'=>'Labas Lietas piedāvā profesionālus instrumentus, dārza tehniku un servisa aprīkojumu meistariem un entuziastiem.',
            'sections'=>array(
                'Ko piedāvājam'=>'Praktisku un uzticamu aprīkojumu darbam darbnīcā, servisā, saimniecībā un dārzā.',
                'Mūsu pieeja'=>'Skaidra informācija par precēm, piegādi, garantiju un atgriešanu, kā arī palīdzība piemērotākās preces izvēlē.'
            )
        ),
        'piegade' => array(
            'title'=>'Piegāde',
            'lead'=>'Izvēlies saņemšanu Labas Lietas noliktavā, pakomātā vai ar kurjeru visā Latvijā.',
            'sections'=>array(
                'Saņemšana'=>'Labas Lietas noliktavā Smiltenē — bez maksas.',
                'Pakomāti'=>'Omniva pakomāts — 3,90 €. Unisend pakomāts — 3,90 €. Latvijas Pasts pakomāts — 3,90 €.',
                'UNISEND Kurjers'=>'Piegāde ar UNISEND kurjeru sūtījumiem līdz 30 kg — 10,00 €.',
                'Lielgabarīta piegāde'=>'Lielgabarīta vai nestandarta precēm piegādes izmaksas ir atkarīgas no piegādes vietas un tiek saskaņotas atsevišķi.'
            )
        ),
        'apmaksa' => array(
            'title'=>'Apmaksa',
            'lead'=>'Pasūtījuma noformēšanā pieejama apmaksa skaidrā naudā, ar bankas pārskaitījumu vai tiešsaistē.',
            'sections'=>array(
                'Skaidrā naudā'=>'Apmaksa skaidrā naudā, saņemot preci Smiltenē.',
                'Bankas pārskaitījums'=>'Apmaksa pēc rēķina saņemšanas. Maksājuma uzdevumā lūdzam norādīt rēķina numuru.',
                'Tiešsaistē ar internetbanku'=>'Drošs tiešsaistes maksājums ar EveryPay / Swedbank maksājumu vārteju pēc tās aktivizēšanas WooCommerce iestatījumos.'
            )
        ),
        'atgriesana' => array(
            'title'=>'Atgriešana un maiņa',
            'lead'=>'Preču atgriešana un maiņa tiek veikta saskaņā ar Latvijas normatīvajiem aktiem un veikala noteikumiem.',
            'sections'=>array(
                'Atgriešana'=>'Sazinies ar mums pirms preces nosūtīšanas atpakaļ. Precei jābūt atbilstošā stāvoklī un, ja iespējams, oriģinālajā iepakojumā.',
                'Bojājums vai neatbilstība'=>'Ja saņemta bojāta vai pasūtījumam neatbilstoša prece, sazinies ar klientu servisu pēc iespējas ātrāk.'
            )
        ),
        'kontakti' => array(
            'title'=>'Kontakti',
            'lead'=>'Sazinies ar Labas Lietas par precēm, piegādi, pasūtījumiem un garantiju.',
            'contact'=>true,
            'sections'=>array(
                'Tālrunis'=>'Informācija Facebook profilā',
                'E-pasts'=>'Saziņai izmanto kontaktformu',
                'Adrese'=>'Smiltene, Latvija',
                'Darba laiks'=>'Pēc vienošanās'
            )
        ),
        'biezak-uzdotie-jautajumi' => array(
            'title'=>'Biežāk uzdotie jautājumi',
            'lead'=>'Atbildes uz biežākajiem jautājumiem par pasūtījumiem un piegādi.',
            'sections'=>array(
                'Kā izvēlēties pakomātu?'=>'Noformējot pasūtījumu, izvēlies Omniva, Unisend vai Latvijas Pasts pakomātu un pēc tam konkrēto pakomātu.',
                'Ko darīt, ja prece ir pārāk smaga pakomātam?'=>'Lielgabarīta vai nestandarta precēm izvēlies Labas Lietas kurjeru; piegādes izmaksas tiks saskaņotas pēc pasūtījuma saņemšanas.',
                'Vai varu saņemt rēķinu uzņēmumam?'=>'Jā. Norēķinu informācijā izvēlies “Juridiska persona” un aizpildi uzņēmuma rekvizītus.'
            )
        ),
        'garantija' => array(
            'title'=>'Garantija',
            'lead'=>'Garantijas nosacījumi ir atkarīgi no preces un ražotāja.',
            'sections'=>array(
                'Garantijas pieteikums'=>'Sagatavo pasūtījuma informāciju, preces nosaukumu un īsu problēmas aprakstu, pēc tam sazinies ar klientu servisu.',
                'Izskatīšana'=>'Pēc preces un informācijas saņemšanas tiks precizēts tālākais garantijas risinājums.'
            )
        ),
        'sudzibu-iesniegsana' => array(
            'title'=>'Sūdzību iesniegšana',
            'lead'=>'Sūdzību vai ierosinājumu vari iesniegt rakstiski vai sazinoties ar klientu servisu.',
            'sections'=>array(
                'Kā iesniegt'=>'Nosūti aprakstu uz , norādot pasūtījuma numuru, kontaktinformāciju un situācijas aprakstu.',
                'Atbilde'=>'Labas Lietas sazināsies pēc informācijas izvērtēšanas un precizēs turpmāko risinājumu.'
            )
        ),
    );
}

function labaslietas_contact_form_block_markup() {
    // Keep the page content Gutenberg/BBuilder-friendly, while rendering the
    // actual contact form through a shortcode so old/new BBuilder block schemas
    // cannot silently fall back to the English demo fields.
    return '<!-- wp:shortcode -->[labaslietas_contact_form]<!-- /wp:shortcode -->';
}

/**
 * LABAS LIETAS contact form that uses BBuilder's AJAX form handler and hCaptcha
 * configuration. This avoids block-attribute version mismatches while keeping
 * submissions, spam checks and captcha verification inside BBuilder.
 */
function labaslietas_render_contact_form_shortcode() {
    $recipient = sanitize_email(get_option('admin_email'));
    $success = 'Paldies! Ziņa ir nosūtīta. Mēs ar jums sazināsimies.';
    $validation = 'Lūdzu, aizpildi visus obligātos laukus pareizi.';
    $captcha_provider = '';
    $hcaptcha_site_key = '';
    $hcaptcha_secret_key = '';

    if (function_exists('wpbb_get_option')) {
        $recipient = sanitize_email(wpbb_get_option('default_recipient_email', $recipient));
        if (!$recipient) { $recipient = sanitize_email(get_option('admin_email')); }
        $hcaptcha_site_key = sanitize_text_field((string) wpbb_get_option('hcaptcha_site_key', ''));
        $hcaptcha_secret_key = sanitize_text_field((string) wpbb_get_option('hcaptcha_secret_key', ''));
        $hcaptcha_enabled = (bool) wpbb_get_option('hcaptcha_enabled', 0);
        if ($hcaptcha_enabled && $hcaptcha_site_key !== '' && $hcaptcha_secret_key !== '') {
            $captcha_provider = 'hcaptcha';
        }
    }

    // BBuilder normally registers this globally. Register a safe fallback so
    // the contact form cannot lose its AJAX behaviour if script registration
    // order changes between BBuilder versions.
    if (!wp_script_is('wpbb-form-view', 'registered') && defined('WPBB_PLUGIN_URL')) {
        wp_register_script(
            'wpbb-form-view',
            WPBB_PLUGIN_URL . 'assets/form.js',
            array(),
            defined('WPBB_VERSION') ? WPBB_VERSION : null,
            true
        );
    }
    if (wp_script_is('wpbb-form-view', 'registered')) {
        wp_enqueue_script('wpbb-form-view');
        wp_localize_script('wpbb-form-view', 'wpbbForm', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wpbb_form_nonce'),
            'error' => 'Ziņu neizdevās nosūtīt. Lūdzu, mēģini vēlreiz.',
            'validationText' => $validation,
        ));
    }
    if ($captcha_provider === 'hcaptcha') {
        wp_enqueue_script('hcaptcha-api', 'https://js.hcaptcha.com/1/api.js', array(), null, true);
    }

    $honeypot_id = 'labaslietas-website-' . wp_unique_id();
    ob_start();
    ?>
    <div class="wpbb-dynamic-form-wrap style-soft labels-top labaslietas-contact-form-wrap">
        <form class="wpbb-form wpbb-dynamic-form labaslietas-contact-form" data-recipient="<?php echo esc_attr($recipient); ?>" data-subject="<?php echo esc_attr('Jauns ziņojums no Labas Lietas kontaktu formas'); ?>" data-success="<?php echo esc_attr($success); ?>" data-validation="<?php echo esc_attr($validation); ?>" data-steps="0" data-conditional="0" data-honeypot="1" data-captcha-provider="<?php echo esc_attr($captcha_provider); ?>" enctype="multipart/form-data">
            <div class="wpbb-form-bot-field" hidden aria-hidden="true">
                <label for="<?php echo esc_attr($honeypot_id); ?>">Atstāj šo lauku tukšu</label>
                <input id="<?php echo esc_attr($honeypot_id); ?>" type="text" name="website" value="" tabindex="-1" autocomplete="off">
                <input type="hidden" name="started_at" value="<?php echo esc_attr(time()); ?>">
            </div>

            <div class="labaslietas-contact-form-grid">
                <div class="wpbb-field labaslietas-contact-field">
                    <label class="form-label" for="labaslietas-contact-name">Vārds, uzvārds <span aria-hidden="true">*</span></label>
                    <input id="labaslietas-contact-name" class="form-control" type="text" name="name" autocomplete="name" placeholder="Jūsu vārds un uzvārds" required>
                </div>
                <div class="wpbb-field labaslietas-contact-field">
                    <label class="form-label" for="labaslietas-contact-email">E-pasts <span aria-hidden="true">*</span></label>
                    <input id="labaslietas-contact-email" class="form-control" type="email" name="email" autocomplete="email" placeholder="jusu@epasts.lv" required>
                </div>
                <div class="wpbb-field labaslietas-contact-field">
                    <label class="form-label" for="labaslietas-contact-phone">Tālruņa numurs</label>
                    <input id="labaslietas-contact-phone" class="form-control" type="tel" name="phone" autocomplete="tel" placeholder="+371 ...">
                </div>
                <div class="wpbb-field labaslietas-contact-field">
                    <label class="form-label" for="labaslietas-contact-subject">Temats <span aria-hidden="true">*</span></label>
                    <input id="labaslietas-contact-subject" class="form-control" type="text" name="subject" placeholder="Par ko vēlaties jautāt?" required>
                </div>
                <div class="wpbb-field labaslietas-contact-field labaslietas-contact-field--full">
                    <label class="form-label" for="labaslietas-contact-message">Ziņa <span aria-hidden="true">*</span></label>
                    <textarea id="labaslietas-contact-message" class="form-control" name="message" rows="6" placeholder="Aprakstiet jautājumu, preci vai pasūtījumu." required></textarea>
                </div>

                <?php if ($captcha_provider === 'hcaptcha') : ?>
                    <div class="wpbb-field wpbb-field--captcha labaslietas-contact-field labaslietas-contact-field--full">
                        <div class="h-captcha" data-sitekey="<?php echo esc_attr($hcaptcha_site_key); ?>"></div>
                        <input type="hidden" name="wpbb_captcha_enabled" value="1">
                        <input type="hidden" name="wpbb_captcha_provider" value="hcaptcha">
                    </div>
                <?php elseif (current_user_can('manage_options')) : ?>
                    <div class="labaslietas-captcha-admin-note labaslietas-contact-field labaslietas-contact-field--full">
                        <strong>hCaptcha ir integrēts ar BBuilder.</strong>
                        Lai izaicinājums parādītos publiski, BBuilder iestatījumos ieslēdz hCaptcha un ievadi site key + secret key.
                    </div>
                <?php endif; ?>
            </div>

            <div class="wpbb-form-message labaslietas-contact-form-message" aria-live="polite"></div>
            <div class="wpbb-form-actions labaslietas-contact-form-actions">
                <button type="submit" class="btn btn-primary labaslietas-contact-submit">Nosūtīt ziņu</button>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('labaslietas_contact_form', 'labaslietas_render_contact_form_shortcode');

function labaslietas_information_page_content($definition, $slug = '') {
    $lead = isset($definition['lead']) ? $definition['lead'] : '';
    $sections = isset($definition['sections']) && is_array($definition['sections']) ? $definition['sections'] : array();
    $html = '<!-- labaslietas-managed-page:v6 -->';

    if ($slug === 'kontakti' || !empty($definition['contact'])) {
        $phone = isset($sections['Tālrunis']) ? $sections['Tālrunis'] : '';
        $email = isset($sections['E-pasts']) ? $sections['E-pasts'] : '';
        $address = isset($sections['Adrese']) ? $sections['Adrese'] : 'Smiltene, Latvija';
        $hours = isset($sections['Darba laiks']) ? $sections['Darba laiks'] : '';
        $html .= '<!-- wp:wpbb/row {"className":"labaslietas-info-row labaslietas-contact-row"} -->';
        $html .= '<!-- wp:wpbb/column {"lg":5,"className":"labaslietas-contact-details-col"} -->';
        $html .= '<!-- wp:paragraph {"className":"labaslietas-info-lead"} --><p class="labaslietas-info-lead">' . esc_html($lead) . '</p><!-- /wp:paragraph -->';
        $html .= '<!-- wp:group {"className":"labaslietas-contact-cards"} --><div class="wp-block-group labaslietas-contact-cards">';
        $html .= '<!-- wp:group {"className":"labaslietas-contact-card"} --><div class="wp-block-group labaslietas-contact-card"><h2>Facebook</h2><p><a href="https://www.facebook.com/labas.lietas.33" target="_blank" rel="noopener noreferrer">Labas Lietas Facebook profils</a></p></div><!-- /wp:group -->';
        $html .= '<!-- wp:group {"className":"labaslietas-contact-card"} --><div class="wp-block-group labaslietas-contact-card"><h2>Saziņa</h2><p>Izmanto kontaktformu, lai nosūtītu ziņu veikalam.</p></div><!-- /wp:group -->';
        $html .= '<!-- wp:group {"className":"labaslietas-contact-card"} --><div class="wp-block-group labaslietas-contact-card"><h2>Adrese</h2><p>' . esc_html($address) . '</p></div><!-- /wp:group -->';
        $html .= '<!-- wp:group {"className":"labaslietas-contact-card"} --><div class="wp-block-group labaslietas-contact-card"><h2>Darba laiks</h2><p>' . esc_html($hours) . '</p></div><!-- /wp:group -->';
        $html .= '</div><!-- /wp:group -->';
        $html .= '<!-- /wp:wpbb/column -->';
        $html .= '<!-- wp:wpbb/column {"lg":7,"className":"labaslietas-contact-form-col"} -->';
        $html .= '<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Raksti mums</h2><!-- /wp:heading -->';
        $html .= '<!-- wp:paragraph --><p>Aizpildi formu, un ziņa tiks nosūtīta uz Labas Lietas. Forma izmanto BBuilder sūtīšanu, aizsardzību pret spamu un konfigurēto hCaptcha.</p><!-- /wp:paragraph -->';
        $html .= labaslietas_contact_form_block_markup();
        $html .= '<!-- /wp:wpbb/column -->';
        $html .= '<!-- /wp:wpbb/row -->';
        return $html;
    }

    $html .= '<!-- wp:wpbb/row {"className":"labaslietas-info-row"} -->';
    $html .= '<!-- wp:wpbb/column {"lg":12} -->';
    $html .= '<!-- wp:paragraph {"className":"labaslietas-info-lead"} --><p class="labaslietas-info-lead">' . esc_html($lead) . '</p><!-- /wp:paragraph -->';
    $html .= '<!-- wp:group {"className":"labaslietas-info-grid"} --><div class="wp-block-group labaslietas-info-grid">';
    foreach ($sections as $heading => $text) {
        $html .= '<!-- wp:group {"className":"labaslietas-info-section"} --><div class="wp-block-group labaslietas-info-section">';
        $html .= '<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">' . esc_html($heading) . '</h2><!-- /wp:heading -->';
        $html .= '<!-- wp:paragraph --><p>' . esc_html($text) . '</p><!-- /wp:paragraph -->';
        $html .= '</div><!-- /wp:group -->';
    }
    $html .= '</div><!-- /wp:group -->';
    $html .= '<!-- /wp:wpbb/column --><!-- /wp:wpbb/row -->';
    return $html;
}

function labaslietas_ensure_information_pages() {
    if (!current_user_can('manage_options') || !function_exists('get_page_by_path')) { return; }
    $version = '2026-09-20-6';
    if (get_option('labaslietas_info_pages_version') === $version) { return; }
    foreach (labaslietas_information_page_definitions() as $slug => $definition) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        $content = labaslietas_information_page_content($definition, $slug);
        if (!$page) {
            wp_insert_post(array(
                'post_type'=>'page','post_status'=>'publish','post_title'=>$definition['title'],'post_name'=>$slug,
                'post_content'=>$content,'post_excerpt'=>$definition['lead'],'comment_status'=>'closed','ping_status'=>'closed'
            ));
            continue;
        }
        $update = array('ID'=>$page->ID);
        $changed = false;
        if ($page->post_status !== 'publish') { $update['post_status'] = 'publish'; $changed = true; }
        $existing = (string)$page->post_content;
        $is_managed = trim($existing) === '' || strpos($existing, 'labaslietas-info-row') !== false || strpos($existing, 'labaslietas-contact-row') !== false || strpos($existing, 'labaslietas-managed-page:') !== false;
        if ($is_managed && $existing !== $content) { $update['post_content'] = $content; $changed = true; }
        if ((string)$page->post_excerpt !== (string)$definition['lead']) { $update['post_excerpt'] = $definition['lead']; $changed = true; }
        if ($changed) { wp_update_post($update); }
    }
    $opts = get_option('labaslietas_theme_options', array());
    if (!is_array($opts)) { $opts = array(); }
    $opts['footer_delivery_partners'] = 'Unisend,Omniva,Latvijas Pasts,Kurjers';
    update_option('labaslietas_theme_options', $opts, false);
    update_option('labaslietas_info_pages_version', $version, false);
}
add_action('admin_init', 'labaslietas_ensure_information_pages', 25);
add_action('after_switch_theme', 'labaslietas_ensure_information_pages', 25);

/** Front-end safety net for sites that have not yet visited wp-admin after the update. */
add_filter('the_content', function($content) {
    if (!is_page() || !in_the_loop() || !is_main_query()) { return $content; }
    $post = get_post();
    $defs = labaslietas_information_page_definitions();
    if (!$post || !array_key_exists($post->post_name, $defs)) { return $content; }

    // Render the newest managed content immediately even before an admin page
    // is visited. The admin migration will persist the same content later.
    $stored = (string) $post->post_content;
    $is_managed = strpos($stored, 'labaslietas-managed-page:') !== false || strpos($stored, 'labaslietas-info-row') !== false || strpos($stored, 'labaslietas-contact-row') !== false;
    if ($is_managed && strpos($stored, 'labaslietas-managed-page:v6') === false) {
        $content = do_blocks(labaslietas_information_page_content($defs[$post->post_name], $post->post_name));
    }

    // The page template owns the single H1.
    return preg_replace('/<h1\b[^>]*>.*?<\/h1>/is', '', $content, 1);
}, 99);

add_filter('body_class', function($classes) {
    if (is_page(array_keys(labaslietas_information_page_definitions()))) { $classes[] = 'labaslietas-info-page'; }
    return $classes;
});

/** Lightweight descriptions for the managed content pages when no SEO plugin is supplying one. */
add_action('wp_head', function() {
    if (!is_page() || (function_exists('labaslietas_yoast_active_235') && labaslietas_yoast_active_235()) || defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION')) { return; }
    $post = get_post();
    if (!$post) { return; }
    $defs = labaslietas_information_page_definitions();
    if (!isset($defs[$post->post_name]['lead'])) { return; }
    echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($defs[$post->post_name]['lead'])) . '">' . "\n";
}, 3);

add_action('wp', function() {
    if (!class_exists('WPBBuilder_Bootstrap')) { return; }
    $slugs = array_keys(labaslietas_information_page_definitions());
    if (is_page($slugs) || (function_exists('is_checkout') && is_checkout()) || (function_exists('is_cart') && is_cart())) {
        WPBBuilder_Bootstrap::needs(array('forms','card','buttons','helpers'));
    }
});

/** Read a weight mentioned in legacy product copy when the WooCommerce weight field is empty. */
function labaslietas_product_declared_weight_kg($product) {
    if (!$product || !is_a($product, 'WC_Product')) { return 0.0; }
    $text = wp_strip_all_tags((string)$product->get_description() . ' ' . (string)$product->get_short_description());
    if (preg_match('/(?:svars|weight)\s*:?\s*([0-9]+(?:[,.][0-9]+)?)\s*(?:kg|kilogram(?:i|s)?)?/iu', $text, $m)) {
        return (float)str_replace(',', '.', $m[1]);
    }
    return 0.0;
}

function labaslietas_product_is_oversize($product) {
    if (!$product || !is_a($product, 'WC_Product')) { return false; }
    if ($product->get_meta('_labaslietas_shipping_quote') === 'yes') { return true; }
    $name = (string)$product->get_name();
    return (bool)preg_match('/\b(prese|preses)\b/iu', $name);
}

/** Return cart weight in kilograms, with product-copy fallback for legacy LABAS LIETAS products. */
function labaslietas_cart_weight_kg($package = array()) {
    $weight = 0.0;
    $contents = !empty($package['contents']) ? $package['contents'] : ((function_exists('WC') && WC()->cart) ? WC()->cart->get_cart() : array());
    foreach ((array)$contents as $item) {
        $product = isset($item['data']) && is_a($item['data'], 'WC_Product') ? $item['data'] : null;
        if (!$product) { continue; }
        $qty = isset($item['quantity']) ? max(1, (int)$item['quantity']) : 1;
        $w = (float)$product->get_weight();
        if ($w > 0) {
            $weight += (float)wc_get_weight($w * $qty, 'kg');
        } else {
            $weight += labaslietas_product_declared_weight_kg($product) * $qty;
        }
    }
    return $weight;
}

function labaslietas_package_requires_quote($package = array()) {
    if (labaslietas_cart_weight_kg($package) > 30) { return true; }
    $contents = !empty($package['contents']) ? $package['contents'] : ((function_exists('WC') && WC()->cart) ? WC()->cart->get_cart() : array());
    foreach ((array)$contents as $item) {
        $product = isset($item['data']) && is_a($item['data'], 'WC_Product') ? $item['data'] : null;
        if (!$product) { continue; }
        if (labaslietas_product_is_oversize($product)) { return true; }
    }
    return false;
}

function labaslietas_package_allows_parcel($package = array()) {
    if (labaslietas_package_requires_quote($package)) { return false; }
    $contents = !empty($package['contents']) ? $package['contents'] : ((function_exists('WC') && WC()->cart) ? WC()->cart->get_cart() : array());
    foreach ((array)$contents as $item) {
        $product = isset($item['data']) && is_a($item['data'], 'WC_Product') ? $item['data'] : null;
        if ($product && $product->get_meta('_labaslietas_no_parcel') === 'yes') { return false; }
    }
    return true;
}

add_action('woocommerce_shipping_init', function() {
    if (!class_exists('WC_Shipping_Method')) { return; }

    if (!class_exists('WC_Shipping_Labaslietas_Pickup')) {
        class WC_Shipping_Labaslietas_Pickup extends WC_Shipping_Method {
            public function __construct($instance_id = 0) {
                $this->id = 'labaslietas_pickup';
                $this->instance_id = absint($instance_id);
                $this->method_title = 'Saņemšana Smiltenē';
                $this->method_description = 'Bezmaksas saņemšana Smiltenē.';
                $this->supports = array('shipping-zones','instance-settings');
                $this->enabled = 'yes';
                $this->title = 'Saņemšana Smiltenē';
            }
            public function calculate_shipping($package = array()) {
                $this->add_rate(array(
                    'id'=>$this->get_rate_id(),
                    'label'=>'Saņemšana Smiltenē — Bezmaksas',
                    'cost'=>0,
                    'package'=>$package,
                ));
            }
        }
    }

    if (!class_exists('WC_Shipping_Labaslietas_Omniva')) {
        class WC_Shipping_Labaslietas_Omniva extends WC_Shipping_Method {
            public function __construct($instance_id = 0) {
                $this->id = 'labaslietas_omniva';
                $this->instance_id = absint($instance_id);
                $this->method_title = 'Omniva pakomāts';
                $this->method_description = 'Omniva pakomāta piegāde Latvijā.';
                $this->supports = array('shipping-zones','instance-settings');
                $this->enabled = 'yes';
                $this->title = 'Omniva pakomāts';
            }
            public function calculate_shipping($package = array()) {
                if (!labaslietas_package_allows_parcel($package)) { return; }
                $this->add_rate(array('id'=>$this->get_rate_id(),'label'=>'Omniva pakomāts','cost'=>3.90,'package'=>$package));
            }
        }
    }

    if (!class_exists('WC_Shipping_Labaslietas_Unisend_Parcel')) {
        class WC_Shipping_Labaslietas_Unisend_Parcel extends WC_Shipping_Method {
            public function __construct($instance_id = 0) {
                $this->id = 'labaslietas_unisend_parcel';
                $this->instance_id = absint($instance_id);
                $this->method_title = 'Unisend pakomāts';
                $this->method_description = 'Unisend pakomāta piegāde Latvijā.';
                $this->supports = array('shipping-zones','instance-settings');
                $this->enabled = 'yes';
                $this->title = 'Unisend pakomāts';
            }
            public function calculate_shipping($package = array()) {
                if (!labaslietas_package_allows_parcel($package)) { return; }
                $this->add_rate(array('id'=>$this->get_rate_id(),'label'=>'Unisend pakomāts','cost'=>3.90,'package'=>$package));
            }
        }
    }

    if (!class_exists('WC_Shipping_Labaslietas_Latvijas_Pasts')) {
        class WC_Shipping_Labaslietas_Latvijas_Pasts extends WC_Shipping_Method {
            public function __construct($instance_id = 0) {
                $this->id = 'labaslietas_latvijas_pasts';
                $this->instance_id = absint($instance_id);
                $this->method_title = 'Latvijas Pasts pakomāts';
                $this->method_description = 'Latvijas Pasta pakomāta piegāde Latvijā.';
                $this->supports = array('shipping-zones','instance-settings');
                $this->enabled = 'yes';
                $this->title = 'Latvijas Pasts pakomāts';
            }
            public function calculate_shipping($package = array()) {
                if (!labaslietas_package_allows_parcel($package)) { return; }
                $this->add_rate(array('id'=>$this->get_rate_id(),'label'=>'Latvijas Pasts pakomāts','cost'=>3.90,'package'=>$package));
            }
        }
    }

    if (!class_exists('WC_Shipping_Labaslietas_Unisend_Courier')) {
        class WC_Shipping_Labaslietas_Unisend_Courier extends WC_Shipping_Method {
            public function __construct($instance_id = 0) {
                $this->id = 'labaslietas_unisend_courier';
                $this->instance_id = absint($instance_id);
                $this->method_title = 'UNISEND Kurjers';
                $this->method_description = 'UNISEND kurjera piegāde sūtījumiem līdz 30 kg.';
                $this->supports = array('shipping-zones','instance-settings');
                $this->enabled = 'yes';
                $this->title = 'UNISEND Kurjers';
            }
            public function calculate_shipping($package = array()) {
                if (labaslietas_package_requires_quote($package)) { return; }
                $this->add_rate(array('id'=>$this->get_rate_id(),'label'=>'UNISEND Kurjers (līdz 30 kg)','cost'=>10.00,'package'=>$package));
            }
        }
    }

    if (!class_exists('WC_Shipping_Labaslietas_Oversize_Courier')) {
        class WC_Shipping_Labaslietas_Oversize_Courier extends WC_Shipping_Method {
            public function __construct($instance_id = 0) {
                $this->id = 'labaslietas_oversize_courier';
                $this->instance_id = absint($instance_id);
                $this->method_title = 'Lielgabarīta piegāde';
                $this->method_description = 'Lielgabarīta piegāde; izmaksas tiek saskaņotas atsevišķi.';
                $this->supports = array('shipping-zones','instance-settings');
                $this->enabled = 'yes';
                $this->title = 'Lielgabarīta piegāde';
            }
            public function calculate_shipping($package = array()) {
                if (!labaslietas_package_requires_quote($package)) { return; }
                $this->add_rate(array(
                    'id'=>$this->get_rate_id(),
                    'label'=>'Lielgabarīta piegāde (izmaksas atkarīgas no piegādes vietas) — Pēc vienošanās',
                    'cost'=>0,
                    'package'=>$package,
                    'meta_data'=>array('labaslietas_quote'=>'yes'),
                ));
            }
        }
    }
});

add_filter('woocommerce_shipping_package_name', function($name) {
    return 'Piegāde';
}, 20);

add_filter('woocommerce_shipping_methods', function($methods) {
    $methods['labaslietas_pickup'] = 'WC_Shipping_Labaslietas_Pickup';
    $methods['labaslietas_omniva'] = 'WC_Shipping_Labaslietas_Omniva';
    $methods['labaslietas_unisend_parcel'] = 'WC_Shipping_Labaslietas_Unisend_Parcel';
    $methods['labaslietas_latvijas_pasts'] = 'WC_Shipping_Labaslietas_Latvijas_Pasts';
    $methods['labaslietas_unisend_courier'] = 'WC_Shipping_Labaslietas_Unisend_Courier';
    $methods['labaslietas_oversize_courier'] = 'WC_Shipping_Labaslietas_Oversize_Courier';
    return $methods;
});

function labaslietas_shipping_method_base_id($method) {
    $method = (string)$method;
    $pos = strpos($method, ':');
    return $pos === false ? $method : substr($method, 0, $pos);
}

function labaslietas_locker_provider_for_shipping($method) {
    $map = array(
        'labaslietas_omniva'=>'omniva',
        'labaslietas_unisend_parcel'=>'unisend',
        'labaslietas_latvijas_pasts'=>'latvijas-pasts',
        // Backward compatibility for orders/checkouts created with the previous combined method.
        'labaslietas_parcel'=>'',
    );
    $base = labaslietas_shipping_method_base_id($method);
    return isset($map[$base]) ? $map[$base] : '';
}

function labaslietas_is_parcel_shipping($method) {
    return in_array(labaslietas_shipping_method_base_id($method), array('labaslietas_omniva','labaslietas_unisend_parcel','labaslietas_latvijas_pasts','labaslietas_parcel'), true);
}

function labaslietas_is_courier_shipping($method) {
    return in_array(labaslietas_shipping_method_base_id($method), array('labaslietas_unisend_courier','labaslietas_oversize_courier','labaslietas_courier'), true);
}

/**
 * Labas Lietas sells and delivers only inside Latvia.
 *
 * Keep this enforced in WooCommerce data/rate calculation rather than only
 * hiding the country selector. That prevents stale customer sessions or a
 * crafted request from moving the cart into another shipping zone.
 */
function labaslietas_latvia_only_country_list($countries) {
    $label = (is_array($countries) && isset($countries['LV'])) ? $countries['LV'] : 'Latvija';
    return array('LV' => $label);
}
add_filter('woocommerce_countries_allowed_countries', 'labaslietas_latvia_only_country_list', 999);
add_filter('woocommerce_countries_shipping_countries', 'labaslietas_latvia_only_country_list', 999);

function labaslietas_force_latvia_customer_country($country, $customer = null) {
    if (is_admin() && !(function_exists('wp_doing_ajax') && wp_doing_ajax())) { return $country; }
    return 'LV';
}
add_filter('woocommerce_customer_get_billing_country', 'labaslietas_force_latvia_customer_country', 999, 2);
add_filter('woocommerce_customer_get_shipping_country', 'labaslietas_force_latvia_customer_country', 999, 2);

add_filter('woocommerce_cart_shipping_packages', function($packages) {
    foreach ((array)$packages as $index => $package) {
        if (!is_array($package)) { continue; }
        if (!isset($package['destination']) || !is_array($package['destination'])) { $package['destination'] = array(); }
        $package['destination']['country'] = 'LV';
        $packages[$index] = $package;
    }
    return $packages;
}, 999);

/** Keep WooCommerce's own country/shipping settings synchronized with the Latvia-only storefront. */
function labaslietas_ensure_latvia_only_shipping_settings() {
    if (!current_user_can('manage_woocommerce') && !current_user_can('manage_options')) { return; }
    $version = '2026-09-20-1';
    if (get_option('labaslietas_latvia_only_shipping_version') === $version) { return; }

    update_option('woocommerce_allowed_countries', 'specific', false);
    update_option('woocommerce_specific_allowed_countries', array('LV'), false);
    update_option('woocommerce_ship_to_countries', 'specific', false);
    update_option('woocommerce_specific_ship_to_countries', array('LV'), false);
    update_option('labaslietas_latvia_only_shipping_version', $version, false);

    if (function_exists('WC') && WC()->session) {
        WC()->session->__unset('shipping_for_package_0');
    }
    if (function_exists('wc_delete_product_transients')) { wc_delete_product_transients(); }
}
add_action('admin_init', 'labaslietas_ensure_latvia_only_shipping_settings', 68);
add_action('after_switch_theme', 'labaslietas_ensure_latvia_only_shipping_settings', 68);

/** Force the current frontend customer/session back to Latvia before cart/checkout calculations. */
function labaslietas_force_latvia_customer_session() {
    if (!function_exists('WC') || !WC()->customer) { return; }
    $changed = false;
    if (WC()->customer->get_billing_country('edit') !== 'LV') {
        WC()->customer->set_billing_country('LV');
        $changed = true;
    }
    if (WC()->customer->get_shipping_country('edit') !== 'LV') {
        WC()->customer->set_shipping_country('LV');
        $changed = true;
    }
    if ($changed && method_exists(WC()->customer, 'save')) { WC()->customer->save(); }
}
add_action('woocommerce_before_cart', 'labaslietas_force_latvia_customer_session', 1);
add_action('woocommerce_before_checkout_form', 'labaslietas_force_latvia_customer_session', 1);

/** Ensure Latvia has the requested LABAS LIETAS shipping methods after activation or database restore. */
function labaslietas_ensure_shipping_zone_methods() {
    if (!current_user_can('manage_options') || !class_exists('WC_Shipping_Zones') || !class_exists('WC_Shipping_Zone')) { return; }
    $version = '2026-09-20-1';
    if (get_option('labaslietas_shipping_setup_version') === $version) { return; }

    $zone = null;
    $zones = WC_Shipping_Zones::get_zones();
    foreach ((array)$zones as $zone_data) {
        if (empty($zone_data['zone_id']) || empty($zone_data['zone_locations'])) { continue; }
        foreach ((array)$zone_data['zone_locations'] as $location) {
            if (isset($location->type, $location->code) && $location->type === 'country' && strtoupper($location->code) === 'LV') {
                $zone = new WC_Shipping_Zone((int)$zone_data['zone_id']);
                break 2;
            }
        }
    }
    if (!$zone) {
        $zone = new WC_Shipping_Zone();
        $zone->set_zone_name('Latvija');
        $zone->set_zone_order(0);
        $zone->add_location('LV', 'country');
        $zone->save();
    }

    $wanted = array('labaslietas_pickup','labaslietas_omniva','labaslietas_unisend_parcel','labaslietas_latvijas_pasts','labaslietas_unisend_courier','labaslietas_oversize_courier');
    $present = array();
    foreach ((array)$zone->get_shipping_methods(true) as $method) {
        if (!isset($method->id)) { continue; }
        if (in_array($method->id, array('labaslietas_parcel','labaslietas_courier'), true) && !empty($method->instance_id)) {
            if (method_exists($zone, 'delete_shipping_method')) { $zone->delete_shipping_method((int)$method->instance_id); }
            continue;
        }
        $present[$method->id] = true;
    }
    foreach ($wanted as $method_id) {
        if (empty($present[$method_id])) { $zone->add_shipping_method($method_id); }
    }

    update_option('labaslietas_shipping_setup_version', $version, false);
    if (function_exists('wc_delete_product_transients')) { wc_delete_product_transients(); }
}
add_action('admin_init', 'labaslietas_ensure_shipping_zone_methods', 70);
add_action('after_switch_theme', 'labaslietas_ensure_shipping_zone_methods', 70);

/** Remove duplicate WooCommerce defaults and keep the client-requested delivery order. */
add_filter('woocommerce_package_rates', function($rates) {
    $order = array(
        'labaslietas_pickup'=>10,
        'labaslietas_omniva'=>20,
        'labaslietas_unisend_parcel'=>30,
        'labaslietas_latvijas_pasts'=>40,
        'labaslietas_unisend_courier'=>50,
        'labaslietas_oversize_courier'=>60,
        'labaslietas_parcel'=>90,
        'labaslietas_courier'=>91,
    );
    $new_method_ids = array('labaslietas_pickup','labaslietas_omniva','labaslietas_unisend_parcel','labaslietas_latvijas_pasts','labaslietas_unisend_courier','labaslietas_oversize_courier');
    $has_labaslietas = false;
    foreach ($rates as $rate) {
        if (in_array($rate->method_id, $new_method_ids, true)) { $has_labaslietas = true; break; }
    }
    if ($has_labaslietas) {
        foreach ($rates as $key => $rate) {
            if (in_array($rate->method_id, array('flat_rate','free_shipping','local_pickup','labaslietas_parcel','labaslietas_courier'), true)) { unset($rates[$key]); }
        }
    }
    uasort($rates, function($a, $b) use ($order) {
        $a_order = isset($order[$a->method_id]) ? $order[$a->method_id] : 999;
        $b_order = isset($order[$b->method_id]) ? $order[$b->method_id] : 999;
        if ($a_order === $b_order) { return 0; }
        return $a_order < $b_order ? -1 : 1;
    });
    return $rates;
}, 100);

add_action('woocommerce_product_options_shipping', function() {
    woocommerce_wp_checkbox(array('id'=>'_labaslietas_no_parcel','label'=>'Nevar sūtīt ar pakomātu','description'=>'Atzīmē lielām vai nestandarta precēm.'));
    woocommerce_wp_checkbox(array('id'=>'_labaslietas_shipping_quote','label'=>'Piegādes cenu precizēt','description'=>'Kurjera cena netiek pieskaitīta automātiski; klientam tiek parādīts, ka cena tiks precizēta.'));
});
add_action('woocommerce_process_product_meta', function($post_id) {
    update_post_meta($post_id, '_labaslietas_no_parcel', isset($_POST['_labaslietas_no_parcel']) ? 'yes' : 'no');
    update_post_meta($post_id, '_labaslietas_shipping_quote', isset($_POST['_labaslietas_shipping_quote']) ? 'yes' : 'no');
});

add_filter('woocommerce_checkout_fields', function($fields) {
    if (!isset($fields['billing'])) { $fields['billing'] = array(); }
    $fields['billing']['billing_customer_type'] = array(
        'type'=>'select','label'=>'Pircēja veids','required'=>true,'priority'=>5,
        'options'=>array('physical'=>'Fiziska persona','company'=>'Juridiska persona'),'class'=>array('form-row-wide')
    );
    if (isset($fields['billing']['billing_first_name'])) { $fields['billing']['billing_first_name']['required']=false; $fields['billing']['billing_first_name']['priority']=10; $fields['billing']['billing_first_name']['class']=array('form-row-first','labaslietas-physical-field'); }
    if (isset($fields['billing']['billing_last_name'])) { $fields['billing']['billing_last_name']['required']=false; $fields['billing']['billing_last_name']['priority']=20; $fields['billing']['billing_last_name']['class']=array('form-row-last','labaslietas-physical-field'); }
    if (isset($fields['billing']['billing_company'])) { $fields['billing']['billing_company']['required']=false; $fields['billing']['billing_company']['label']='Uzņēmuma nosaukums'; $fields['billing']['billing_company']['priority']=30; $fields['billing']['billing_company']['class']=array('form-row-wide','labaslietas-company-field'); }
    $fields['billing']['billing_company_id'] = array('type'=>'text','label'=>'Reģ. nr. / PVN nr. (ja ir)','required'=>false,'priority'=>31,'class'=>array('form-row-wide','labaslietas-company-field'));
    $fields['billing']['billing_legal_address'] = array('type'=>'textarea','label'=>'Juridiskā adrese','required'=>false,'priority'=>32,'class'=>array('form-row-wide','labaslietas-company-field'),'custom_attributes'=>array('rows'=>2));
    foreach (array('billing_address_1','billing_address_2','billing_city','billing_state','billing_postcode') as $key) { unset($fields['billing'][$key]); }
    $fields['billing']['billing_country'] = array('type'=>'hidden','required'=>true,'priority'=>39,'default'=>'LV','class'=>array('labaslietas-country-fixed'));
    if (isset($fields['shipping']['shipping_country'])) {
        $fields['shipping']['shipping_country']['type'] = 'hidden';
        $fields['shipping']['shipping_country']['required'] = true;
        $fields['shipping']['shipping_country']['default'] = 'LV';
        $fields['shipping']['shipping_country']['class'] = array('labaslietas-country-fixed');
    }
    if (isset($fields['billing']['billing_phone'])) { $fields['billing']['billing_phone']['priority']=40; $fields['billing']['billing_phone']['required']=true; $fields['billing']['billing_phone']['label']='Tālruņa numurs'; }
    if (isset($fields['billing']['billing_email'])) { $fields['billing']['billing_email']['priority']=50; $fields['billing']['billing_email']['required']=true; $fields['billing']['billing_email']['label']='E-pasts'; }
    return $fields;
}, 50);

function labaslietas_lines_to_locker_list($value) {
    $lines = preg_split('/\r\n|\r|\n/', (string)$value);
    $out = array();
    foreach ((array)$lines as $line) {
        $line = trim(wp_strip_all_tags($line));
        if ($line !== '') { $out[] = $line; }
    }
    return array_values(array_unique($out));
}

/** Public Omniva location feed, cached for 12 hours. Checkout still works if the feed is unavailable. */
function labaslietas_omniva_locker_locations() {
    $cache_key = 'labaslietas_omniva_lv_lockers_v2';
    $cached = get_transient($cache_key);
    if (is_array($cached) && !empty($cached)) { return $cached; }
    if (!function_exists('wp_remote_get')) { return array(); }
    $response = wp_remote_get('https://www.omniva.ee/locations.json', array('timeout'=>8, 'redirection'=>3, 'user-agent'=>'Labas Lietas WooCommerce/1.2.0'));
    if (is_wp_error($response) || (int)wp_remote_retrieve_response_code($response) !== 200) { return array(); }
    $rows = json_decode((string)wp_remote_retrieve_body($response), true);
    if (!is_array($rows)) { return array(); }
    $out = array();
    foreach ($rows as $row) {
        if (!is_array($row) || strtoupper(isset($row['A0_NAME']) ? $row['A0_NAME'] : '') !== 'LV') { continue; }
        $name = trim(isset($row['NAME']) ? $row['NAME'] : '');
        if ($name === '') { continue; }
        $street = trim(trim(isset($row['A5_NAME']) ? $row['A5_NAME'] : '') . ' ' . trim(isset($row['A7_NAME']) ? $row['A7_NAME'] : ''));
        $city = trim(isset($row['A3_NAME']) ? $row['A3_NAME'] : '');
        if ($city === '') { $city = trim(isset($row['A2_NAME']) ? $row['A2_NAME'] : ''); }
        $extra = implode(', ', array_filter(array($street, $city)));
        $out[] = $extra ? $name . ' — ' . $extra : $name;
    }
    $out = array_values(array_unique($out));
    natcasesort($out);
    $out = array_values($out);
    if (!empty($out)) { set_transient($cache_key, $out, 12 * HOUR_IN_SECONDS); }
    return $out;
}

function labaslietas_locker_suggestions() {
    $options = function_exists('labaslietas_get_theme_options') ? labaslietas_get_theme_options() : array();
    $defaults = array(
        'unisend'=>labaslietas_lines_to_locker_list(isset($options['locker_locations_unisend']) ? $options['locker_locations_unisend'] : ''),
        'omniva'=>labaslietas_omniva_locker_locations(),
        'latvijas-pasts'=>labaslietas_lines_to_locker_list(isset($options['locker_locations_latvijas_pasts']) ? $options['locker_locations_latvijas_pasts'] : ''),
    );
    return apply_filters('labaslietas_locker_suggestions', $defaults);
}


add_action('woocommerce_after_checkout_billing_form', function($checkout) {
    echo '<section class="labaslietas-delivery-fields"><h3>Piegādes informācija</h3>';
    echo '<div class="labaslietas-parcel-fields">';
    echo '<input type="hidden" id="labaslietas_locker_provider" name="labaslietas_locker_provider" value="' . esc_attr($checkout->get_value('labaslietas_locker_provider')) . '">';
    woocommerce_form_field('labaslietas_locker_location', array(
        'type'=>'text',
        'label'=>'Pakomāts / adrese',
        'required'=>false,
        'class'=>array('form-row-wide'),
        'custom_attributes'=>array('list'=>'labaslietas-locker-options','autocomplete'=>'off'),
        'placeholder'=>'Sāc rakstīt vai izvēlies no saraksta'
    ), $checkout->get_value('labaslietas_locker_location'));
    echo '<datalist id="labaslietas-locker-options"></datalist><p class="labaslietas-field-help">Pakomāta piegāde: 3,90 €. Izvēlies konkrēto pakomātu; ja saraksts nav ielādēts, ieraksti precīzu pakomāta nosaukumu un adresi.</p></div>';

    echo '<div class="labaslietas-courier-fields">';
    woocommerce_form_field('labaslietas_delivery_address', array('type'=>'text','label'=>'Piegādes adrese','required'=>false,'class'=>array('form-row-wide'),'placeholder'=>'Iela, mājas numurs, dzīvoklis'), $checkout->get_value('labaslietas_delivery_address'));
    woocommerce_form_field('labaslietas_delivery_city', array('type'=>'text','label'=>'Pilsēta / apdzīvota vieta','required'=>false,'class'=>array('form-row-first')), $checkout->get_value('labaslietas_delivery_city'));
    woocommerce_form_field('labaslietas_delivery_postcode', array('type'=>'text','label'=>'Pasta indekss','required'=>false,'class'=>array('form-row-last'),'placeholder'=>'LV-5001'), $checkout->get_value('labaslietas_delivery_postcode'));
    echo '<p class="labaslietas-field-help labaslietas-courier-help">UNISEND kurjers līdz 30 kg: 10,00 €. Lielgabarīta precēm Labas Lietas piegādes cena tiek saskaņota atsevišķi.</p></div>';
    echo '</section>';
});

add_filter('woocommerce_checkout_posted_data', function($data) {
    $data['billing_country'] = 'LV';
    $data['shipping_country'] = 'LV';
    return $data;
});

add_action('woocommerce_after_checkout_validation', function($data, $errors) {
    $type = isset($_POST['billing_customer_type']) ? wc_clean(wp_unslash($_POST['billing_customer_type'])) : 'physical';
    if ($type === 'company') {
        if (empty($_POST['billing_company'])) { $errors->add('billing_company', 'Lūdzu, ievadi uzņēmuma nosaukumu.'); }
        if (empty($_POST['billing_legal_address'])) { $errors->add('billing_legal_address', 'Lūdzu, ievadi juridisko adresi.'); }
    } else {
        if (empty($_POST['billing_first_name'])) { $errors->add('billing_first_name', 'Lūdzu, ievadi vārdu.'); }
        if (empty($_POST['billing_last_name'])) { $errors->add('billing_last_name', 'Lūdzu, ievadi uzvārdu.'); }
    }

    $method = '';
    if (!empty($_POST['shipping_method']) && is_array($_POST['shipping_method'])) { $method = wc_clean(reset($_POST['shipping_method'])); }
    if (labaslietas_is_parcel_shipping($method)) {
        if (empty($_POST['labaslietas_locker_location'])) { $errors->add('labaslietas_locker_location', 'Lūdzu, izvēlies vai ievadi pakomātu.'); }
    }
    if (labaslietas_is_courier_shipping($method)) {
        if (empty($_POST['labaslietas_delivery_address'])) { $errors->add('labaslietas_delivery_address', 'Lūdzu, ievadi kurjera piegādes adresi.'); }
        if (empty($_POST['labaslietas_delivery_city'])) { $errors->add('labaslietas_delivery_city', 'Lūdzu, ievadi pilsētu vai apdzīvoto vietu.'); }
        if (empty($_POST['labaslietas_delivery_postcode'])) { $errors->add('labaslietas_delivery_postcode', 'Lūdzu, ievadi pasta indeksu.'); }
    }
}, 10, 2);

add_action('woocommerce_checkout_create_order', function($order, $data) {
    $order->set_billing_country('LV');
    $order->set_shipping_country('LV');
    $keys = array('billing_customer_type','billing_company_id','billing_legal_address','labaslietas_locker_location','labaslietas_delivery_address','labaslietas_delivery_city','labaslietas_delivery_postcode');
    foreach ($keys as $key) {
        if (isset($_POST[$key])) { $order->update_meta_data('_'.$key, sanitize_textarea_field(wp_unslash($_POST[$key]))); }
    }

    $method = '';
    if (!empty($_POST['shipping_method']) && is_array($_POST['shipping_method'])) { $method = wc_clean(reset($_POST['shipping_method'])); }
    $provider = labaslietas_locker_provider_for_shipping($method);
    // Preserve the old combined parcel method if an in-flight checkout still posts it.
    if ($provider === '' && labaslietas_shipping_method_base_id($method) === 'labaslietas_parcel' && isset($_POST['labaslietas_locker_provider'])) {
        $provider = sanitize_key(wp_unslash($_POST['labaslietas_locker_provider']));
    }
    if ($provider !== '') { $order->update_meta_data('_labaslietas_locker_provider', $provider); }

    if (labaslietas_is_courier_shipping($method)) {
        $order->set_shipping_country('LV');
        $order->set_shipping_address_1(isset($_POST['labaslietas_delivery_address']) ? sanitize_text_field(wp_unslash($_POST['labaslietas_delivery_address'])) : '');
        $order->set_shipping_city(isset($_POST['labaslietas_delivery_city']) ? sanitize_text_field(wp_unslash($_POST['labaslietas_delivery_city'])) : '');
        $order->set_shipping_postcode(isset($_POST['labaslietas_delivery_postcode']) ? sanitize_text_field(wp_unslash($_POST['labaslietas_delivery_postcode'])) : '');
    } elseif (labaslietas_is_parcel_shipping($method)) {
        $order->set_shipping_country('LV');
        $order->set_shipping_address_1(isset($_POST['labaslietas_locker_location']) ? sanitize_text_field(wp_unslash($_POST['labaslietas_locker_location'])) : '');
    }
}, 10, 2);

add_action('woocommerce_admin_order_data_after_shipping_address', function($order) {
    $provider = $order->get_meta('_labaslietas_locker_provider'); $locker = $order->get_meta('_labaslietas_locker_location');
    if ($provider || $locker) { echo '<p><strong>Pakomāts:</strong><br>' . esc_html(ucwords(str_replace('-', ' ', $provider))) . '<br>' . esc_html($locker) . '</p>'; }
    $legal = $order->get_meta('_billing_legal_address'); $reg = $order->get_meta('_billing_company_id');
    if ($reg || $legal) { echo '<p><strong>Uzņēmuma rekvizīti:</strong><br>' . esc_html($reg) . ($legal ? '<br>'.esc_html($legal) : '') . '</p>'; }
});

add_filter('woocommerce_email_order_meta_fields', function($fields, $sent_to_admin, $order) {
    $provider = $order->get_meta('_labaslietas_locker_provider'); $locker = $order->get_meta('_labaslietas_locker_location');
    if ($provider || $locker) { $fields['labaslietas_locker'] = array('label'=>'Pakomāts','value'=>trim(ucwords(str_replace('-', ' ', $provider)).' '.$locker)); }
    $reg = $order->get_meta('_billing_company_id');
    $legal = $order->get_meta('_billing_legal_address');
    if ($reg) { $fields['labaslietas_company_id'] = array('label'=>'Reģ. nr. / PVN nr.','value'=>$reg); }
    if ($legal) { $fields['labaslietas_legal_address'] = array('label'=>'Juridiskā adrese','value'=>$legal); }
    return $fields;
}, 10, 3);

add_action('wp_enqueue_scripts', function() {
    if (!(function_exists('is_checkout') && is_checkout())) { return; }
    wp_add_inline_script('labaslietas-theme', 'window.LabaslietasTheme=window.LabaslietasTheme||{};window.LabaslietasTheme.lockers=' . wp_json_encode(labaslietas_locker_suggestions()) . ';', 'before');
}, 30);


/**
 * LABAS LIETAS payment methods.
 *
 * Cash and invoice/bank-transfer use WooCommerce's native COD/BACS gateways.
 * Online banking is supplied by the official EveryPay WooCommerce extension;
 * credentials intentionally stay in the plugin/WordPress settings and are never
 * stored in the theme.
 */
function labaslietas_current_shipping_method() {
    if (!empty($_POST['shipping_method']) && is_array($_POST['shipping_method'])) {
        return wc_clean(reset($_POST['shipping_method']));
    }
    if (!empty($_POST['post_data'])) {
        $posted = array();
        parse_str(wp_unslash($_POST['post_data']), $posted);
        if (!empty($posted['shipping_method']) && is_array($posted['shipping_method'])) {
            return wc_clean(reset($posted['shipping_method']));
        }
    }
    if (function_exists('WC') && WC()->session) {
        $chosen = WC()->session->get('chosen_shipping_methods');
        if (is_array($chosen) && !empty($chosen)) { return (string)reset($chosen); }
    }
    return '';
}

function labaslietas_gateway_is_everypay($gateway_id, $gateway = null) {
    $haystack = strtolower((string)$gateway_id);
    if (is_object($gateway)) {
        $haystack .= ' ' . strtolower(get_class($gateway));
        if (isset($gateway->method_title)) { $haystack .= ' ' . strtolower((string)$gateway->method_title); }
        if (isset($gateway->title)) { $haystack .= ' ' . strtolower((string)$gateway->title); }
    }
    return strpos($haystack, 'everypay') !== false || strpos($haystack, 'every-pay') !== false;
}

/** Bootstrap the two native offline payment methods once; preserve account details and other existing settings. */
function labaslietas_ensure_native_payment_methods() {
    if (!current_user_can('manage_woocommerce') && !current_user_can('manage_options')) { return; }
    $version = '2026-09-20-1';
    if (get_option('labaslietas_payment_setup_version') === $version) { return; }

    $cod = get_option('woocommerce_cod_settings', array());
    if (!is_array($cod)) { $cod = array(); }
    $cod['enabled'] = 'yes';
    $cod['title'] = 'Skaidrā naudā, saņemot preci Smiltenē';
    $cod['description'] = 'Apmaksa skaidrā naudā, saņemot preci Smiltenē.';
    $cod['instructions'] = 'Apmaksa skaidrā naudā preces saņemšanas brīdī.';
    $cod['enable_for_methods'] = array();
    update_option('woocommerce_cod_settings', $cod, false);

    $bacs = get_option('woocommerce_bacs_settings', array());
    if (!is_array($bacs)) { $bacs = array(); }
    $bacs['enabled'] = 'yes';
    $bacs['title'] = 'Bankas pārskaitījums';
    $bacs['description'] = 'Apmaksa pēc rēķina saņemšanas. Maksājuma uzdevumā lūdzam norādīt rēķina numuru.';
    $bacs['instructions'] = 'Pēc pasūtījuma saņemšanas Labas Lietas sagatavos rēķinu apmaksai.';
    update_option('woocommerce_bacs_settings', $bacs, false);

    update_option('labaslietas_payment_setup_version', $version, false);
}
add_action('admin_init', 'labaslietas_ensure_native_payment_methods', 75);
add_action('after_switch_theme', 'labaslietas_ensure_native_payment_methods', 75);

add_filter('woocommerce_gateway_title', function($title, $gateway_id) {
    if ($gateway_id === 'cod') {
        return 'Skaidrā naudā, saņemot preci Smiltenē';
    }
    if ($gateway_id === 'bacs') { return 'Bankas pārskaitījums'; }
    if (labaslietas_gateway_is_everypay($gateway_id)) { return 'Tiešsaistē ar internetbanku'; }
    return $title;
}, 50, 2);

add_filter('woocommerce_gateway_description', function($description, $gateway_id) {
    if ($gateway_id === 'cod') {
        return 'Pieejams tikai, izvēloties saņemšanu Smiltenē.';
    }
    if ($gateway_id === 'bacs') {
        return 'Apmaksa pēc rēķina saņemšanas; maksājuma uzdevumā lūdzam norādīt rēķina numuru.';
    }
    if (labaslietas_gateway_is_everypay($gateway_id)) {
        return 'Drošs tiešsaistes maksājums ar EveryPay / Swedbank maksājumu vārteju.';
    }
    return $description;
}, 50, 2);

/** Cash is valid only for warehouse pickup; put payment methods in the client-requested order. */
add_filter('woocommerce_available_payment_gateways', function($gateways) {
    if (!is_array($gateways) || empty($gateways)) { return $gateways; }

    $shipping = labaslietas_shipping_method_base_id(labaslietas_current_shipping_method());
    if (isset($gateways['cod']) && $shipping !== 'labaslietas_pickup') { unset($gateways['cod']); }

    $rank = array();
    foreach ($gateways as $id => $gateway) {
        if ($id === 'cod') { $rank[$id] = 10; }
        elseif ($id === 'bacs') { $rank[$id] = 20; }
        elseif (labaslietas_gateway_is_everypay($id, $gateway)) { $rank[$id] = 30; }
        else { $rank[$id] = 100; }
    }
    uasort($gateways, function($a, $b) use ($rank) {
        $a_id = isset($a->id) ? $a->id : '';
        $b_id = isset($b->id) ? $b->id : '';
        $a_rank = isset($rank[$a_id]) ? $rank[$a_id] : 100;
        $b_rank = isset($rank[$b_id]) ? $rank[$b_id] : 100;
        if ($a_rank === $b_rank) { return 0; }
        return $a_rank < $b_rank ? -1 : 1;
    });
    return $gateways;
}, 50);

/** Show a targeted setup reminder only on WooCommerce payment settings when EveryPay is not installed/active. */
add_action('admin_notices', function() {
    if (!current_user_can('manage_woocommerce') && !current_user_can('manage_options')) { return; }
    if (empty($_GET['page']) || $_GET['page'] !== 'wc-settings') { return; }
    $tab = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : '';
    if ($tab !== 'checkout' && $tab !== 'payment-gateways') { return; }
    if (!function_exists('WC') || !WC()->payment_gateways()) { return; }

    foreach ((array)WC()->payment_gateways()->payment_gateways() as $id => $gateway) {
        if (labaslietas_gateway_is_everypay($id, $gateway)) { return; }
    }
    echo '<div class="notice notice-warning"><p><strong>Labas Lietas — tiešsaistes maksājumi:</strong> instalē un aktivizē oficiālo EveryPay WooCommerce spraudni, pēc tam ievadi TEST/LIVE API username, API secret un processing account no EveryPay Merchant Portal. Portāla pieslēgšanās paroli tēmā neievada. <a href="https://support.every-pay.com/lv/articles/13784439-woocommerce-extension" target="_blank" rel="noopener noreferrer">EveryPay WooCommerce instrukcija</a>.</p></div>';
});

/* Keep WooCommerce's no-gateway state readable and in Latvian. Gateway activation remains an admin/payment-provider setting. */
add_filter('woocommerce_no_available_payment_methods_message', function($message) {
    return 'Pašlaik nav pieejama neviena maksājuma metode. Lūdzu, sazinieties ar Labas Lietas, lai vienotos par apmaksu.';
});
