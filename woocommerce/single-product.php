<?php
/**
 * LABAS LIETAS V13 centered Bootstrap/IWS-style single product template.
 * Uses WooCommerce native gallery output so zoom, slider and lightbox theme support can work.
 * PHP 7.4 compatible.
 */
defined('ABSPATH') || exit;
/**
 * LABAS LIETAS V25 custom single product gallery.
 * Avoids WooCommerce native gallery duplication and provides one clean lightbox with thumbnails.
 */
if (!function_exists('labaslietas_render_v31_product_gallery')) {
    /**
     * Stable custom product gallery: no Woo native duplication, no flexslider dependency.
     * Each thumb includes thumb/main/full URLs so JS can always fall back to a visible image.
     */
    function labaslietas_render_v31_product_gallery($product) {
        if (!$product || !is_a($product, 'WC_Product')) { return; }
        $ids = array();
        $main_id = $product->get_image_id();
        if ($main_id) { $ids[] = $main_id; }
        foreach ((array) $product->get_gallery_image_ids() as $gid) {
            if ($gid && !in_array($gid, $ids, true)) { $ids[] = $gid; }
        }
        if (empty($ids)) {
            echo '<div class="labaslietas-iws-gallery-custom labaslietas-gallery-v31"><div class="labaslietas-iws-gallery-main"><img class="labaslietas-iws-main-img" src="' . esc_url(wc_placeholder_img_src('woocommerce_single')) . '" alt="" /></div></div>';
            return;
        }
        $items = array();
        foreach ($ids as $id) {
            $thumb = wp_get_attachment_image_url($id, 'woocommerce_gallery_thumbnail');
            $main = wp_get_attachment_image_url($id, 'full');
            $large = wp_get_attachment_image_url($id, 'large');
            $single = wp_get_attachment_image_url($id, 'woocommerce_single');
            $full = wp_get_attachment_image_url($id, 'full');
            $src = $full ? $full : ($large ? $large : ($single ? $single : ($main ? $main : $thumb)));
            $modal = $full ? $full : ($large ? $large : $src);
            if (!$thumb) { $thumb = $src; }
            $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
            if (!$alt) { $alt = $product->get_name(); }
            if ($src) { $items[] = array('thumb'=>$thumb, 'main'=>$src, 'full'=>$modal, 'alt'=>$alt); }
        }
        if (empty($items)) { return; }
        $first = $items[0];
        echo '<div class="labaslietas-iws-gallery-custom labaslietas-gallery-v31" data-active="0" data-gallery-count="' . esc_attr(count($items)) . '">';
        echo '<button type="button" class="labaslietas-gallery-zoom" aria-label="Atvērt lielo attēlu"></button>';
        echo '<div class="labaslietas-iws-gallery-main" aria-live="polite">';
        echo '<img class="labaslietas-iws-main-img" src="' . esc_url($first['main']) . '" data-main="' . esc_url($first['main']) . '" data-full="' . esc_url($first['full']) . '" alt="' . esc_attr($first['alt']) . '" decoding="async" loading="eager" />';
        echo '</div>';
        if (count($items) > 1) {
            echo '<div class="labaslietas-iws-gallery-thumbs" role="list" aria-label="Produkta attēli">';
            foreach ($items as $idx => $item) {
                echo '<button type="button" class="labaslietas-iws-thumb' . ($idx === 0 ? ' is-active' : '') . '" data-index="' . esc_attr($idx) . '" data-main="' . esc_url($item['main']) . '" data-large="' . esc_url($item['main']) . '" data-full="' . esc_url($item['full']) . '" data-thumb="' . esc_url($item['thumb']) . '" aria-pressed="' . ($idx === 0 ? 'true' : 'false') . '" aria-label="Attēls ' . esc_attr($idx + 1) . '">';
                echo '<img src="' . esc_url($item['thumb']) . '" alt="' . esc_attr($item['alt']) . '" decoding="async" loading="lazy" />';
                echo '</button>';
            }
            echo '</div>';
        }
        echo '</div>';
    }
}


