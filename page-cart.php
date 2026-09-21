<?php
/** LABAS LIETAS V16 dedicated legacy cart page wrapper. */
defined('ABSPATH') || exit;
get_header();
?>
<main class="labaslietas-cart-shell labaslietas-page-shell">
  <?php echo do_shortcode('[woocommerce_cart]'); ?>
</main>
<?php get_footer();
