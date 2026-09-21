<?php
/** LABAS LIETAS V18 order tracking page. */
defined('ABSPATH') || exit;
get_header(); ?>
<main class="labaslietas-track-page labaslietas-container">
  <section class="labaslietas-track-card">
    <div class="labaslietas-track-copy">
      <span class="labaslietas-eyebrow">LABAS LIETAS.LV</span>
      <h1><?php esc_html_e('Sekot pasūtījumam', 'labaslietas'); ?></h1>
      <p><?php esc_html_e('Ievadi pasūtījuma numuru un e-pasta adresi, lai pārbaudītu pasūtījuma statusu.', 'labaslietas'); ?></p>
    </div>
    <div class="labaslietas-track-form">
      <?php echo do_shortcode('[woocommerce_order_tracking]'); ?>
    </div>
  </section>
</main>
<?php get_footer();
