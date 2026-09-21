<?php
/** LABAS LIETAS V13 checkout template, Bootstrap-friendly and PHP 7.4 compatible. */
defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>
<div class="labaslietas-checkout-page labaslietas-container container">
    <div class="labaslietas-checkout-title-row">
        <div>
            <h1><?php esc_html_e('Noformēt pasūtījumu', 'labaslietas'); ?></h1>
            <p><?php esc_html_e('Aizpildi piegādes informāciju un pārbaudi pasūtījuma kopsavilkumu.', 'labaslietas'); ?></p>
        </div>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="labaslietas-cart-continue"><?php esc_html_e('Atpakaļ uz grozu', 'labaslietas'); ?></a>
    </div>
    <form name="checkout" method="post" class="checkout woocommerce-checkout labaslietas-checkout-form" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__('Checkout', 'woocommerce'); ?>">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-7 labaslietas-checkout-fields-col">
                <div class="labaslietas-checkout-card">
                    <?php if ($checkout->get_checkout_fields()) : ?>
                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>
                        <div class="customer_details" id="customer_details">
                            <?php do_action('woocommerce_checkout_billing'); ?>
                            <?php do_action('woocommerce_checkout_shipping'); ?>
                        </div>
                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12 col-lg-5 labaslietas-checkout-review-col">
                <div class="labaslietas-checkout-card labaslietas-order-card">
                    <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
                    <h3 id="order_review_heading"><?php esc_html_e('Jūsu pasūtījums', 'labaslietas'); ?></h3>
                    <?php do_action('woocommerce_checkout_before_order_review'); ?>
                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action('woocommerce_checkout_order_review'); ?>
                    </div>
                    <?php do_action('woocommerce_checkout_after_order_review'); ?>
                </div>
            </div>
        </div>
    </form>
</div>
<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
