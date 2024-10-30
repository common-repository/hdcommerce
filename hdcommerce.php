<?php
/*
 * Plugin Name: HDCommerce
 * Description: The ultimate eCommerce experience.
 * Plugin URI: https://hdselling.com?utm_source=HDCommerce&utm_medium=pluginPage
 * Author: Harmonic Design
 * Author URI: https://harmonicdesign.ca?utm_source=HDCommerce&utm_medium=pluginPage
 * Version: 0.8
 * Notes: This plugin is still in the early stages of development, and as such, some features you require may not have been implimented yet. Please check the plugin page for a list of missing/upcoming features to ensure that HDCommerce can meet your needs.
*/

// ENHANCE: if product not available at cart, remove it from cookie

/*
 * = Developer Notes: TODO =
 * -- Need to redo settings page to follow new standards of product page
 * 
 * [enhance] make product_edit variation fields hookable
 * [feature] edit product page: create "set variation" buttons to update all variations with shipping info or sales info etc.
 * [enhance] introduce new columns for product_edit = col-2-1, col-1-2, col-1-1-1-1 etc
 * [feature] create custom tax amount per product and variation
 * [enhance] allow store owner to customize thank you page content
 * [enhance] allow store owner to customer order email
 * [enhance] also show custom fields in sent emails
 * [enhance] better accessability (proper elements and aria)
 * [feature] add shop/category page ordering again
 * [enhance] delete the cart after order completion
 * [feature] related products
 * [feature] option to auto redirect to cart
 * [feature] cart upsells
 *
*/

if (!defined('ABSPATH')) {
	exit;
}

if (!defined('HDC_PLUGIN_VERSION')) {
    define('HDC_PLUGIN_VERSION', '0.8');
}

/* Include the basic required files
------------------------------------------------------- */
require dirname(__FILE__) . '/includes/functions.php'; // main hooks and functions
require dirname(__FILE__) . '/includes/settings.php'; // settings page
require dirname(__FILE__) . '/includes/addons.php'; // addons page
require dirname(__FILE__) . '/includes/post_type.php'; // custom post types
require dirname(__FILE__) . '/includes/meta.php'; // custom meta

// function to check if HDCommerce is active
function hdc_exists()
{
    return;
}

/* Set HDCommerce plugin action links (settings, docs)
------------------------------------------------------- */
function hdc_plugin_action_links($links)
{
    $links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=hdc_options')) . '">Settings</a>';
    $links[] = '<a href="https://hdselling.com/docs?utm_source=HDCommerce&utm_medium=pluginPage" target="_blank">Documentation</a>';
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'hdc_plugin_action_links', 10, 2);

/* Make HDCommerce pages use our custom product template
------------------------------------------------------- */
function hdc_add_product_template($template)
{
    global $post;
    // Product page
    if ($post->post_type == 'hdc_product') {
        // check if there is a custom template in the theme folder
        $template_override = get_template_directory() . '/hdcommerce/single-product.php';
        if (file_exists($template_override)) {
            $template = $template_override;
        } else {
            $template = dirname(__FILE__) . '/includes/templates/single-product.php';
        }
    }
    // Product category page
    if (is_tax('hdc_products')) {
        $template_override = get_template_directory() . '/hdcommerce/category-product.php';
        // check if there is a custom template in the theme folder
        if (file_exists($template_override)) {
            $template = $template_override;
        } else {
            $template = dirname(__FILE__) . '/includes/templates/category-product.php';
        }

    }
    return $template;
}
add_filter('single_template', 'hdc_add_product_template');

/* Make HDCommerce pages use our custom product category template
------------------------------------------------------- */
function hdc_add_product_category_template($template)
{
    global $post;
    // Product category page
    if (is_tax('hdc_products')) {
        $template_override = get_template_directory() . '/hdcommerce/category-product.php';
        // check if there is a custom template in the theme folder
        if (file_exists($template_override)) {
            $template = $template_override;
        } else {
            $template = dirname(__FILE__) . '/includes/templates/category-product.php';
        }
    }
    return $template;
}
add_filter('archive_template', 'hdc_add_product_category_template');

/* Make HDCommerce pages use our custom templates
------------------------------------------------------- */
function hdc_add_shop_page($template)
{
    global $post;
    $hdc_shop_page = get_option('hdc_store_page');
    $hdc_checkout_page = get_option('hdc_checkout_page');
    $hdc_payment_page = get_option('hdc_payment_page');

    if ($post->ID == $hdc_shop_page) {
        $template_override = get_template_directory() . '/hdcommerce/shop.php';
        // check if there is a custom template in the theme folder
        if (file_exists($template_override)) {
            $template = $template_override;
        } else {
            $template = dirname(__FILE__) . '/includes/templates/shop.php';
        }
    }
    if ($post->ID == $hdc_checkout_page) {
        $template_override = get_template_directory() . '/hdcommerce/checkout.php';
        // check if there is a custom template in the theme folder
        if (file_exists($template_override)) {
            $template = $template_override;
        } else {
            $template = dirname(__FILE__) . '/includes/templates/checkout.php';
        }
    }
    if ($post->ID == $hdc_payment_page) {
        $template_override = get_template_directory() . '/hdcommerce/payment.php';
        // check if there is a custom template in the theme folder
        if (file_exists($template_override)) {
            $template = $template_override;
        } else {
            $template = dirname(__FILE__) . '/includes/templates/payment.php';
        }
    }
    return $template;
}
add_filter('page_template', 'hdc_add_shop_page');

