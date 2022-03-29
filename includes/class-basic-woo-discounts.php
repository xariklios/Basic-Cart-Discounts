<?php

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       Charis Valtzis
 * @since      1.0.0
 *
 * @package    Basic_Woo_Discounts
 * @subpackage Basic_Woo_Discounts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Basic_Woo_Discounts
 * @subpackage Basic_Woo_Discounts/includes
 * @author     Charis Valtzis <charisvaltzis@gmail.com>
 */
class Basic_Woo_Discounts
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Basic_Woo_Discounts_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('BASIC_WOO_DISCOUNTS_VERSION')) {
            $this->version = BASIC_WOO_DISCOUNTS_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'basic-woo-discounts';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Basic_Woo_Discounts_Loader. Orchestrates the hooks of the plugin.
     * - Basic_Woo_Discounts_i18n. Defines internationalization functionality.
     * - Basic_Woo_Discounts_Admin. Defines all hooks for the admin area.
     * - Basic_Woo_Discounts_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-basic-woo-discounts-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-basic-woo-discounts-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-basic-woo-discounts-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-basic-woo-discounts-public.php';

        $this->loader = new Basic_Woo_Discounts_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Basic_Woo_Discounts_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Basic_Woo_Discounts_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Basic_Woo_Discounts_Admin($this->get_plugin_name(), $this->get_version());
        $utils = new \bcd\Utils();
        $woohandler = new \bcd\Woocommerce();

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        /* custom hooks */
        $this->loader->add_action('admin_menu', $plugin_admin, 'register_bcd_submenu_page');

        $this->loader->add_action('wp_ajax_bcd_update_rule_status', $plugin_admin, 'bcd_update_rule_status');
        $this->loader->add_action('wp_ajax_nopriv_bcd_update_rule_status', $plugin_admin, 'bcd_update_rule_status');

        $this->loader->add_action('wp_ajax_bcd_create_new_rule', $plugin_admin, 'bcd_create_new_rule');
        $this->loader->add_action('wp_ajax_nopriv_bcd_create_new_rule', $plugin_admin, 'bcd_create_new_rule');

        /* Woocommerce */
        $this->loader->add_action('woocommerce_before_cart', $plugin_admin, 'bcd_add_cart_discount');
        $this->loader->add_action('woocommerce_before_checkout_form', $plugin_admin, 'bcd_add_cart_discount');

        $this->loader->add_action('pre_get_posts', $plugin_admin, 'bcd_manage_admin_coupon_list', 9999);

        $this->loader->add_filter('woocommerce_cart_totals_coupon_html', $plugin_admin,'bcd_remove_coupon_html', 10, 3);
        $this->loader->add_filter('woocommerce_cart_totals_coupon_label', $plugin_admin,'bcd_hide_coupon_code', 99, 2);

        /* Import Woocommerce scripts */
        $this->loader->add_action('admin_footer', $plugin_admin, 'bcd_import_woo_scripts', 99);
    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Basic_Woo_Discounts_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Basic_Woo_Discounts_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }

}
