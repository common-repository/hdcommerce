<?php
/*
	HDCommerce custom post types
	Creates the hdc_product post type
	as well as the hdc_products taxonomy
*/

/* Register Products
------------------------------------------------------- */
function hdc_cpt_products()
{
    $labels = array(
        'name' => _x('Products', 'Post Type General Name', 'text_domain'),
        'singular_name' => _x('Product', 'Post Type Singular Name', 'text_domain'),
        'menu_name' => __('Products', 'text_domain'),
        'name_admin_bar' => __('Post Type', 'text_domain'),
        'archives' => __('Item Archives', 'text_domain'),
        'attributes' => __('Item Attributes', 'text_domain'),
        'parent_item_colon' => __('Parent Item:', 'text_domain'),
        'all_items' => __('All Products', 'text_domain'),
        'add_new_item' => __('Add New Product', 'text_domain'),
        'add_new' => __('Add New Product', 'text_domain'),
        'new_item' => __('New Product', 'text_domain'),
        'edit_item' => __('Edit Product', 'text_domain'),
        'update_item' => __('Update Product', 'text_domain'),
        'view_item' => __('View Product', 'text_domain'),
        'view_items' => __('View Products', 'text_domain'),
        'search_items' => __('Search Product', 'text_domain'),
        'not_found' => __('Not found', 'text_domain'),
        'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
        'featured_image' => __('Product Image', 'text_domain'),
        'set_featured_image' => __('Set product image', 'text_domain'),
        'remove_featured_image' => __('Remove product image', 'text_domain'),
        'use_featured_image' => __('Use as product image', 'text_domain'),
        'insert_into_item' => __('Insert into item', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
        'items_list' => __('Items list', 'text_domain'),
        'items_list_navigation' => __('Items list navigation', 'text_domain'),
        'filter_items_list' => __('Filter items list', 'text_domain'),
    );
    $rewrite = array(
        'slug' => 'product',
        'with_front' => true,
        'pages' => true,
        'feeds' => true,
    );
    $args = array(
        'label' => __('Product', 'text_domain'),
        'description' => __('Post Type Description', 'text_domain'),
        'labels' => $labels,
        'supports' => array('title'),
        'taxonomies' => array('hdc_products'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 2,
        'menu_icon' => 'dashicons-cart',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'rewrite' => $rewrite,
        'capability_type' => 'page',
    );
    register_post_type('hdc_product', $args);
}
add_action('init', 'hdc_cpt_products', 0);

/* Register Product Categories
------------------------------------------------------- */
function hdc_ctt_products()
{
    $labels = array(
        'name' => _x('Product Categories', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Category', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Product Categories', 'text_domain'),
        'all_items' => __('All Categories', 'text_domain'),
        'parent_item' => __('Parent Category', 'text_domain'),
        'parent_item_colon' => __('Parent Category:', 'text_domain'),
        'new_item_name' => __('New Category Name', 'text_domain'),
        'add_new_item' => __('Add New Category', 'text_domain'),
        'edit_item' => __('Edit Category', 'text_domain'),
        'update_item' => __('Update Category', 'text_domain'),
        'view_item' => __('View Category', 'text_domain'),
        'separate_items_with_commas' => __('Separate items with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove categories', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Items', 'text_domain'),
        'search_items' => __('Search Categories', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No categories', 'text_domain'),
        'items_list' => __('Category list', 'text_domain'),
        'items_list_navigation' => __('Items list navigation', 'text_domain'),
    );
    $rewrite = array(
        'slug' => 'product-category',
        'with_front' => true,
        'hierarchical' => false,
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => $rewrite,
    );
    register_taxonomy('hdc_products', array('hdc_product'), $args);
}
add_action('init', 'hdc_ctt_products', 0);

/* Register Orders
------------------------------------------------------- */
function hdc_cpt_orders()
{

    $labels = array(
        'name' => _x('Orders', 'Post Type General Name', 'text_domain'),
        'singular_name' => _x('Order', 'Post Type Singular Name', 'text_domain'),
        'menu_name' => __('Orders', 'text_domain'),
        'name_admin_bar' => __('Orders', 'text_domain'),
        'archives' => __('Order Archives', 'text_domain'),
        'attributes' => __('Order Attributes', 'text_domain'),
        'parent_item_colon' => __('Parent Order:', 'text_domain'),
        'all_items' => __('All Orders', 'text_domain'),
        'add_new_item' => __('Add New Order', 'text_domain'),
        'add_new' => __('Add New Order', 'text_domain'),
        'new_item' => __('New Order', 'text_domain'),
        'edit_item' => __('Edit Order', 'text_domain'),
        'update_item' => __('Update Order', 'text_domain'),
        'view_item' => __('View Order', 'text_domain'),
        'view_items' => __('View Orders', 'text_domain'),
        'search_items' => __('Search Order', 'text_domain'),
        'not_found' => __('Not found', 'text_domain'),
        'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
        'featured_image' => __('Featured Image', 'text_domain'),
        'set_featured_image' => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image' => __('Use as featured image', 'text_domain'),
        'insert_into_item' => __('Insert into item', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
        'items_list' => __('Items list', 'text_domain'),
        'items_list_navigation' => __('Items list navigation', 'text_domain'),
        'filter_items_list' => __('Filter items list', 'text_domain'),
    );
    $args = array(
        'label' => __('Order', 'text_domain'),
        'description' => __('Post Type Description', 'text_domain'),
        'labels' => $labels,
        'supports' => array(''),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-tag',
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'capability_type' => 'page',
        'capabilities' => array('create_posts' => 'do_not_allow'),
        'map_meta_cap' => true,
    );
    register_post_type('hdc_orders', $args);

}
add_action('init', 'hdc_cpt_orders', 0);

/* Register Coupons
------------------------------------------------------- */
function hdc_cpt_coupons()
{

    $labels = array(
        'name' => _x('Coupons', 'Post Type General Name', 'text_domain'),
        'singular_name' => _x('Coupon', 'Post Type Singular Name', 'text_domain'),
        'menu_name' => __('Coupons', 'text_domain'),
        'name_admin_bar' => __('Post Type', 'text_domain'),
        'archives' => __('Item Archives', 'text_domain'),
        'attributes' => __('Item Attributes', 'text_domain'),
        'parent_item_colon' => __('Parent Item:', 'text_domain'),
        'all_items' => __('All Coupons', 'text_domain'),
        'add_new_item' => __('Add New Coupon', 'text_domain'),
        'add_new' => __('New Coupon', 'text_domain'),
        'new_item' => __('New Coupon', 'text_domain'),
        'edit_item' => __('Edit Coupon', 'text_domain'),
        'update_item' => __('Update Coupon', 'text_domain'),
        'view_item' => __('View Coupon', 'text_domain'),
        'view_items' => __('View Coupons', 'text_domain'),
        'search_items' => __('Search Item', 'text_domain'),
        'not_found' => __('Not found', 'text_domain'),
        'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
        'featured_image' => __('Featured Image', 'text_domain'),
        'set_featured_image' => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image' => __('Use as featured image', 'text_domain'),
        'insert_into_item' => __('Insert into item', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
        'items_list' => __('Items list', 'text_domain'),
        'items_list_navigation' => __('Items list navigation', 'text_domain'),
        'filter_items_list' => __('Filter items list', 'text_domain'),
    );
    $args = array(
        'label' => __('Coupon', 'text_domain'),
        'description' => __('Post Type Description', 'text_domain'),
        'labels' => $labels,
        'supports' => array('title'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => false,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'capability_type' => 'page',
    );
    register_post_type('hdc_coupon', $args);

}
add_action('init', 'hdc_cpt_coupons', 0);

function hdc_change_coupon_title_text($title)
{
    $screen = get_current_screen();

    if ($screen->post_type === "hdc_coupon") {
        $title = 'Enter coupon code here...';
    }

    return $title;
}

add_filter('enter_title_here', 'hdc_change_coupon_title_text');