if (!function_exists('labaslietas_render_v33_product_gallery')) {
    /**
     * V33 isolated product gallery. Uses unique class names so old Woo/Flex/gallery CSS cannot override it.
     * Main image uses WooCommerce single/large sizes, modal uses full size, thumbnails are fixed horizontal buttons.
     */
    function labaslietas_render_v33_product_gallery($product) {
        if (!$product || !is_a($product, 'WC_Product')) { return; }
        $ids = array();
        $main_id = $product->get_image_id();
        if ($main_id) { $ids[] = $main_id; }
        foreach ((array) $product->get_gallery_image_ids() as $gid) {
            if ($gid && !in_array($gid, $ids, true)) { $ids[] = $gid; }
        }
        if (empty($ids)) {
            echo '<div class="labaslietas-product-gallery-v33"><figure class="labaslietas-v33-main"><img src="' . esc_url(wc_placeholder_img_src('woocommerce_single')) . '" alt="" /></figure></div>';
            return;
        }
        $items = array();
        foreach ($ids as $id) {
            $thumb = wp_get_attachment_image_url($id, 'woocommerce_gallery_thumbnail');
            $single = wp_get_attachment_image_url($id, 'woocommerce_single');
            $large = wp_get_attachment_image_url($id, 'large');
            $full = wp_get_attachment_image_url($id, 'full');
            $main = $single ? $single : ($large ? $large : ($full ? $full : $thumb));
            $modal = $full ? $full : ($large ? $large : ($main ? $main : $thumb));
            if (!$thumb) { $thumb = $main; }
            if (!$main) { continue; }
            $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
            if (!$alt) { $alt = $product->get_name(); }
            $items[] = array('id' => $id, 'thumb' => $thumb, 'main' => $main, 'full' => $modal, 'alt' => $alt);
        }
        if (empty($items)) { return; }
        $first = $items[0];
        echo '<div class="labaslietas-product-gallery-v33" data-active="0">';
        echo '<button type="button" class="labaslietas-v33-zoom" aria-label="Atvērt attēlu"></button>';
        echo '<figure class="labaslietas-v33-main">';
        echo '<img class="labaslietas-v33-main-img" src="' . esc_url($first['main']) . '" data-full="' . esc_url($first['full']) . '" alt="' . esc_attr($first['alt']) . '" loading="eager" decoding="async" />';
        echo '</figure>';
        echo '<div class="labaslietas-v33-thumbs" role="list" aria-label="Produkta attēli">';
        foreach ($items as $idx => $item) {
            echo '<button type="button" class="labaslietas-v33-thumb' . ($idx === 0 ? ' is-active' : '') . '" data-index="' . esc_attr($idx) . '" data-main="' . esc_url($item['main']) . '" data-full="' . esc_url($item['full']) . '" aria-pressed="' . ($idx === 0 ? 'true' : 'false') . '">';
            echo '<img src="' . esc_url($item['thumb']) . '" alt="' . esc_attr($item['alt']) . '" loading="lazy" decoding="async" />';
            echo '</button>';
        }
        echo '</div>';
        echo '</div>';
    }
}


