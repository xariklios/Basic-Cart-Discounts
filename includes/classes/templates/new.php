<?php

/**
 * New Rule Display Area
 *
 * @link       Charis Valtzis
 * @since      1.0.0
 *
 * @package    Basic_Woo_Discounts
 * @subpackage Basic_Woo_Discounts/includes/templates
 */


if (!defined('ABSPATH')) exit; // Exit if accessed directly

?>

<h3>Add New Rule</h3>

<!-- Post Title -->
<form method="post" id="bcd_new_form" action="" style="margin-top: 10px;">
    <?php do_action('add_new_rule_form_start'); ?>
    <div id="title_container">
        <div id="title_wrapper">
            <label for="title" style="display: none;"><?php _e('Name', 'bcd'); ?></label>
            <input id="title" type="text" autocomplete="off" name="rule_name" value=""
                   placeholder="<?php _e('Enter title here', 'bcd'); ?>">
        </div>
    </div>
    <!-- Include Products -->
    <div id="include_products">
        <p class="form-field">
            <label for="_bcd_products_included_ids"><?php _e('Include Products', 'bcd') ?></label>
            <select class="wc-product-search" multiple="multiple" style="width: 20%;" id="_bcd_products_included_ids"
                    name="_bcd_products_included_ids[]"
                    data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'woocommerce'); ?>"
                    data-action="woocommerce_json_search_products_and_variations">
                <?php
                $product_ids = get_post_meta($post->ID, '_bcd_products_included_ids', true);


                foreach ($product_ids as $product_id) {
                    $product = wc_get_product($product_id);
                    if (is_object($product)) {
                        echo '<option value="' . esc_attr($product_id) . '"' . selected(true, true, false) . '>' . esc_html(wp_strip_all_tags($product->get_formatted_name())) . '</option>';
                    }
                }
                ?>
            </select>
        </p>
    </div>
    <!-- Exclude Products -->
    <div id="exclude_products">
        <p class="form-field">
            <label for="_bcd_products_excluded_ids"><?php _e('Exclude Products', 'bcd') ?></label>
            <select class="wc-product-search" multiple="multiple" style="width: 20%;" id="_bcd_products_excluded_ids"
                    name="_bcd_products_excluded_ids[]"
                    data-placeholder="<?php esc_attr_e('Search for a product&hellip;', 'woocommerce'); ?>"
                    data-action="woocommerce_json_search_products_and_variations">
                <?php
                $product_ids = get_post_meta($post->ID, '_bcd_products_excluded_ids', true);

                foreach ($product_ids as $product_id) {
                    $product = wc_get_product($product_id);
                    if (is_object($product)) {
                        echo '<option value="' . esc_attr($product_id) . '"' . selected(true, true, false) . '>' . esc_html(wp_strip_all_tags($product->get_formatted_name())) . '</option>';
                    }
                }
                ?>
            </select>
        </p>
    </div>
    <!-- Discount Type -->
    <div id="discount_type_and_value">
        <div class="">
            <select id="bcd_discount_type" name="bcd_discount_type" class="">
                <option value="percent" selected>Percentage discount</option>
                <option value="fixed_cart">Fixed discount</option>
            </select>
            <span class="">Discount Type</span>
        </div>
        <!-- Discount Value -->
        <div class="">
            <input id="bcd_discount_value" name="bcd_discount_value" type="number" class="" value="" placeholder="0.00"
                   min="0" step="any"
                   style="width: 100%;">
            <span class="wdr_desc_text">Value</span>
        </div>
    </div>

    <?php submit_button('Add Rule') ?>

</form>