/* Run the following on HDCommerce plugin activation
------------------------------------------------------- */
function hdc_activate_plugin()
{
    hdc_cpt_products(); // register products
    hdc_ctt_products(); // register taxonomy
    flush_rewrite_rules(); // flush permalinks

    // update plugin version
    update_option("HDC_PLUGIN_VERSION", HDC_PLUGIN_VERSION);

    // Create HDCommerce required pages if the do not already exist
    $hdc_shop_page = get_option('hdc_store_page');
    $hdc_checkout_page = get_option('hdc_checkout_page');
    $hdc_payment_page = get_option('hdc_payment_page');

    // TODO:
    // need to extend to also check if the page with ID has been deleted
    if ($hdc_shop_page == "" || $hdc_shop_page == null) {
        // Save the invoice - name and editor
        $post_information = array(
            'post_title' => "Shop",
            'post_content' => '<p>Please do not edit or delete this page. This page is needed for HDCommerce</p>',
            'post_type' => 'page',
            'post_status' => 'publish',
        );
        $post_id = wp_insert_post($post_information);
        update_option("hdc_store_page", $post_id);
    }

    if ($hdc_checkout_page == "" || $hdc_checkout_page == null) {
        $post_information = array(
            'post_title' => "Cart",
            'post_content' => '<p>Please do not edit or delete this page. This page is needed for HDCommerce</p>',
            'post_type' => 'page',
            'post_status' => 'publish',
        );
        $post_id = wp_insert_post($post_information);
        update_option("hdc_checkout_page", $post_id);
    }

    if ($hdc_payment_page == "" || $hdc_payment_page == null) {
        $post_information = array(
            'post_title' => "Payment",
            'post_content' => '<p>Please do not edit or delete this page. This page is needed for HDCommerce</p>',
            'post_type' => 'page',
            'post_status' => 'publish',
        );
        $post_id = wp_insert_post($post_information);
        update_option("hdc_payment_page", $post_id);
    }
}
register_activation_hook(__FILE__, 'hdc_activate_plugin');



/* Check if HDCommerce has updated
------------------------------------------------------- */
function hdc_check_upgrade() {
	// since modern WP allows for ajax upgrade, "register_activation_hook"
	// is not good for our purposes
    $hdc_version = floatval(get_option("HDC_PLUGIN_VERSION"));
    if (HDC_PLUGIN_VERSION != $hdc_version) {
        update_option("HDC_PLUGIN_VERSION", HDC_PLUGIN_VERSION);
        echo '
		<div class="notice notice-success is-dismissible">
			<p><strong>HDCommerce</strong>. Thank you for upgrading. If you experience any issues at all, please don\'t hesitate to <a href = "https://wordpress.org/support/plugin/hdcommerce" target = "_blank">reach out for support</a>! I\'m always glad to help when I can.</p>
			<p>Please also remember that HDCommerce is currently in beta, and things are going to change version to version. Always check your products and settings after upgrading, and test the cart/checkout as well.</p>
		</div>';

        if ($hdc_version != 0 && $hdc_version < HDC_PLUGIN_VERSION) {
            // attempt to auto upgrade products as best as we can
            hdc_upgrade();
        }
    }	
}
add_action( 'plugins_loaded', 'hdc_check_upgrade' );




/* New hook examples for 
 * custom product meta and product tabs
------------------------------------------------------- */

/* Example usage of adding a new TAB to product edit page, and adding custom meta */
function add_custom_product_tabs()
{
    global $tabs;
    $tab = new \stdClass();
    $tab->slug = "downloads";
    $tab->title = "Downloads";
    array_push($tabs, $tab);
}
// add_action( 'hdc_add_product_admin_tab', 'add_custom_product_tabs');

function add_custom_fields_to_products()
{
    global $additional_fields;
    $f = array();
    $f["tab"] = "downloads";
    $f["type"] = "col-1-1";

    $children = array();

    $child = array();
    $child["type"] = "text";
    $child["name"] = "test-text";
    $child["label"] = "Hello BBY";
    $child["required"] = false;
    array_push($children, $child);

    $child = array();
    $child["type"] = "currency";
    $child["name"] = "test-text222";
    $child["label"] = "Hello BBY";
    $child["required"] = true;
    array_push($children, $child);

    $f["children"] = $children;
    array_push($additional_fields, $f);
}
//add_action( 'hdc_add_product_meta', 'add_custom_fields_to_products');
