<?php

namespace bcd;
/**
 * Helping Class to collect data from the database
 */
class Database
{

    /**
     * @return int[]|\WP_Post[]
     *
     * @since    1.0.0
     */
    static function bcd_fetch_rules()
    {
        $args = array(
            'post_type' => 'shop_coupon',
            'posts_per_page' => -1,
            'post_status' => 'any',
            'meta_query' => array(
                array(
                    'key' => '_bcd_discount_rule',
                    'compare' => 'EXISTS'
                )
            )
        );

        return get_posts($args);
    }

    /**
     * @param $id
     * @return string|\WP_Error
     *
     * @since    1.0.0
     */
    static function bcd_toggle_rule_status($id)
    {
        $current_post_status = get_post_status($id);

        $change_to = ($current_post_status == 'publish') ? 'draft' : 'publish';

        $args = array(
            'ID' => $id,
            'post_status' => $change_to
        );

        $updated = wp_update_post($args);

        if ($updated) {
            return $change_to;
        }

        return new \WP_Error('400', __("Couldn't update post status", "bcd"));
    }
}
