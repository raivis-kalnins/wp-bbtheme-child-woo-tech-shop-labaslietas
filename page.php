<?php
/** Generic LABAS LIETAS page template with WooCommerce core page fallbacks. */
defined('ABSPATH') || exit;
if (function_exists('is_cart') && is_cart()) { get_template_part('page', 'cart'); return; }
if (function_exists('is_checkout') && is_checkout() && !is_wc_endpoint_url()) { get_template_part('page', 'checkout'); return; }
get_header(); ?>
<main class="labaslietas-page-shell labaslietas-container">
<?php if (function_exists('labaslietas_seo_breadcrumbs_235')) { echo labaslietas_seo_breadcrumbs_235(); } ?>
<?php while (have_posts()) : the_post();
    $slug = get_post_field('post_name', get_the_ID());
    $is_info = function_exists('labaslietas_information_page_definitions') && array_key_exists($slug, labaslietas_information_page_definitions());
?>
  <header class="labaslietas-page-heading<?php echo $is_info ? ' labaslietas-info-heading' : ''; ?>">
    <?php if ($is_info) : ?><span class="labaslietas-page-kicker">LABAS LIETAS.LV</span><?php endif; ?>
    <h1><?php the_title(); ?></h1>
  </header>
  <article <?php post_class('labaslietas-page-card'); ?>>
    <div class="entry-content"><?php the_content(); ?></div>
  </article>
<?php endwhile; ?>
</main>
<?php get_footer();
