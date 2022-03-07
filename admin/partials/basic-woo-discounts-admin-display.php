<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       Charis Valtzis
 * @since      1.0.0
 *
 * @package    Basic_Woo_Discounts
 * @subpackage Basic_Woo_Discounts/admin/partials
 */

/* Get All discount Rules */
$args = array(
    'post_type' => 'discount_rules',
    'posts_per_page' => -1
);

$query = new WP_Query($args);
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Start Date</th>
        <th>Expire Day</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            echo '<td>' . get_the_ID() . '</td>';
            echo '<td>' . get_the_title() . '</td>';
        endwhile;
        echo '</select>';
        wp_reset_postdata();
    endif; ?>
</table>
