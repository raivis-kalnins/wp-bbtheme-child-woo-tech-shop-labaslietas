<?php
/** Labas Lietas 3.0.12 My Account structure. */
defined('ABSPATH') || exit;
?>
<div class="llg-myaccount-layout">
    <aside class="llg-myaccount-nav-card"><?php do_action('woocommerce_account_navigation'); ?></aside>
    <section class="woocommerce-MyAccount-content llg-myaccount-content-card">
        <?php do_action('woocommerce_account_content'); ?>
    </section>
</div>
