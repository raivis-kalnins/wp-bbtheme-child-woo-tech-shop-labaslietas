<?php
/**
 * LABAS LIETAS mini cart override.
 * Keeps WooCommerce hooks/filters while using a stable full-width drawer layout.
 */
defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart');

if (WC()->cart && !WC()->cart->is_empty()) : ?>
    <ul class="woocommerce-mini-cart cart_list product_list_widget labaslietas-mini-cart-list">
        <?php do_action('woocommerce_before_mini_cart_contents'); ?>
        <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
            $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
            if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0 || !apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
                continue;
            }
            $product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
            $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
        ?>
            <li class="woocommerce-mini-cart-item mini_cart_item labaslietas-mini-cart-item">
                <?php echo apply_filters(
                    'woocommerce_cart_item_remove_link',
                    sprintf(
                        '<a role="button" href="%s" class="remove remove_from_cart_button labaslietas-mini-cart-remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                        esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                        esc_attr($product_id),
                        esc_attr($cart_item_key),
                        esc_attr($_product->get_sku())
                    ),
                    $cart_item_key
                ); ?>

                <div class="labaslietas-mini-cart-product">
                    <?php if ($product_permalink) : ?>
                        <a class="labaslietas-mini-cart-thumb labaslietas-mini-cart-thumb-link" href="<?php echo esc_url($product_permalink); ?>" aria-label="<?php echo esc_attr(wp_strip_all_tags($product_name)); ?>"><?php echo $thumbnail; ?></a>
                    <?php else : ?>
                        <span class="labaslietas-mini-cart-thumb"><?php echo $thumbnail; ?></span>
                    <?php endif; ?>

                    <div class="labaslietas-mini-cart-copy">
                        <?php if ($product_permalink) : ?>
                            <a class="labaslietas-mini-cart-title" href="<?php echo esc_url($product_permalink); ?>"><?php echo wp_kses_post($product_name); ?></a>
                        <?php else : ?>
                            <span class="labaslietas-mini-cart-title"><?php echo wp_kses_post($product_name); ?></span>
                        <?php endif; ?>
                        <span class="labaslietas-mini-cart-meta"><?php echo esc_html($cart_item['quantity']); ?> × <?php echo wp_kses_post($product_price); ?></span>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
        <?php do_action('woocommerce_mini_cart_contents'); ?>
    </ul>

    <p class="woocommerce-mini-cart__total total labaslietas-mini-cart-total-row">
        <span><?php esc_html_e('Subtotal', 'woocommerce'); ?></span>
        <strong><?php echo WC()->cart->get_cart_subtotal(); ?></strong>
    </p>

    <?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>
    <p class="woocommerce-mini-cart__buttons buttons labaslietas-mini-cart-buttons"><?php do_action('woocommerce_widget_shopping_cart_buttons'); ?></p>
    <?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>
<?php else : ?>
    <div class="labaslietas-mini-cart-empty">
        <span class="labaslietas-mini-cart-empty-icon" aria-hidden="true">🛒</span>
        <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>
        <a class="button labaslietas-mini-cart-shop" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Continue shopping', 'woocommerce'); ?></a>
    </div>
<?php endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>
