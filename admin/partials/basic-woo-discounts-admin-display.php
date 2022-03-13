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

$activeRules = \bcd\Database::bcd_fetch_active_rules();
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

</table>
