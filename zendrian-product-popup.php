<?php
/*
Plugin Name: Zendrian Product Popup
Plugin URI: https://github.com/your-username/zendrian-product-popup 
Description: A simple WooCommerce product popup plugin
Version: 1.1
Author: Zendrian
Author URI: https://github.com/ZenDevMaster (replace with your profile URL)
GitHub Plugin URI: https://github.com/ZenDevMaster/zendrian-product-popup 
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Check if Woocommerce is installed and active before allowing the plugin to be activated
add_action('plugins_loaded', 'zendrian_product_popup_init');

function zendrian_product_popup_init()
{
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'zendrian_product_popup_wc_not_installed');
        return;
    }

    // Load CSS and JS files
    add_action('wp_enqueue_scripts', 'zendrian_product_popup_enqueue_scripts_styles');
    add_action('admin_enqueue_scripts', 'zendrian_product_popup_enqueue_scripts_styles');

    // Initialize plugin settings
    require_once plugin_dir_path(__FILE__) . 'settings/zpp-settings.php';

    // Include functions
    require_once plugin_dir_path(__FILE__) . 'functions/zpp-functions.php';
}

function zendrian_product_popup_wc_not_installed()
{
    echo '<div class="notice notice-error is-dismissible"><p>Please install and activate WooCommerce to use the Zendrian Product Popup plugin.</p></div>';
}

function zendrian_product_popup_enqueue_scripts_styles()
{
    wp_enqueue_style('zendrian-product-popup', plugins_url('assets/css/zendrian-product-popup.css', __FILE__));
    wp_enqueue_script('zendrian-product-popup', plugins_url('assets/js/zendrian-product-popup.js', __FILE__), array('jquery'), '', true);
}

?>