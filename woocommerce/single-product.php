<?php
/** Labas Lietas 3.0.5 single product template. */
defined('ABSPATH') || exit;
get_header();
do_action('woocommerce_before_single_product');
while (have_posts()) : the_post();
    global $product;
    if (!$product || !is_a($product, 'WC_Product')) { $product = wc_get_product(get_the_ID()); }
    if (!$product) { continue; }
    $product_id = $product->get_id();
    $stock_qty = $product->get_manage_stock() ? $product->get_stock_quantity() : null;
?>
<main class="llg-single-page">
  <div class="labaslietas-container llg-single-container">
    <?php woocommerce_output_all_notices(); ?>
    <div class="llg-single-breadcrumbs"><?php echo function_exists('labaslietas_seo_breadcrumbs_235') ? labaslietas_seo_breadcrumbs_235() : woocommerce_breadcrumb(); ?></div>
    <article id="product-<?php the_ID(); ?>" <?php wc_product_class('llg-single-product-card', $product); ?>>
      <section class="llg-single-gallery-panel">
        <?php if ($product->is_on_sale()) : ?><span class="llg-single-sale">Akcija</span><?php endif; ?>
        <?php echo function_exists('labaslietas_render_v38_product_gallery') ? labaslietas_render_v38_product_gallery($product) : ''; ?>
      </section>
      <section class="llg-single-summary-panel">
        <div class="llg-single-status-row">
          <span class="llg-single-stock <?php echo $product->is_in_stock() ? 'is-in' : 'is-out'; ?>"><?php echo $product->is_in_stock() ? 'Ir noliktavā' : 'Nav noliktavā'; ?><?php if ($stock_qty !== null && $product->is_in_stock()) : ?> (<?php echo (int)$stock_qty; ?>)<?php endif; ?></span>
          <?php if ($product->get_sku()) : ?><span class="llg-single-sku">SKU: <?php echo esc_html($product->get_sku()); ?></span><?php endif; ?>
        </div>
        <h1 class="product_title entry-title"><?php echo esc_html($product->get_name()); ?></h1>
        <?php if (wc_review_ratings_enabled() && $product->get_average_rating() > 0) : ?><div class="llg-single-rating"><?php echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()); ?></div><?php endif; ?>
        <div class="llg-single-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
        <?php if ($product->get_short_description()) : ?><div class="llg-single-short"><?php echo wp_kses_post(wpautop($product->get_short_description())); ?></div><?php endif; ?>

        <div class="llg-single-buybox">
          <?php woocommerce_template_single_add_to_cart(); ?>
        </div>

        <div class="llg-single-meta-row">
          <?php woocommerce_template_single_meta(); ?>
        </div>

        <div class="llg-single-trust-grid">
          <div><span><?php echo function_exists('labaslietas_green_icon') ? labaslietas_green_icon('truck') : ''; ?></span><strong>Ātra piegāde</strong><small>Pakomāti un kurjers visā Latvijā</small></div>
          <div><span><?php echo function_exists('labaslietas_green_icon') ? labaslietas_green_icon('store') : ''; ?></span><strong>Saņemšana Smiltenē</strong><small>Bezmaksas pēc vienošanās</small></div>
          <div><span><?php echo function_exists('labaslietas_green_icon') ? labaslietas_green_icon('shield') : ''; ?></span><strong>Drošs pirkums</strong><small>Garantija un skaidri noteikumi</small></div>
        </div>
      </section>
    </article>

    <section class="llg-single-tabs">
      <?php woocommerce_output_product_data_tabs(); ?>
    </section>

    <?php if (function_exists('labaslietas_render_single_product_section')) : ?>
      <?php labaslietas_render_single_product_section($product_id, 'recommended'); ?>
      <?php labaslietas_render_single_product_section($product_id, 'related'); ?>
    <?php else : ?>
      <section class="llg-related-native"><?php woocommerce_output_related_products(); ?></section>
    <?php endif; ?>
  </div>
</main>
<?php endwhile; do_action('woocommerce_after_single_product'); get_footer();