if (!function_exists('labaslietas_render_v34_product_gallery')) {
    /**
     * V34 hard-isolated IWS-style gallery.
     * Inline layout + inline JS avoids old WooCommerce/FlexSlider/cache CSS conflicts.
     */
    function labaslietas_render_v34_product_gallery($product) {
        if (!$product || !is_a($product, 'WC_Product')) { return; }
        $ids = array();
        $main_id = $product->get_image_id();
        if ($main_id) { $ids[] = $main_id; }
        foreach ((array) $product->get_gallery_image_ids() as $gid) {
            if ($gid && !in_array($gid, $ids, true)) { $ids[] = $gid; }
        }
        $items = array();
        if (empty($ids)) {
            $items[] = array(
                'main' => wc_placeholder_img_src('woocommerce_single'),
                'full' => wc_placeholder_img_src('full'),
                'thumb' => wc_placeholder_img_src('woocommerce_gallery_thumbnail'),
                'alt' => $product->get_name(),
            );
        } else {
            foreach ($ids as $id) {
                $thumb = wp_get_attachment_image_url($id, 'woocommerce_gallery_thumbnail');
                $medium = wp_get_attachment_image_url($id, 'medium_large');
                $single = wp_get_attachment_image_url($id, 'woocommerce_single');
                $large = wp_get_attachment_image_url($id, 'large');
                $full = wp_get_attachment_image_url($id, 'full');
                $main = $large ? $large : ($single ? $single : ($medium ? $medium : ($full ? $full : $thumb)));
                $modal = $full ? $full : ($large ? $large : ($single ? $single : $main));
                if (!$thumb) { $thumb = $main; }
                if (!$main) { continue; }
                $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                if (!$alt) { $alt = $product->get_name(); }
                $items[] = array('main' => $main, 'full' => $modal, 'thumb' => $thumb, 'alt' => $alt);
            }
        }
        if (empty($items)) { return; }
        $uid = 'labaslietas-v34-gallery-' . absint($product->get_id());
        $first = $items[0];
        echo '<div id="' . esc_attr($uid) . '" class="labaslietas-v34-gallery" data-active="0" style="width:100%;position:relative;display:block;">';
        echo '<button type="button" class="labaslietas-v34-zoom" aria-label="Atvērt lielo attēlu" style="position:absolute;right:18px;top:18px;width:46px;height:46px;border:0;border-radius:999px;background:#111827;color:#fff;z-index:4;display:flex;align-items:center;justify-content:center;box-shadow:0 14px 30px rgba(15,23,42,.24);cursor:pointer;font-size:0;">⌕</button>';
        echo '<figure class="labaslietas-v34-main" style="margin:0;width:100%;height:500px;min-height:420px;border:1px solid #e4ebf5;border-radius:24px;background:#fff center center/contain no-repeat;display:flex;align-items:center;justify-content:center;overflow:hidden;padding:24px;box-sizing:border-box;" data-bg="' . esc_url($first['main']) . '">';
        echo '<img class="labaslietas-v34-main-img" src="' . esc_url($first['main']) . '" data-full="' . esc_url($first['full']) . '" alt="' . esc_attr($first['alt']) . '" loading="eager" decoding="async" style="display:block!important;max-width:100%!important;max-height:100%!important;width:auto!important;height:auto!important;object-fit:contain!important;object-position:center!important;margin:auto!important;opacity:1!important;visibility:visible!important;position:static!important;" />';
        echo '</figure>';
        if (count($items) > 1) {
            echo '<div class="labaslietas-v34-thumbs" style="display:flex!important;flex-direction:row!important;flex-wrap:nowrap!important;gap:10px;width:100%;overflow-x:auto;overflow-y:hidden;padding:12px 2px 4px;margin:0;box-sizing:border-box;align-items:center;">';
            foreach ($items as $i => $item) {
                echo '<button type="button" class="labaslietas-v34-thumb' . ($i === 0 ? ' is-active' : '') . '" data-index="' . esc_attr($i) . '" data-main="' . esc_url($item['main']) . '" data-full="' . esc_url($item['full']) . '" data-thumb="' . esc_url($item['thumb']) . '" aria-label="Attēls ' . esc_attr($i + 1) . '" style="flex:0 0 78px!important;width:78px!important;height:70px!important;min-width:78px!important;min-height:70px!important;max-width:78px!important;max-height:70px!important;margin:0!important;padding:6px!important;border:1px solid ' . ($i === 0 ? '#2f8b49' : '#dfe7f1') . ';border-radius:12px;background:#fff;display:inline-flex!important;align-items:center;justify-content:center;overflow:hidden;box-sizing:border-box;cursor:pointer;">';
                echo '<img src="' . esc_url($item['thumb']) . '" alt="' . esc_attr($item['alt']) . '" loading="lazy" decoding="async" style="display:block!important;width:100%!important;height:100%!important;max-width:100%!important;max-height:100%!important;object-fit:contain!important;object-position:center!important;margin:0!important;padding:0!important;opacity:1!important;visibility:visible!important;position:static!important;" />';
                echo '</button>';
            }
            echo '</div>';
        }
        echo '<script type="application/json" class="labaslietas-v34-data">' . wp_json_encode($items) . '</script>';
        echo '</div>';
    }
}



