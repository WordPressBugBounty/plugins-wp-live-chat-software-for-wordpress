<?php

namespace LiveChat\Templates;

function text_register_woo_scripts(): void {
    wp_enqueue_script(
        'text-connect',
        TEXT_PLUGIN_URL . '/includes/js/textConnect.js',
        array(),
        null,
        false // load in footer
    );

    wp_localize_script('text-connect', 'textConnect', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));

    // TODO: get storeUUID from token
    $storeUUID = '39fc6c9c-e970-4ab2-bc1c-b899780b86ce';
    $script_url = 'https://' . TEXT_DOMAIN . '/api/v1/customer-insight/script/' . $storeUUID . '/cart-tracking.js';

    wp_enqueue_script(
        'cart-tracking',
        $script_url, 
        array(), 
        null,
        true // load in footer
    );
}

