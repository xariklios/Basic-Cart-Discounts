<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       Charis Valtzis
 * @since      1.0.0
 *
 * @package    Basic_Woo_Discounts
 * @subpackage Basic_Woo_Discounts/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Basic_Woo_Discounts
 * @subpackage Basic_Woo_Discounts/includes
 * @author     Charis Valtzis <charisvaltzis@gmail.com>
 */
class Basic_Woo_Discounts_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'basic-woo-discounts',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
