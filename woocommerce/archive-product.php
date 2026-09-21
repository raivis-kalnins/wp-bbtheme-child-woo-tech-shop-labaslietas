<?php
/**
 * LABAS LIETAS WooCommerce archive/category template.
 * Uses the green storefront cards and avoids empty sidebar columns.
 */
defined('ABSPATH') || exit;
get_header();
$sidebar_enabled = labaslietas_get_theme_option('archive_sidebar', '1') === '1';
$sidebar_html = $sidebar_enabled && function_exists('labaslietas_wc_archive_sidebar') ? trim((string) labaslietas_wc_archive_sidebar()) : '';
$show_sidebar = $sidebar_enabled && $sidebar_html !== '';
?>
<main class="labaslietas-archive-page labaslietas-archive-v27 llg-archive-page">
    <div class="labaslietas-container container">
        <?php woocommerce_output_all_notices(); ?>
        <section class="labaslietas-archive-hero llg-archive-hero card border-0 shadow-sm">
            <div>
                <?php echo function_exists('labaslietas_seo_breadcrumbs_235') ? labaslietas_seo_breadcrumbs_235() : ''; ?>
                <h1><?php echo esc_html(labaslietas_wc_archive_title()); ?></h1>
                <?php echo labaslietas_wc_archive_description(); ?>
            </div>
            <div class="labaslietas-archive-hero-badges">
                <span>Ātra piegāde</span>
                <span>Oficiālie zīmoli</span>
                <span>14 dienu atgriešana</span>
            </div>
        </section>

        <div class="labaslietas-archive-layout row g-4 align-items-start <?php echo $show_sidebar ? '' : 'labaslietas-no-sidebar'; ?>">
            <?php if ($show_sidebar) : ?>
            <aside class="col-12 col-lg-3 labaslietas-archive-filter-col">
                <?php echo $sidebar_html; ?>
            </aside>
            <?php endif; ?>

            <section class="col-12 <?php echo $show_sidebar ? 'col-lg-9' : 'col-lg-12'; ?> labaslietas-archive-products-col">
                <div class="labaslietas-archive-toolbar card border-0 shadow-sm d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3">
                    <div class="labaslietas-result-count"><?php woocommerce_result_count(); ?></div>
                    <div class="labaslietas-ordering"><?php woocommerce_catalog_ordering(); ?></div>
                </div>

                <?php if (woocommerce_product_loop()) : ?>
                    <div class="labaslietas-bootstrap-products" id="labaslietas-archive-products">
                    <?php while (have_posts()) : the_post(); global $product; ?>
                        <div class="labaslietas-bs-product-col">
                            <?php
                            if (function_exists('labaslietas_green_product_card')) {
                                echo labaslietas_green_product_card($product);
                            } else {
                                echo labaslietas_product_card($product);
                            }
                            ?>
                        </div>
                    <?php endwhile; ?>
                    </div>
                    <?php if (function_exists('labaslietas_v27_archive_load_more_button')) { echo labaslietas_v27_archive_load_more_button(); } ?>
                    <div class="labaslietas-archive-pagination"><?php woocommerce_pagination(); ?></div>
                <?php else : ?>
                    <div class="labaslietas-no-products card border-0 shadow-sm"><?php wc_get_template('loop/no-products-found.php'); ?></div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>
<?php get_footer(); ?>
