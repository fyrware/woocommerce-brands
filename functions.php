<?php

function wc_brands_init(): void {
    register_taxonomy(WC_BRANDS_TAXONOMY_NAME, 'product', array(
        'label' => __('Brand', WC_BRANDS_TEXT_DOMAIN),
        'rewrite' => array('slug' => 'brand'),
        'hierarchical' => true,
//        'show_admin_column' => true,
        'show_in_rest' => true,
        'show_in_quick_edit' => true,
        'show_in_nav_menus' => true,
        'show_in_menu' => true,
        'show_ui' => true,
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

function wc_brands_add_form_fields(): void {
    yz_form_field([
        'id'    => 'logo',
        'label' => __('Logo', WC_BRANDS_TEXT_DOMAIN),
        'type'  => 'media',
    ]);
    yz_form_field([
        'id'    => 'primary-color',
        'label' => __('Primary Color', WC_BRANDS_TEXT_DOMAIN),
        'type'  => 'color',
    ]);
}

function wc_brands_edit_form_fields(WP_Term $term): void {
    $logo          = absint(get_term_meta($term->term_id, 'logo', true));
    $logo_src      = wp_get_attachment_thumb_url($logo);
    $primary_color = get_term_meta($term->term_id, 'primary-color', true);

    yz_element('tr', [
        'class'    => '',
        'children' => function() use($logo, $logo_src) {
            yz_element('th', [
                'children' => function() {
                    yz_text(__('Logo', WC_BRANDS_TEXT_DOMAIN), [
                        'variant'    => 'label',
                        'attributes' => ['for' => 'logo']
                    ]);
                }
            ]);
            yz_element('td', [
                'children' => function() use($logo, $logo_src) {
                    yz_media_picker([
                        'id'      => 'logo',
                        'value'   => $logo,
                        'preview' => $logo_src,
                    ]);
                }
            ]);
        }
    ]);
    yz_element('tr', [
        'class'    => '',
        'children' => function() use($primary_color) {
            yz_element('th', [
                'children' => function() {
                    yz_text(__('Primary Color', WC_BRANDS_TEXT_DOMAIN), [
                        'variant'    => 'label',
                        'attributes' => ['for' => 'primary-color']
                    ]);
                }
            ]);
            yz_element('td', [
                'children' => function() use($primary_color) {
                    yz_color_picker([
                        'id'    => 'primary-color',
                        'value' => $primary_color,
                    ]);
                }
            ]);
        }
    ]);
}

function wc_brands_save_form_fields(int $term_id, string $taxonomy_id, string $taxonomy): void {
    if ($taxonomy === WC_BRANDS_TAXONOMY_NAME) {
        $logo          = $_POST['logo'] ?? '';
        $primary_color = $_POST['primary-color'] ?? '';

        if (!empty($logo)) {
            update_term_meta($term_id, 'logo', $logo);
        }

        if (!empty($primary_color)) {
            update_term_meta($term_id, 'primary-color', $primary_color);
        }
    }
}