if (!function_exists('labaslietas_render_v35_product_gallery')) {
    /**
     * V35 final custom gallery: no Woo/Flex dependency, visible via background-image + img fallback.
     */
    function labaslietas_render_v35_product_gallery($product) {
        if (!$product || !is_a($product, 'WC_Product')) { return; }
        $ids = array();
        $main_id = $product->get_image_id();
        if ($main_id) { $ids[] = $main_id; }
        foreach ((array) $product->get_gallery_image_ids() as $gid) {
            if ($gid && !in_array($gid, $ids, true)) { $ids[] = $gid; }
        }
        $items = array();
        if (empty($ids)) {
            $ph = wc_placeholder_img_src('woocommerce_single');
            $items[] = array('main' => $ph, 'full' => $ph, 'thumb' => $ph, 'alt' => $product->get_name());
        } else {
            foreach ($ids as $id) {
                $thumb = wp_get_attachment_image_url($id, 'woocommerce_gallery_thumbnail');
                $single = wp_get_attachment_image_url($id, 'woocommerce_single');
                $large = wp_get_attachment_image_url($id, 'large');
                $full = wp_get_attachment_image_url($id, 'full');
                $main = $single ? $single : ($large ? $large : ($full ? $full : $thumb));
                if (!$main) { continue; }
                $modal = $full ? $full : ($large ? $large : $main);
                if (!$thumb) { $thumb = $main; }
                $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                if (!$alt) { $alt = $product->get_name(); }
                $items[] = array('main'=>$main, 'full'=>$modal, 'thumb'=>$thumb, 'alt'=>$alt);
            }
        }
        if (empty($items)) { return; }
        $first = $items[0];
        echo '<div class="labaslietas-v35-gallery" data-active="0">';
        echo '<button type="button" class="labaslietas-v35-zoom" aria-label="Atvērt lielo attēlu"><span></span></button>';
        echo '<div class="labaslietas-v35-main" style="background-image:url(' . esc_url($first['main']) . ');">';
        echo '<img class="labaslietas-v35-img" src="' . esc_url($first['main']) . '" data-full="' . esc_url($first['full']) . '" alt="' . esc_attr($first['alt']) . '" loading="eager" decoding="async" />';
        echo '</div>';
        echo '<div class="labaslietas-v35-thumbs" role="list" aria-label="Produkta attēli">';
        foreach ($items as $i => $item) {
            echo '<button type="button" class="labaslietas-v35-thumb' . ($i === 0 ? ' is-active' : '') . '" data-index="' . esc_attr($i) . '" data-main="' . esc_url($item['main']) . '" data-full="' . esc_url($item['full']) . '" data-thumb="' . esc_url($item['thumb']) . '" aria-pressed="' . ($i === 0 ? 'true' : 'false') . '">';
            echo '<img src="' . esc_url($item['thumb']) . '" alt="' . esc_attr($item['alt']) . '" loading="lazy" decoding="async" />';
            echo '</button>';
        }
        echo '</div>';
        echo '<script type="application/json" class="labaslietas-v35-data">' . wp_json_encode($items) . '</script>';
        echo '</div>';
    }
}


