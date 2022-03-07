<?php

namespace bcd;

class Utils
{

    function bcd_generate_discount_rules_post_type()
    {

        $labels = array(
            'name' => _x('Discount Rules', 'Post Type General Name', 'bcd'),
            'singular_name' => _x('Discount Rule', 'Post Type Singular Name', 'bcd'),
            'menu_name' => __('Post Types', 'bcd'),
            'name_admin_bar' => __('Post Type', 'bcd'),
            'archives' => __('Item Archives', 'bcd'),
            'attributes' => __('Item Attributes', 'bcd'),
            'parent_item_colon' => __('Parent Item:', 'bcd'),
            'all_items' => __('All Items', 'bcd'),
            'add_new_item' => __('Add New Item', 'bcd'),
            'add_new' => __('Add New', 'bcd'),
            'new_item' => __('New Item', 'bcd'),
            'edit_item' => __('Edit Item', 'bcd'),
            'update_item' => __('Update Item', 'bcd'),
            'view_item' => __('View Item', 'bcd'),
            'view_items' => __('View Items', 'bcd'),
            'search_items' => __('Search Item', 'bcd'),
            'not_found' => __('Not found', 'bcd'),
            'not_found_in_trash' => __('Not found in Trash', 'bcd'),
            'featured_image' => __('Featured Image', 'bcd'),
            'set_featured_image' => __('Set featured image', 'bcd'),
            'remove_featured_image' => __('Remove featured image', 'bcd'),
            'use_featured_image' => __('Use as featured image', 'bcd'),
            'insert_into_item' => __('Insert into item', 'bcd'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'bcd'),
            'items_list' => __('Items list', 'bcd'),
            'items_list_navigation' => __('Items list navigation', 'bcd'),
            'filter_items_list' => __('Filter items list', 'bcd'),
        );
        $args = array(
            'label' => __('Discount Rule', 'bcd'),
            'description' => __('Discount Rules Post Type for Basic Cart Discounts Plugin', 'bcd'),
            'labels' => $labels,
            'supports' => false,
            'hierarchical' => false,
            'public' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'menu_position' => 5,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'page',
        );
        register_post_type('discount_rules', $args);
    }
}
