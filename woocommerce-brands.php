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

function wc_brands_activate(): void {
    $yuzu_exists = function_exists('yuzu_init');

    if (!$yuzu_exists) {
        wp_die('Yuzu Framework is required to activate WooCommerce Brands.');
    }
}

register_activation_hook(__FILE__, 'wc_brands_activate');

function wc_brands_notify_user(): void {
    $yuzu_exists = function_exists('yuzu_init');

    if (!$yuzu_exists) { ?>
        <div class="notice notice-error">
            <p><strong>Missing Dependency</strong>: Yuzu Framework is required by WooCommerce Brands</p>
        </div>
    <?php }
}

add_action('admin_notices', 'wc_brands_notify_user');

function wc_brands_init(): void {
    $yuzu_exists = function_exists('yuzu_init');

    register_taxonomy('product_brand', 'product', array(
        'label' => __('Brand', WC_BRANDS_TEXT_DOMAIN),
        'rewrite' => array('slug' => 'brand'),
        'hierarchical' => true,
        'show_admin_column' => $yuzu_exists,
        'show_in_rest' => $yuzu_exists,
        'show_in_quick_edit' => $yuzu_exists,
        'show_in_nav_menus' => $yuzu_exists,
        'show_in_menu' => $yuzu_exists,
        'show_ui' => $yuzu_exists,
        'labels' => array(
            'parent_item' => __('Parent Brand', WC_BRANDS_TEXT_DOMAIN),
            'parent_item_colon' => __('Parent Brand:', WC_BRANDS_TEXT_DOMAIN),
            'name' => __('Product Brands', WC_BRANDS_TEXT_DOMAIN),
            'singular_name' => __('Brand', WC_BRANDS_TEXT_DOMAIN),
            'menu_name' => __('Brands', WC_BRANDS_TEXT_DOMAIN),
            'search_items' => __('Search Brands', WC_BRANDS_TEXT_DOMAIN),
            'popular_items' => __('Popular Brands', WC_BRANDS_TEXT_DOMAIN),
            'all_items' => __('All Brands', WC_BRANDS_TEXT_DOMAIN),
            'edit_item' => __('Edit Brand', WC_BRANDS_TEXT_DOMAIN),
            'update_item' => __('Update Brand', WC_BRANDS_TEXT_DOMAIN),
            'add_new_item' => __('Add New Brand', WC_BRANDS_TEXT_DOMAIN),
            'new_item_name' => __('New Brand Name', WC_BRANDS_TEXT_DOMAIN),
            'separate_items_with_commas' => __('Separate brands with commas', WC_BRANDS_TEXT_DOMAIN),
            'add_or_remove_items' => __('Add or remove brands', WC_BRANDS_TEXT_DOMAIN),
            'choose_from_most_used' => __('Choose from the most used brands', WC_BRANDS_TEXT_DOMAIN),
            'not_found' => __('No brands found', WC_BRANDS_TEXT_DOMAIN),
            'no_terms' => __('No brands', WC_BRANDS_TEXT_DOMAIN),
            'items_list_navigation' => __('Brands list navigation', WC_BRANDS_TEXT_DOMAIN),
            'items_list' => __('Brands list', WC_BRANDS_TEXT_DOMAIN),
            'back_to_items' => __('&larr; Back to Brands', WC_BRANDS_TEXT_DOMAIN),
        )
    ));
}

add_action('woocommerce_init', 'wc_brands_init');

function wc_brands_add_form_fields(): void {
    $yuzu_exists = function_exists('yuzu_init');

    if ($yuzu_exists) {
        yuzu_render_admin_media_field(array(
            'id' => 'logo_id',
            'label' => __('Logo', WC_BRANDS_TEXT_DOMAIN),
        ));
        yuzu_render_admin_color_field(array(
            'id' => 'primary_color',
            'label' => __('Primary Color', WC_BRANDS_TEXT_DOMAIN),
        ));
    }
}

add_action('product_brand_add_form_fields', 'wc_brands_add_form_fields');

function wc_brands_edit_form_fields(WP_Term $term): void {
    $yuzu_exists = function_exists('yuzu_init');

    if ($yuzu_exists) {
        $logo_id = absint(get_term_meta($term->term_id, 'logo_id', true));
        $logo_src = wp_get_attachment_thumb_url($logo_id);
        $primary_color = get_term_meta($term->term_id, 'primary_color', true);

        yuzu_render_admin_media_field(array(
            'id' => 'logo_id',
            'label' => __('Logo', WC_BRANDS_TEXT_DOMAIN),
            'value' => $logo_id,
            'preview' => $logo_src,
            'display_as' => 'table'
        ));

        yuzu_render_admin_color_field(array(
            'id' => 'primary_color',
            'label' => __('Primary Color', WC_BRANDS_TEXT_DOMAIN),
            'value' => $primary_color,
            'display_as' => 'table'
        ));
    }
}

add_action('product_brand_edit_form_fields', 'wc_brands_edit_form_fields');

function wc_brands_save_form_fields(int $term_id, string $taxonomy_id, string $taxonomy): void {
    $logo_id = $_POST['logo_id'] ?? '';
    $primary_color = $_POST['primary_color'] ?? '';

    if (!empty($logo_id) && $taxonomy === 'product_brand') {
        update_term_meta($term_id, 'logo_id', $logo_id);
    }

    if (!empty($primary_color) && $taxonomy === 'product_brand') {
        update_term_meta($term_id, 'primary_color', $primary_color);
    }
}

add_action('create_term', 'wc_brands_save_form_fields', 10, 3);
add_action('edit_term', 'wc_brands_save_form_fields', 10, 3);
