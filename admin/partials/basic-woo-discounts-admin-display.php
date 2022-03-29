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

$activeRules = \bcd\Database::bcd_fetch_rules();

do_action('bcd_before_rules_table');
?>

    <!-- This file should primarily consist of HTML with a little bit of PHP. -->

    <table class="bcd_rules-table">
        <tr class="bcd_rules-table__headings">
            <th class="heading__item">Id</th>
            <th class="heading__item">Title</th>
            <th class="heading__item">Start Date</th>
            <th class="heading__item">Expire Day</th>
            <th class="heading__item">Status</th>
            <th class="heading__item">Actions</th>
        </tr>
        <?php foreach ($activeRules as $activeRule): ?>
            <tr class="table__row" data-id="<?php echo $activeRule->ID ?>">
                <td class="table__item"><?php echo $activeRule->ID ?></td>
                <td class="table__item"><?php echo $activeRule->post_title ?></td>
                <td class="table__item"><?php echo 'from' ?></td>
                <td class="table__item"><?php echo 'until' ?></td>
                <td class="table__item">
                    <label class="switch">
                        <input type="checkbox" <?php echo ($activeRule->post_status == 'publish') ? 'checked' : '' ?>>
                        <span class="slider round"></span>
                    </label>
                </td>
                <td><?php echo 'actions' ?></td>
            </tr>
        <?php endforeach; ?>

    </table>
<?php
do_action('bcd_after_rules_table');
