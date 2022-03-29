<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       Charis Valtzis
 * @since      1.0.0
 *
 * @package    Basic_Woo_Discounts
 * @subpackage Basic_Woo_Discounts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Basic_Woo_Discounts
 * @subpackage Basic_Woo_Discounts/admin
 * @author     Charis Valtzis <charisvaltzis@gmail.com>
 */
class Basic_Woo_Discounts_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Basic_Woo_Discounts_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Basic_Woo_Discounts_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/basic-woo-discounts-admin.css', array(), time(), 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Basic_Woo_Discounts_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Basic_Woo_Discounts_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/basic-woo-discounts-admin.js', array('jquery'), time(), false);

        $localizedData = array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
        );
        wp_localize_script($this->plugin_name, 'bcdScript', $localizedData);

        wp_enqueue_script($this->plugin_name);

    }

    public function register_bcd_submenu_page()
    {
        if (!is_admin()) return;
        global $submenu;
        if (isset($submenu['woocommerce'])) {
            add_submenu_page('woocommerce',
                'Basic Cart Discounts',
                'Basic Cart Discounts',
                'manage_options',
                'bcd-basic-discount-rules',
                array($this, 'bcd_admin_page'));
        }

        add_submenu_page(NULL,
            'Create New BCD Rule',
            'Create New BCD Rule',
            'manage_options',
            'add-rule',
            array($this, 'bcd_admin_page_create'));
    }

    function bcd_admin_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        echo '<h1 class="" style="font-size: 1rem;">Basic Cart Discounts</h1>';


        require_once 'partials/basic-woo-discounts-admin-display.php';

    }


    function bcd_admin_page_create()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        echo '<h1 class="" style="font-size: 1rem;">Basic Cart Discounts</h1>';
        require_once plugin_dir_path(__DIR__) . 'includes/classes/templates/new.php';
    }

    /**
     *The create new rule ajax callback function
     * @since    1.0.0
     */
    function bcd_create_new_rule()
    {
        //todo create nonce
        $woohandler = new \bcd\Woocommerce();
        $newRule = $woohandler->bcd_add_new_rule($_POST);

        if ($newRule) {
            wp_send_json_success($newRule);
        }
    }

    /*
     * The create update rule status ajax callback function
     */
    function bcd_update_rule_status()
    {
        //todo create nonce

        if (!$_POST['id']) {
            wp_send_json_error('error', 400);
        }

        $updated_status = \bcd\Database::bcd_toggle_rule_status($_POST['id']);

        wp_send_json_success($updated_status, 200);
    }

    /* Import woo scripts in order to use multi selections */
    function bcd_import_woo_scripts()
    {
        $curScreen = get_current_screen();

        if (is_admin() && $curScreen->base == 'admin_page_add-rule') {
            wp_enqueue_script('selectWoo', WP_PLUGIN_DIR . '/woocommerce/assets/js/selectWoo/selectWoo.full.min.js', array('jquery'), '1.0.6');
            wp_enqueue_script('wc-enhanced-select', WP_PLUGIN_DIR . '/woocommerce/assets/js/admin/wc-enhanced-select.min.js', array('jquery', 'selectWoo'), '123');
        }
    }

    /**
     * @param $cart
     *
     * @since    1.0.0
     */
    function bcd_add_cart_discount($cart)
    {

        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        $wooHandler = new \bcd\Woocommerce();
        $wooHandler->bcd_create_cart_discounts($cart);
    }

    /**
     * @param $query
     *
     * Remove Plugin made coupons
     * from classic made coupon list
     *
     * @since    1.0.0
     */
    function bcd_manage_admin_coupon_list($query)
    {

        if (!is_admin()) {
            return;
        }

        global $pagenow;

        if ($query->is_admin && $pagenow == 'edit.php' && $_GET['post_type'] == 'shop_coupon') {

            $meta_query[] = $query->get('meta_query', []);
            $meta_query = array(
                array(
                    'key' => '_bcd_discount_rule',
                    'compare' => 'NOT EXISTS',
                ),
            );

            $query->set('meta_query', $meta_query);
        }
    }

    function bcd_remove_coupon_html($coupon_html, $coupon, $discount_amount_html)
    {
        if (str_starts_with($coupon->code, "bcd_")) {
            return $discount_amount_html;
        }
        return $coupon_html;
    }

    function bcd_hide_coupon_code( $label, $coupon ) {

        if (str_starts_with($coupon->code, "bcd_")) {
            return apply_filters('bcd_discount_name', __('Discount'), $coupon);
        }

        return $label;
    }
}
