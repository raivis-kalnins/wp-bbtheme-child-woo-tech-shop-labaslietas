<?php
/** Labas Lietas My Account wrapper. */
defined('ABSPATH') || exit;
get_header(); ?>
<main class="llg-commerce-shell llg-account-shell">
  <div class="labaslietas-container">
    <header class="llg-commerce-heading"><span>LABAS LIETAS</span><h1><?php echo is_user_logged_in() ? esc_html__('Mans konts','labaslietas') : esc_html__('Klienta konts','labaslietas'); ?></h1><p><?php echo is_user_logged_in() ? esc_html__('Pārvaldi pasūtījumus, adreses un konta informāciju.','labaslietas') : esc_html__('Pieslēdzies vai izveido kontu ērtākai iepirkšanai.','labaslietas'); ?></p></header>
    <div class="llg-commerce-card"><?php echo do_shortcode('[woocommerce_my_account]'); ?></div>
  </div>
</main>
<?php get_footer();
