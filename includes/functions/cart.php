<?php
/*
	HDCommerce Cart Functions
	Functions that hand the cart.
	Adding/removing products
*/

/* If product is added to the cart
------------------------------------------------------- */
function hdc_cart_cookies()
{
    $hdc_product_added = false;
    // check if there is post data
    if (isset($_POST["hdc_form_submit"]) && $_POST["hdc_form_submit"] == 'Y') {

        $hdc_id = get_the_ID();
        $hdc_checkout_page = intval(get_option('hdc_checkout_page'));

        // check for cart data
        $hdc_cart_products = array();
        if (!isset($_COOKIE['hdc_cart_' . $hdc_checkout_page])) {
            // set cookie and expire in 3 days
            $data = json_encode($hdc_cart_products);
            //$data = base64_encode($data);
            setcookie('hdc_cart_' . $hdc_checkout_page, $data, time() + 259200, '/');
        } else {
            $hdc_cart_products = stripslashes($_COOKIE['hdc_cart_' . $hdc_checkout_page]);
            $hdc_cart_products = json_decode(html_entity_decode($hdc_cart_products), true);
        }

        $already_in_cart = false;
        $counter = 0;
        $key = 0;

        foreach ($hdc_cart_products as $value) {
            // check if same post ID
            if ($value[0] == $hdc_id) {
                if ($value[1] == $_POST["hdc_variation_name"]) {
                    $already_in_cart = true;
                    $key = $counter;
                }
            }
            $counter = $counter + 1;
        }

        if (!$already_in_cart) {
            array_push($hdc_cart_products, array($_POST["hdc_product_id"], $_POST["hdc_variation_name"], 1));
        } else {
            $quantity = $hdc_cart_products[$key][2] + 1;
            $hdc_cart_products[$key] = array($_POST["hdc_product_id"], $_POST["hdc_variation_name"], $quantity);
        }
        global $hdc_product_added;
        $hdc_product_added = true;

        // overwrite the cookie
        $hdc_cart_products = json_encode($hdc_cart_products);
        setcookie('hdc_cart_' . $hdc_checkout_page, $hdc_cart_products, time() + 259200, '/');
    }
}


/* Update cart cookie when quantity changed
------------------------------------------------------- */
function hdc_update_cart_cookie_quantity()
{
    // get cookie
    $hdc_checkout_page = intval(get_option('hdc_checkout_page'));
    $hdc_cart_products = stripslashes($_COOKIE['hdc_cart_' . $hdc_checkout_page]);	
    $hdc_cart_products = json_decode(html_entity_decode($hdc_cart_products), true);
	$data = $_POST["data"];
	$data["quantity"] = intval($data["quantity"]);
	$data["id"] = intval($data["id"]);
	$data["permutation"] = sanitize_text_field($data["permutation"]);
	
	for($i = 0; $i < count($hdc_cart_products); $i++){
		if($hdc_cart_products[$i][0] == $data["id"]){
			$hdc_cart_products[$i][2] = $data["quantity"];
			echo 'success';
			break;
		}
	}
	
    // overwrite the cookie
    $hdc_cart_products = json_encode($hdc_cart_products);
    setcookie('hdc_cart_' . $hdc_checkout_page, $hdc_cart_products, time() + 259200, '/');	
    die();
}
add_action('wp_ajax_nopriv_hdc_update_cart_cookie_quantity', 'hdc_update_cart_cookie_quantity');
add_action('wp_ajax_hdc_update_cart_cookie_quantity', 'hdc_update_cart_cookie_quantity');

/* Remove product from the cart
------------------------------------------------------- */
function hdc_remove_product_cart()
{
    // get cookie
    $hdc_checkout_page = intval(get_option('hdc_checkout_page'));
    $hdc_cart_products = stripslashes($_COOKIE['hdc_cart_' . $hdc_checkout_page]);
    $hdc_cart_products = json_decode(html_entity_decode($hdc_cart_products), true);

    $remove = intval($_POST['key']);
    $key2 = null;
    $counter = 0;
    foreach ($hdc_cart_products as $value => $key) {
        if ($counter == $remove) {
            $key2 = $value;
        }
        $counter = $counter + 1;
    }
    unset($hdc_cart_products[$key2]);

    // overwrite the cookie
    $hdc_cart_products = json_encode($hdc_cart_products);
    setcookie('hdc_cart_' . $hdc_checkout_page, $hdc_cart_products, time() + 259200, '/');
    die();
}
add_action('wp_ajax_nopriv_hdc_remove_product_cart', 'hdc_remove_product_cart');
add_action('wp_ajax_hdc_remove_product_cart', 'hdc_remove_product_cart');

/* Get the data from the submitted cart
------------------------------------------------------- */
function hdc_get_cart_post_data()
{
    global $hdc_form;
    $hdc_form = new \stdClass();

    $hdc_store_currency = get_option("hdc_store_currency");
    $hdc_store_currency = explode("|", $hdc_store_currency);

    // if shipping was disabled, this will be blank
    $hdc_shipping_method = "";
    if (isset($_POST['hdc_shipping_method'])) {
        $hdc_shipping_method = $_POST['hdc_shipping_method'];
    }

    $hdc_form->hdc_store_currency = $hdc_store_currency[0];
    $hdc_form->hdc_checkout_first_name = sanitize_text_field($_POST['hdc_checkout_first_name']);
    $hdc_form->hdc_checkout_last_name = sanitize_text_field($_POST['hdc_checkout_last_name']);
    $hdc_form->hdc_checkout_email = sanitize_email($_POST['hdc_checkout_email']);
    $hdc_form->hdc_checkout_phone = sanitize_text_field($_POST['hdc_checkout_phone']);
    $hdc_form->hdc_checkout_country = sanitize_text_field($_POST['hdc_checkout_country']);
    $hdc_form->hdc_checkout_state = sanitize_text_field($_POST['hdc_checkout_state']);
    $hdc_form->hdc_checkout_address = sanitize_text_field($_POST['hdc_checkout_address']);
    $hdc_form->hdc_checkout_address2 = sanitize_text_field($_POST['hdc_checkout_address2']);
    $hdc_form->hdc_checkout_city = sanitize_text_field($_POST['hdc_checkout_city']);
    $hdc_form->hdc_checkout_zip = sanitize_text_field($_POST['hdc_checkout_zip']);
    $hdc_form->hdc_shipping_method = floatval($hdc_shipping_method);
    $hdc_form->hdc_shipping_method_name = sanitize_text_field($_POST['hdc_shipping_method_name']);
    $hdc_form->hdc_checkout_tax = sanitize_text_field($_POST['hdc_checkout_tax']);
    $hdc_form->hdc_checkout_tax_amount = floatval($_POST['hdc_checkout_tax_amount']);
    $hdc_form->hdc_checkout_note = wp_kses_post($_POST['hdc_checkout_note']);
    $hdc_form->hdc_checkout_products = sanitize_text_field($_POST['hdc_checkout_products']);
    $hdc_form->hdc_coupon_codes = sanitize_text_field($_POST['hdc_coupon_codes']);
    $hdc_form->hdc_payment_amount = floatval($_POST['hdc_payment_amount']);

    hdc_get_extra_form_fields(); // get extra fields submitted to the checkout

    return $hdc_form;
}