if (!function_exists('labaslietas_render_v37_product_gallery')) {
    /**
     * V37 hard server-side gallery. It does not depend on WooCommerce FlexSlider JS/CSS.
     * Unique class names + inline fallback styles keep the main product image visible even when cached old CSS is loaded.
     */
    function labaslietas_render_v37_product_gallery($product) {
        if (!$product || !is_a($product, 'WC_Product')) { return; }
        $ids = array();
        $main_id = $product->get_image_id();
        if ($main_id) { $ids[] = $main_id; }
        foreach ((array) $product->get_gallery_image_ids() as $gid) {
            if ($gid && !in_array($gid, $ids, true)) { $ids[] = $gid; }
        }
        $items = array();
        if (empty($ids)) {
            $ph = wc_placeholder_img_src('woocommerce_single');
            $items[] = array('thumb' => $ph, 'main' => $ph, 'full' => $ph, 'alt' => $product->get_name());
        } else {
            foreach ($ids as $id) {
                $thumb = wp_get_attachment_image_url($id, 'woocommerce_gallery_thumbnail');
                $single = wp_get_attachment_image_url($id, 'woocommerce_single');
                $large = wp_get_attachment_image_url($id, 'large');
                $full = wp_get_attachment_image_url($id, 'full');
                $main = $single ? $single : ($large ? $large : ($full ? $full : $thumb));
                if (!$main) { continue; }
                if (!$thumb) { $thumb = $main; }
                $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                if (!$alt) { $alt = $product->get_name(); }
                $items[] = array(
                    'thumb' => $thumb,
                    'main' => $main,
                    'full' => $full ? $full : ($large ? $large : $main),
                    'alt' => $alt,
                );
            }
        }
        if (empty($items)) { return; }
        $uid = 'm37pix-' . absint($product->get_id());
        $first = $items[0];
        echo '<div id="' . esc_attr($uid) . '" class="m37pix" data-active="0" style="display:block!important;width:100%!important;max-width:590px!important;margin:0 auto!important;position:relative!important;overflow:visible!important;">';
        echo '<button type="button" class="m37pix-zoom" aria-label="Atvērt attēlu" style="position:absolute!important;top:18px!important;right:18px!important;z-index:20!important;width:48px!important;height:48px!important;border:0!important;border-radius:999px!important;background:#111827!important;color:#fff!important;display:flex!important;align-items:center!important;justify-content:center!important;box-shadow:0 16px 34px rgba(15,23,42,.24)!important;cursor:pointer!important;">⌕</button>';
        echo '<div class="m37pix-main" style="display:flex!important;align-items:center!important;justify-content:center!important;width:100%!important;height:520px!important;min-height:520px!important;border:1px solid #e4ebf5!important;border-radius:24px!important;background:#fff!important;padding:24px!important;box-sizing:border-box!important;overflow:hidden!important;">';
        echo '<img class="m37pix-img" src="' . esc_url($first['main']) . '" data-full="' . esc_url($first['full']) . '" alt="' . esc_attr($first['alt']) . '" loading="eager" decoding="async" style="display:block!important;visibility:visible!important;opacity:1!important;width:auto!important;height:auto!important;max-width:100%!important;max-height:100%!important;object-fit:contain!important;object-position:center!important;margin:auto!important;position:static!important;transform:none!important;" />';
        echo '</div>';
        echo '<div class="m37pix-thumbs" role="list" aria-label="Produkta attēli" style="display:flex!important;flex-direction:row!important;flex-wrap:nowrap!important;gap:10px!important;width:100%!important;margin:14px 0 0!important;padding:2px 2px 10px!important;overflow-x:auto!important;overflow-y:hidden!important;align-items:center!important;justify-content:flex-start!important;box-sizing:border-box!important;">';
        foreach ($items as $i => $item) {
            echo '<button type="button" class="m37pix-thumb' . ($i === 0 ? ' is-active' : '') . '" data-index="' . esc_attr($i) . '" data-main="' . esc_url($item['main']) . '" data-full="' . esc_url($item['full']) . '" aria-pressed="' . ($i === 0 ? 'true' : 'false') . '" style="flex:0 0 82px!important;width:82px!important;height:74px!important;min-width:82px!important;min-height:74px!important;margin:0!important;padding:6px!important;border:1px solid ' . ($i === 0 ? '#2f8b49' : '#dfe7f1') . '!important;border-radius:14px!important;background:#fff!important;display:flex!important;align-items:center!important;justify-content:center!important;overflow:hidden!important;box-sizing:border-box!important;cursor:pointer!important;">';
            echo '<img src="' . esc_url($item['thumb']) . '" alt="' . esc_attr($item['alt']) . '" loading="lazy" decoding="async" style="display:block!important;width:100%!important;height:100%!important;max-width:100%!important;max-height:100%!important;object-fit:contain!important;object-position:center!important;margin:0!important;padding:0!important;opacity:1!important;visibility:visible!important;position:static!important;" />';
            echo '</button>';
        }
        echo '</div>';
        echo '<script type="application/json" class="m37pix-data">' . wp_json_encode($items) . '</script>';
        echo '<div class="m37pix-modal" hidden style="position:fixed!important;inset:0!important;z-index:999999!important;background:rgba(4,8,15,.92)!important;padding:22px!important;box-sizing:border-box!important;grid-template-columns:76px minmax(0,1fr) 76px!important;grid-template-rows:62px minmax(0,1fr) 112px!important;gap:14px!important;align-items:center!important;justify-items:center!important;">';
        echo '<button type="button" class="m37pix-close" aria-label="Aizvērt" style="grid-column:3!important;grid-row:1!important;justify-self:end!important;width:48px!important;height:48px!important;border-radius:999px!important;border:1px solid rgba(255,255,255,.18)!important;background:#111827!important;color:#fff!important;font-size:30px!important;line-height:1!important;display:flex!important;align-items:center!important;justify-content:center!important;cursor:pointer!important;">×</button>';
        echo '<button type="button" class="m37pix-prev" aria-label="Iepriekšējais" style="grid-column:1!important;grid-row:2!important;width:58px!important;height:58px!important;border-radius:999px!important;border:0!important;background:#fff!important;color:#111827!important;font-size:42px!important;display:flex!important;align-items:center!important;justify-content:center!important;cursor:pointer!important;">‹</button>';
        echo '<img class="m37pix-modal-img" src="' . esc_url($first['full']) . '" alt="' . esc_attr($first['alt']) . '" style="grid-column:1 / 4!important;grid-row:2!important;display:block!important;width:auto!important;height:auto!important;max-width:100%!important;max-height:calc(100vh - 230px)!important;object-fit:contain!important;opacity:1!important;visibility:visible!important;" />';
        echo '<button type="button" class="m37pix-next" aria-label="Nākamais" style="grid-column:3!important;grid-row:2!important;width:58px!important;height:58px!important;border-radius:999px!important;border:0!important;background:#fff!important;color:#111827!important;font-size:42px!important;display:flex!important;align-items:center!important;justify-content:center!important;cursor:pointer!important;">›</button>';
        echo '<div class="m37pix-modal-thumbs" style="grid-column:1 / 4!important;grid-row:3!important;width:100%!important;max-width:920px!important;display:flex!important;align-items:center!important;justify-content:center!important;gap:10px!important;overflow-x:auto!important;padding:8px 4px!important;box-sizing:border-box!important;"></div>';
        echo '</div>';
        echo '<script>(function(){var g=document.getElementById(' . wp_json_encode($uid) . ');if(!g)return;var dataEl=g.querySelector(".m37pix-data"),items=[];try{items=JSON.parse(dataEl.textContent||"[]")}catch(e){};var img=g.querySelector(".m37pix-img"),modal=g.querySelector(".m37pix-modal"),modalImg=g.querySelector(".m37pix-modal-img"),modalThumbs=g.querySelector(".m37pix-modal-thumbs");function norm(i){i=parseInt(i||0,10);if(isNaN(i))i=0;if(!items.length)return 0;return (i%items.length+items.length)%items.length}function active(i,open){i=norm(i);var it=items[i]||{};g.setAttribute("data-active",i);if(img){img.removeAttribute("srcset");img.removeAttribute("sizes");img.src=it.main||it.full||it.thumb||img.src;img.setAttribute("data-full",it.full||it.main||img.src);img.alt=it.alt||"";img.style.display="block";img.style.visibility="visible";img.style.opacity="1";}g.querySelectorAll(".m37pix-thumb").forEach(function(b,n){var on=n===i;b.classList.toggle("is-active",on);b.setAttribute("aria-pressed",on?"true":"false");b.style.borderColor=on?"#2f8b49":"#dfe7f1"});if(open)openModal(i)}function openModal(i){i=norm(i);active(i,false);var it=items[i]||{};modalImg.src=it.full||it.main||it.thumb||"";modalImg.alt=it.alt||"";modalThumbs.innerHTML=items.map(function(x,n){return "<button type=\\"button\\" class=\\"m37pix-mthumb "+(n===i?"is-active":"")+"\\" data-index=\\""+n+"\\" style=\\"flex:0 0 84px;width:84px;height:74px;border-radius:14px;border:1px solid "+(n===i?"#2f8b49":"rgba(255,255,255,.25)")+";background:#fff;padding:6px;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:"+(n===i?"1":".72")+";box-sizing:border-box;\\"><img src=\\""+(x.thumb||x.main||x.full)+"\\" alt=\\"\\" style=\\"width:100%;height:100%;object-fit:contain;display:block;\\"></button>"}).join("");modal.hidden=false;modal.style.display="grid";document.body.style.overflow="hidden"}function close(){modal.hidden=true;modal.style.display="none";document.body.style.overflow=""}g.addEventListener("click",function(e){var t=e.target.closest(".m37pix-thumb");if(t){e.preventDefault();active(t.getAttribute("data-index"),false);return}if(e.target.closest(".m37pix-zoom")||e.target.closest(".m37pix-main")){e.preventDefault();openModal(g.getAttribute("data-active")||0);return}var mt=e.target.closest(".m37pix-mthumb");if(mt){e.preventDefault();openModal(mt.getAttribute("data-index"));return}if(e.target.closest(".m37pix-prev")){e.preventDefault();openModal(norm((g.getAttribute("data-active")||0)-1));return}if(e.target.closest(".m37pix-next")){e.preventDefault();openModal(norm(parseInt(g.getAttribute("data-active")||0,10)+1));return}if(e.target.closest(".m37pix-close")||e.target===modal){e.preventDefault();close();return}},true);active(0,false);})();</script>';
        echo '</div>';
    }
}

