<?php
/*
	HDCommerce Header
	Enqueues the style and js,
	creates the cart cookie,
	and grabs the theme header
*/

global $hdc_product_view_type;
$hdc_product_view_type = get_option("hdc_product_view_type");

global $hdc_product_headings;
$hdc_product_headings = get_option("hdc_product_headings");
$hdc_product_headings = strtolower($hdc_product_headings);

global $hdc_disable_reviews;
$hdc_disable_reviews = get_option("hdc_disable_reviews");

// figure out which HDCommerce page we are on
$hdc_id = get_the_ID();
$hdc_shop_page = intval(get_option('hdc_store_page'));
global $hdc_checkout_page;
$hdc_checkout_page = intval(get_option('hdc_checkout_page'));
$hdc_payment_page = intval(get_option('hdc_payment_page'));

wp_enqueue_style(
    'hdc_style',
    plugin_dir_url(__FILE__) . '../css/hdc_style.css'
);

wp_enqueue_script(
    'hdc_script',
    plugins_url('../js/hdc_script.js', __FILE__),
    array('jquery'),
    '1.0',
    true
);

wp_localize_script(
    'hdc_script',
    'hdc_id',
    "$hdc_id"
);

wp_localize_script(
    'hdc_script',
    'hdc_checkout_page',
    "$hdc_checkout_page"
);

wp_localize_script(
    'hdc_script',
    'hdc_ajax',
    admin_url('admin-ajax.php')
);

wp_localize_script(
    'hdc_script',
    'hdc_currency_symbol',
    get_option('hdc_store_currency')
);

// if we're on the checkout or payment page, attempt to stop caching
if ($hdc_id == $hdc_checkout_page || $hdc_id == $hdc_payment_page) {
    // WP Fastest Cache
    echo '<!-- [wpfcNOT] -->';
    if (function_exists('wpfc_exclude_current_page')) {
        wpfc_exclude_current_page();
    }
    // W3 Total Cache, WP Super Cache, WP Rocket, Comet Cache, Cachify
    define('DONOTCACHEPAGE', true);
    define('DONOTCACHEDB', true);
}

if ($hdc_id == $hdc_checkout_page) {

    global $hdc_cart_products;
    // get cookie info
    $hdc_cart_products = array();
    if (!isset($_COOKIE['hdc_cart_' . $hdc_checkout_page])) {
        // set cookie and expire in 3 days
        $data = json_encode($hdc_cart_products);
        //$data = base64_encode($data);
        setcookie('hdc_cart_' . $hdc_checkout_page, $data, time() + 259200, '/');
    } else {
        $hdc_cart_products = $_COOKIE['hdc_cart_' . $hdc_checkout_page];
        $hdc_cart_products = stripslashes($hdc_cart_products);
        $hdc_cart_products = json_decode(html_entity_decode($hdc_cart_products), true);
    }

    global $hdc_store_selling_countries;
    $hdc_store_selling_countries = get_option("hdc_store_selling_countries");

    if ($hdc_store_selling_countries != "" && $hdc_store_selling_countries != null) {
        $hdc_store_selling_countries = str_replace("\\", "", $hdc_store_selling_countries);

        if ($hdc_store_selling_countries != "" && $hdc_store_selling_countries != null) {
            $hdc_store_selling_countries = json_decode($hdc_store_selling_countries, true);
        }
    }

    $hdc_tax_inclusive = get_option("hdc_tax_inclusive");
    if ($hdc_tax_inclusive == 1) {
        $hdc_tax_inclusive = "yes";
    } else {
        $hdc_tax_inclusive = "no";
    }

    global $hdc_payment_page;
    $hdc_payment_page = get_option("hdc_payment_page");
    $hdc_payment_page = get_the_permalink($hdc_payment_page);

    global $hdc_shipping_disable;
    $hdc_shipping_disable = get_option("hdc_shipping_disable");
    if ($hdc_shipping_disable != 1) {
        $hdc_shipping_disable = 0;
    }
    wp_localize_script(
        'hdc_script',
        'hdc_shipping_disable',
        "$hdc_shipping_disable"
    );

    global $hdc_shipping_free;
    $hdc_shipping_free = get_option("hdc_shipping_free");

    wp_localize_script(
        'hdc_script',
        'hdc_shipping_free',
        $hdc_shipping_free
    );

    global $hdc_payment_gateway;
    $hdc_payment_gateway = get_option("hdc_payment_gateway");
    $hdc_payment_gateway = explode("|", $hdc_payment_gateway);
    $hdc_payment_gateway = $hdc_payment_gateway[0];

	if($hdc_payment_gateway === "paypal"){
		function set_paypal_payment_form($hdc_payment_form) {
			$hdc_paypal_sandbox = get_option("hdc_paypal_sandbox");
			if($hdc_paypal_sandbox == 1){
				$hdc_payment_form = "https://www.sandbox.paypal.com/";	
			} else {
				$hdc_payment_form = "https://www.paypal.com/cgi-bin/webscr";	
			}			
			return $hdc_payment_form;
		}
		add_filter( 'hdc_payment_form_location', 'set_paypal_payment_form', 10, 1);
	}
	
	
	global $hdc_payment_form;
	$hdc_payment_form = $hdc_payment_page;
	// allow gateways to overrride this
	$hdc_payment_form = apply_filters("hdc_payment_form_location", $hdc_payment_form);
	
    wp_localize_script(
        'hdc_script',
        'hdc_payment_gateway',
        $hdc_payment_gateway
    );

    wp_localize_script(
        'hdc_script',
        'hdc_tax_inclusive',
        $hdc_tax_inclusive
    );

    wp_localize_script(
        'hdc_script',
        'hdc_tax',
        str_replace("\\", "", get_option("hdc_tax_chart"))
    );
}

get_header();
