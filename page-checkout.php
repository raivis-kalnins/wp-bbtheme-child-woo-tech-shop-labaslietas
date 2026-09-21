<?php
/** LABAS LIETAS V16 dedicated legacy checkout page wrapper. */
defined('ABSPATH') || exit;
get_header();
?>
<main class="labaslietas-checkout-shell labaslietas-page-shell">
  <?php echo do_shortcode('[woocommerce_checkout]'); ?>
</main>
<?php get_footer();
