<?php

namespace bcd;
/**
 * Helping Class to collect data from the database
 */
class Database
{

    static function bcd_fetch_active_rules()
    {
        $args = array(
            'post_type' => 'shop_coupon',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_bcd_discount_rule',
                    'compare' => 'EXISTS'
                )
            )
        );

         return get_posts($args);
    }
}