get_header();

while (have_posts()) : the_post();
    global $product;
    if (!$product || !is_a($product, 'WC_Product')) {
        $product = wc_get_product(get_the_ID());
    }
    if (!$product) {
        continue;
    }
    $product_id = $product->get_id();
    ?>
    <main class="labaslietas-single-wrap labaslietas-single-v16 py-4 py-lg-5">
        <div class="labaslietas-container container labaslietas-single-container">
            <?php woocommerce_output_all_notices(); ?>
            <div class="labaslietas-single-breadcrumbs mb-3"><?php woocommerce_breadcrumb(); ?></div>

            <article id="product-<?php the_ID(); ?>" <?php wc_product_class('labaslietas-single-card labaslietas-iws-card mx-auto', $product); ?>>
                <div class="labaslietas-single-inner row g-4 g-xl-5 align-items-start justify-content-center">
                    <div class="col-12 col-lg-6 labaslietas-single-gallery-col">
                        <div class="labaslietas-single-gallery labaslietas-iws-gallery card border-0">
                            <?php echo labaslietas_sale_badge($product); ?>
                            <?php echo labaslietas_render_v38_product_gallery($product); ?>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 labaslietas-single-summary-col">
                        <div class="labaslietas-single-summary card border-0">
                            <div class="labaslietas-single-labels">
                                <?php if ($product->is_on_sale()) : ?><span>Akcija</span><?php endif; ?>
                                <?php if ($product->is_in_stock()) : ?><span class="is-stock">Ir noliktavā</span><?php else : ?><span class="is-out">Nav noliktavā</span><?php endif; ?>
                            </div>
                            <?php woocommerce_template_single_title(); ?>
                            <div class="labaslietas-single-price"><?php woocommerce_template_single_price(); ?></div>
                            <div class="labaslietas-single-short"><?php woocommerce_template_single_excerpt(); ?></div>

                            <div class="labaslietas-single-buybox card border-0">
                                <?php woocommerce_template_single_add_to_cart(); ?>
                                <div class="labaslietas-single-actions-row">
                                    <?php echo labaslietas_yith_wishlist_button($product_id); ?>
                                    <?php echo labaslietas_yith_compare_button($product_id); ?>
                                </div>
                            </div>

                            <div class="labaslietas-single-meta">
                                <?php woocommerce_template_single_meta(); ?>
                            </div>

                            <div class="labaslietas-single-trust row g-3">
                                <div class="col-12 col-md-4"><div><strong>Ātra piegāde</strong><span>1-3 darba dienas Latvijā</span></div></div>
                                <div class="col-12 col-md-4"><div><strong>Drošs pirkums</strong><span>14 dienu atgriešana</span></div></div>
                                <div class="col-12 col-md-4"><div><strong>Oficiālie zīmoli</strong><span>Garantija un serviss</span></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <section class="labaslietas-single-tabs-card card border-0 shadow-sm mx-auto mt-4">
                <?php woocommerce_output_product_data_tabs(); ?>
            </section>

            <?php labaslietas_render_single_product_section($product_id, 'recommended'); ?>
            <?php labaslietas_render_single_product_section($product_id, 'related'); ?>
        </div>
    </main>
<?php endwhile;
get_footer();
