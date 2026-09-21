<?php
/** Product-first search page with SKU and ID support. */
defined('ABSPATH') || exit;
get_header();
$term = get_search_query();
$ids = array();
if ($term !== '' && class_exists('WooCommerce')) {
    if (ctype_digit($term) && get_post_type(absint($term)) === 'product') {
        $ids[] = absint($term);
    }
    global $wpdb;
    $sku_ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_sku' AND meta_value LIKE %s LIMIT 80", '%' . $wpdb->esc_like($term) . '%'));
    $ids = array_merge($ids, array_map('absint', (array) $sku_ids));
    $text_q = new WP_Query(array('post_type'=>'product','s'=>$term,'posts_per_page'=>80,'post_status'=>'publish','fields'=>'ids'));
    if ($text_q->posts) { $ids = array_merge($ids, array_map('absint', $text_q->posts)); }
    wp_reset_postdata();
}
$ids = array_values(array_unique(array_filter($ids)));
?>
<main class="labaslietas-search-page">
  <div class="labaslietas-container">
    <section class="labaslietas-search-hero">
      <h1><?php echo esc_html(sprintf(__('Meklēšana: %s', 'labaslietas'), $term)); ?></h1>
      <p><?php esc_html_e('Rezultāti pēc produkta nosaukuma, SKU un produkta ID.', 'labaslietas'); ?></p>
      <form role="search" method="get" class="labaslietas-search labaslietas-ajax-search" action="<?php echo esc_url(home_url('/')); ?>" autocomplete="off">
        <div class="labaslietas-search-field-wrap"><input type="search" name="s" value="<?php echo esc_attr($term); ?>" placeholder="Meklēt produktus, SKU vai ID..."><div class="labaslietas-search-results" hidden></div></div>
        <input type="hidden" name="post_type" value="product"><button type="submit" aria-label="Meklēt">⌕</button>
      </form>
    </section>
    <?php if (!empty($ids) && class_exists('WooCommerce')) : ?>
      <div class="labaslietas-product-grid" style="--labaslietas-cols:<?php echo esc_attr(labaslietas_get_theme_option('products_per_row', '4')); ?>">
        <?php foreach ($ids as $id) { $product = wc_get_product($id); if ($product && $product->get_status() === 'publish') { echo labaslietas_product_card($product); } } ?>
      </div>
    <?php else : ?>
      <section class="labaslietas-404-card"><h2><?php esc_html_e('Nekas netika atrasts', 'labaslietas'); ?></h2><p><?php esc_html_e('Pamēģini citu nosaukumu, SKU vai produkta ID.', 'labaslietas'); ?></p><div class="labaslietas-404-actions"><a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/')); ?>"><?php esc_html_e('Visas preces', 'labaslietas'); ?></a></div></section>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
