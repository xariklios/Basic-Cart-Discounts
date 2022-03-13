<?php

namespace bcd;

use bcd_\Database;

class Woocommerce
{
//    private $cart;

//    public function __construct()
//    {
//        $this->cart = $cart;
//    }


    function bcd_add_new_rule($data)
    {

        $args = array(
            'post_title' => $data['title'],
            'included_products' => $_POST['includedProdIds'],
            'discount_type' => $_POST['discountType'],
            'discount_amount' => $_POST['discountValue'],
        );
//        $args = array(
//            'post_title' => $data['title'],
//            'post_status' => 'publish',
//            'post_author' => 1,
//            'post_type' => 'discount_rules'
//        );
//
//// Insert the post into the database
//        $postId = wp_insert_post($args);
//
//        if ($postId) {
//            update_post_meta($postId, '_bcd_included_products', $_POST['includedProdIds']);
//            update_post_meta($postId, '_bcd_discount_value', $_POST['discountValue']);
//            update_post_meta($postId, '_bcd_discount_type', $_POST['discountType']);
//        }
        return $this->bcd_create_coupon($args);

    }

    function bcd_create_cart_discounts($cart)
    {
        $subtotal = WC()->cart->subtotal;

        $cat_ids = array();
        foreach (wc()->cart->get_cart() as $cart_item_key => $cart_item) {
            $cat_ids = array_merge(
                $cat_ids, $cart_item['data']->get_category_ids()
            );
        }

        $activeRules = \bcd\Database::bcd_fetch_active_rules();

        foreach ($activeRules as $activeRule) {
            /* todo 1. Check if ids in cart match with ids in cart product */
            /* todo 2. Check if category ids in cart match with category ids in cart product */
            /* todo 3. If find a match, break and after foreach apply discount */
        }

    }

    private function bcd_create_coupon($data)
    {
        $coupon_code = 'bcd_' . sanitize_title($data['post_title']);
        $type = $data['discount_type'];
        $amount = $data['discount_amount'];
//        $includedProductIds = (!empty(get_post_meta($ruleId, '_bcd_included_products', true))) ? get_post_meta($ruleId, '_bcd_included_products', true) : '';

        $cat_ids = array();//cart categories id
        foreach (wc()->cart->get_cart() as $cart_item_key => $cart_item) {
            $cat_ids = array_merge(
                $cat_ids, $cart_item['data']->get_category_ids()
            );
        }

        $coupon = array(
            'post_title' => $coupon_code,
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon'
        );

        $new_coupon_id = wp_insert_post($coupon);

        if ($new_coupon_id) {
            //Coupon meta data
            update_post_meta($new_coupon_id, 'discount_type', $type);
            update_post_meta($new_coupon_id, 'coupon_amount', $amount);
            update_post_meta($new_coupon_id, 'individual_use', 'no');
            update_post_meta($new_coupon_id, 'product_ids', '');
            update_post_meta($new_coupon_id, 'exclude_product_ids', '');
            update_post_meta($new_coupon_id, 'usage_limit', '');
            update_post_meta($new_coupon_id, 'expiry_date', '');
            update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
            update_post_meta($new_coupon_id, 'free_shipping', 'no');
            update_post_meta($new_coupon_id, '_bcd_discount_rule', 'yes');

            return $new_coupon_id;
        }

        return false;
    }
}
