<?php

namespace LiveChat\Templates;

use WC_Cart;
use stdClass;


function text_get_cart_content() {
    $woocommerce = WC();

    if ( ! $woocommerce ) {
        return null;
    }

    // Get the cart content
    $cart = $woocommerce->cart;

    // create an object 
    $response = new stdClass();

    $response->currency = get_woocommerce_currency();
    $response->total = floatval(preg_replace('/[^\d\.]/', '', $cart->get_cart_contents_total()));
    $response->subtotal = floatval(preg_replace('/[^\d\.]/', '', $cart->get_subtotal()));

    $response->items = array();

    $items = $cart->get_cart_contents();

    foreach ( $items as $item ) {
        $product = wc_get_product( $item['data'] );
        $url     = $product->get_permalink();
        $qty     = $item['quantity'];
        $name    = $product->get_name();


        $subtotal = $item['line_subtotal'];

        // Get the total (after discounts)
        $value = $item['line_total'];
    
        // Calculate the discount for this item
        $discount = $subtotal - $value;


        $variantTitle = '';
        if ( $product->is_type('variation') ) {
            // Get the variation attributes (e.g., size, color)
            $attributes = $cart_item['variation'];

            // Loop through the attributes and output the name
            foreach ($attributes as $attribute_name => $attribute_value) {
                // Get the human-readable attribute name (e.g., "Size", "Color")
                $taxonomy = str_replace('attribute_', '', $attribute_name); // Remove 'attribute_' prefix
                $attribute_label = wc_attribute_label($taxonomy);

                // Output the variation attribute name and value (e.g., "Size: Large")
                $variantTitle .= $attribute_value . ' / ';
            }

            // remove the last ' / '
            $variantTitle = rtrim($variantTitle, ' / ');
        }


        $response->items[] = array(
            'thumbnailUrl' =>  get_the_post_thumbnail_url( $product->get_id(), 'shop_thumbnail' ),
            'title' => $name,
            'variantTitle' => $variantTitle,
            'discount' => array(
                'amount' => $discount,
            ),
            'quantity' => $qty,
            'value' => $value,
            'productPreviewUrl' => $url,
        );
    }

    // Send back the cart items in a JSON response
    wp_send_json_success($response);
}
