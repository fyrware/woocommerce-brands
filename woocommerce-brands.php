<?php
/**
 * Plugin Name: WooCommerce Brands
 * Plugin URI: https://fyrware.com/
 * Description: Create and organize products by brand
 * Author: Fyrware
 * Version: 1.0.0
 * Author URI: https://fyrware.com
 * Text Domain: wc-brands
 */

const WC_BRANDS_TEXT_DOMAIN = 'wc-brands';

function wc_brands_can_run(): bool {
    return function_exists('is_yuzu')
        && function_exists('is_woocommerce');
}

register_activation_hook(__FILE__, function() {
    if (!wc_brands_can_run()) {
        wp_die('Missing required dependencies for WooCommerce Brands.');
    }
});

add_action('admin_notices', function() {
    if (!wc_brands_can_run()) { ?>
        <div class="notice notice-error">
            <p>
                <strong>Missing Dependencis:</strong>
                Some plugin dependencies are missing for <em>WooCommerce Brands</em>.
                Please consult the documentation for more information.
            </p>
        </div>
    <?php }
});

add_action('plugins_loaded', function() {
    if (wc_brands_can_run()) {
        add_action('woocommerce_init', 'wc_brands_init');
        add_action('product_brand_add_form_fields', 'wc_brands_add_form_fields');
        add_action('product_brand_edit_form_fields', 'wc_brands_edit_form_fields');
        add_action('create_term', 'wc_brands_save_form_fields', 10, 3);
        add_action('edit_term', 'wc_brands_save_form_fields', 10, 3);
    }
});

require_once plugin_dir_path(__FILE__) . 'functions.php';
