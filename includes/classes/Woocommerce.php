<?php

namespace bcd;

use bcd_\Database;
use ParagonIE\Sodium\Core\Curve25519\Ge\P1p1;

class Woocommerce
{
//    private $cart;

//    public function __construct()
//    {
//        $this->cart = $cart;
//    }


    /**
     * @param $data
     * @return false|int|\WP_Error
     *
     * @since    1.0.0
     */
    function bcd_add_new_rule($data)
    {

        $args = array(
            'post_title' => $data['title'],
            'included_products' => $_POST['includedProdIds'],
            'discount_type' => $_POST['discountType'],
            'discount_amount' => $_POST['discountValue'],
        );

        return $this->bcd_create_coupon($args);

    }

    /**
     * @param $cart
     *
     * @since    1.0.0
     */
    function bcd_create_cart_discounts($cart)
    {


        $cat_ids = [];
        $prod_ids = [];
        $cat_ids = $this->bcd_fetch_active_cart_category_ids($cat_ids);
        $prod_ids = $this->bcd_fetch_active_cart_product_ids($prod_ids);

        $activeRules = \bcd\Database::bcd_fetch_rules();

        foreach ($activeRules as $activeRule) {
            $this->bcd_remove_applied_coupons();

            $valid_discount = $this->bcd_is_valid_discount($activeRule->ID);

            if (!apply_filters('bcd_valid_rule_examination', $valid_discount, $activeRule)) {
                continue;
            }

            $included_product_ids = get_post_meta($activeRule->ID, 'include_product_ids', true) ?? '';
            $excluded_product_ids = get_post_meta($activeRule->ID, 'exclude_product_ids', true) ?? '';
            $coupon_code = $activeRule->post_title;


            /*
            *  todo that need to be in the cart in order for the discount to be applied.
            */

            if (!empty($excluded_product_ids)) {
                if (count(array_intersect($excluded_product_ids, $prod_ids))) {
                    continue;//here we have product that is excluded in the cart, so bye bye!;
                }
            }

            if (!empty($included_product_ids)) {
                if (count(array_intersect($included_product_ids, $prod_ids))) {
                    $discount_type = (get_post_meta($activeRule->ID, 'discount_type', true) == 'percent' ? 'percent' : 'fixed');
                    $discount = get_post_meta($activeRule->ID, 'coupon_amount', true);


                    WC()->cart->apply_coupon($coupon_code);
                    break;
                }
            }

            $this->bcd_apply_discount();
            /* todo 1. Check if ids in cart match with ids in cart product */
            /* todo 2. Check if category ids in cart match with category ids in cart product */
            /* todo 3. If find a match, break and after foreach apply discount */
        }

    }

    /**
     * @param $data
     * @return false|int|\WP_Error
     *
     * @since    1.0.0
     */
    private function bcd_create_coupon($data)
    {
        $coupon_code = 'bcd_' . sanitize_title($data['post_title']);
        $type = $data['discount_type'];
        $amount = $data['discount_amount'];
        $includedProductIds = $data['included_products'];
//        $includedProductIds = (!empty(get_post_meta($ruleId, '_bcd_included_products', true))) ? get_post_meta($ruleId, '_bcd_included_products', true) : '';

//        $cat_ids = array();//cart categories id
//        foreach (wc()->cart->get_cart() as $cart_item_key => $cart_item) {
//            $cat_ids = array_merge(
//                $cat_ids, $cart_item['data']->get_category_ids()
//            );
//        }

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
            update_post_meta($new_coupon_id, 'include_product_ids', $includedProductIds);
            update_post_meta($new_coupon_id, 'usage_limit', '');
            update_post_meta($new_coupon_id, 'expiry_date', '');
            update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
            update_post_meta($new_coupon_id, 'free_shipping', 'no');
            update_post_meta($new_coupon_id, '_bcd_discount_rule', 'yes');

            return $new_coupon_id;
        }

        return false;
    }

    /**
     * @param $cat_ids
     * @return array|mixed
     *
     * @since    1.0.0
     */
    private function bcd_fetch_active_cart_category_ids($cat_ids)
    {
        foreach (wc()->cart->get_cart() as $cart_item_key => $cart_item) {
            $cat_ids = array_merge(
                $cat_ids, $cart_item['data']->get_category_ids()
            );
        }

        return $cat_ids;
    }

    /**
     * @param $prod_ids
     * @return mixed
     *
     * @since    1.0.0
     */
    private function bcd_fetch_active_cart_product_ids($prod_ids)
    {
        foreach (wc()->cart->get_cart() as $cart_item_key => $cart_item) {
            $prod_ids[] = $cart_item['product_id'];
        }

        return $prod_ids;
    }

    private function bcd_apply_discount()
    {

    }

    /**
     *
     * @since    1.0.0
     */
    private function bcd_remove_applied_coupons()
    {
        foreach (WC()->cart->get_coupons() as $code => $coupon) {
            $valid = true;
            if (str_starts_with($coupon->code, "bcd_")) {
                $valid = false;
            }
            if (!$valid) {
                WC()->cart->remove_coupon($code);
            }
        }
    }

    /**
     * @param int $id
     * @return bool
     *
     * @since    1.0.0
     */
    private function bcd_is_valid_discount(int $id)
    {
        $subtotal = WC()->cart->subtotal;
        $valid_rule = false;

        $cart_min_total_amount = get_post_meta($id, 'bcd_discount_min_amount', true) ?? '';
        $cart_max_total_amount = get_post_meta($id, 'bcd_discount_max_amount', true) ?? '';


        /* Check if rule must be applied based on cart amount */
        if ((!empty($cart_min_total_amount) && $subtotal >= $cart_min_total_amount) ||
            (!empty($cart_max_total_amount) && $subtotal <= $cart_max_total_amount) ||
            (empty($cart_min_total_amount) && empty($cart_max_total_amount))) {
            $valid_rule = true;
        }

        return $valid_rule;
    }
}
