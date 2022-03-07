<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              Charis Valtzis
 * @since             1.0.0
 * @package           Basic_Woo_Discounts
 *
 * @wordpress-plugin
 * Plugin Name:       Basic Woocommerce Discounts
 * Plugin URI:        basic-woo-discounts
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Charis Valtzis
 * Author URI:        Charis Valtzis
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       basic-woo-discounts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BASIC_WOO_DISCOUNTS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-basic-woo-discounts-activator.php
 */
function activate_basic_woo_discounts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-basic-woo-discounts-activator.php';
	Basic_Woo_Discounts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-basic-woo-discounts-deactivator.php
 */
function deactivate_basic_woo_discounts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-basic-woo-discounts-deactivator.php';
	Basic_Woo_Discounts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_basic_woo_discounts' );
register_deactivation_hook( __FILE__, 'deactivate_basic_woo_discounts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-basic-woo-discounts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_basic_woo_discounts() {

	$plugin = new Basic_Woo_Discounts();
	$plugin->run();

}
run_basic_woo_discounts();
