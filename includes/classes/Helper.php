<?php

namespace bcd;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class Helper{

    public static function isWooActive(){
        if ( !is_plugin_active( 'woocommerce/woocommerce.php') ) {
            wp_die('Please Install WooCommerce to Continue !');
        }
    }

}
