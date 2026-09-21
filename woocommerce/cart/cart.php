<?php
/** LABAS LIETAS V19 clean legacy cart template, card layout. PHP 7.4 compatible. */
defined('ABSPATH') || exit;
do_action('woocommerce_before_cart');
?>
<div class="labaslietas-cart-page labaslietas-container container">
  <div class="labaslietas-cart-title-row">
    <div><h1><?php esc_html_e('Grozs', 'labaslietas'); ?></h1><p><?php esc_html_e('Pārskati preces un daudzumu pirms pasūtījuma noformēšanas.', 'labaslietas'); ?></p></div>
    <a class="labaslietas-cart-continue" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Turpināt iepirkties', 'labaslietas'); ?></a>
  </div>

  <div class="labaslietas-cart-layout">
    <form class="woocommerce-cart-form labaslietas-cart-card" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
      <?php do_action('woocommerce_before_cart_table'); ?>
      <div class="labaslietas-cart-head-row" aria-hidden="true"><span><?php esc_html_e('Produkts', 'woocommerce'); ?></span><span><?php esc_html_e('Cena', 'woocommerce'); ?></span><span><?php esc_html_e('Daudzums', 'woocommerce'); ?></span><span><?php esc_html_e('Summa', 'woocommerce'); ?></span></div>
      <div class="labaslietas-cart-items">
      <?php do_action('woocommerce_before_cart_contents'); ?>
      <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
        if (!$_product instanceof WC_Product || !$_product->exists() || $cart_item['quantity'] <= 0 || !apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) { continue; }
        $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
      ?>
        <div class="labaslietas-cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
          <div class="labaslietas-cart-product">
            <?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a role="button" href="%s" class="remove labaslietas-cart-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>', esc_url(wc_get_cart_remove_url($cart_item_key)), esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))), esc_attr($product_id), esc_attr($_product->get_sku())), $cart_item_key); ?>
            <div class="labaslietas-cart-thumb"><?php $thumb = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key); echo $product_permalink ? '<a href="'.esc_url($product_permalink).'">'.$thumb.'</a>' : $thumb; ?></div>
            <div class="labaslietas-cart-name">
              <?php echo $product_permalink ? wp_kses_post(sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name())) : wp_kses_post($product_name); ?>
              <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
            </div>
          </div>
          <div class="labaslietas-cart-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>"><?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?></div>
          <div class="labaslietas-cart-qty" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
            <?php
              $min_quantity = $_product->is_sold_individually() ? 1 : 0;
              $max_quantity = $_product->is_sold_individually() ? 1 : $_product->get_max_purchase_quantity();
              echo apply_filters('woocommerce_cart_item_quantity', woocommerce_quantity_input(array('input_name' => "cart[{$cart_item_key}][qty]", 'input_value' => $cart_item['quantity'], 'max_value' => $max_quantity, 'min_value' => $min_quantity, 'product_name' => $product_name), $_product, false), $cart_item_key, $cart_item);
            ?>
          </div>
          <div class="labaslietas-cart-subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>"><?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?></div>
        </div>
      <?php endforeach; ?>
      <?php do_action('woocommerce_cart_contents'); ?>
      <?php do_action('woocommerce_after_cart_contents'); ?>
      </div>
      <div class="labaslietas-cart-actions">
        <?php if (wc_coupons_enabled()) : ?>
          <div class="coupon labaslietas-cart-coupon"><input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" /><button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_html_e('Apply coupon', 'woocommerce'); ?></button><?php do_action('woocommerce_cart_coupon'); ?></div>
        <?php endif; ?>
        <button type="submit" class="button labaslietas-update-cart" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>
        <?php do_action('woocommerce_cart_actions'); wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
      </div>
      <?php do_action('woocommerce_after_cart_table'); ?>
    </form>
    <aside class="labaslietas-cart-summary-card"><?php do_action('woocommerce_before_cart_collaterals'); ?><div class="cart-collaterals"><?php do_action('woocommerce_cart_collaterals'); ?></div></aside>
  </div>
</div>
<?php do_action('woocommerce_after_cart'); ?>
