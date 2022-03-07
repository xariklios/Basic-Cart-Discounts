<?php

namespace bcd;

class Woocommerce{
    public function bcd_add_new_rule($data){

        var_dump($data);
        exit;

        $args = array(
            'post_title'    => $data['title'] ,
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type' => 'discount_rules'
        );

// Insert the post into the database
        $postId = wp_insert_post( $args );

        if ($postId){

        }

    }
}
