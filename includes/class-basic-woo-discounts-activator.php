<?php

/**
 * Fired during plugin activation
 *
 * @link       Charis Valtzis
 * @since      1.0.0
 *
 * @package    Basic_Woo_Discounts
 * @subpackage Basic_Woo_Discounts/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Basic_Woo_Discounts
 * @subpackage Basic_Woo_Discounts/includes
 * @author     Charis Valtzis <charisvaltzis@gmail.com>
 */
class Basic_Woo_Discounts_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        bcd\Helper::isWooActive();
	}

}
