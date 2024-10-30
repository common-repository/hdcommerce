<?php
/*
    HDCommerce primary functions file
    Generic/Global functions are located here
*/

/* Include the basic required files
------------------------------------------------------- */
require dirname(__FILE__) . '/functions/admin.php'; // admin functions and layout
require dirname(__FILE__) . '/functions/save_edit_product.php'; // admin product
require dirname(__FILE__) . '/functions/template_functions.php'; // functions for HDC templates
require dirname(__FILE__) . '/functions/payment_gateway.php'; // functions for payment gateways
require dirname(__FILE__) . '/functions/shipping.php'; // functions for shipping
require dirname(__FILE__) . '/functions/cart.php'; // functions for handling the cart

/* Converts number into the HDC currency amount
------------------------------------------------------- */
function hdc_amount($amount = "")
{
    $currencyL = "";
    $currencyR = "";
    $currency = get_option('hdc_store_currency');
    if ($currency == "" || $currency == null) {
        $currency = "USD|$|l";
    }
    $currency = explode("|", $currency);

    if ($currency[2] == "l") {
        $currencyL = $currency[1];
    } else {
        $currencyR = $currency[1];
    }

    $currency = $currencyL . number_format((float)$amount, 2) . $currencyR;
    return $currency;
}

function hdc_get_currency_symbol()
{
    $currency = get_option('hdc_store_currency');
    if ($currency == "" || $currency == null) {
        $currency = "USD|$|l";
    }
    $currency = explode("|", $currency);
    return $currency[1];
}

function hdc_get_shipping_units()
{
    $hdc_shipping_unit = intval(get_option("hdc_shipping_unit"));
    if ($hdc_shipping_unit != 1) {
        $hdc_size_unit = "cm";
        $hdc_weight_unit = "kg";
    } else {
        $hdc_size_unit = "inches";
        $hdc_weight_unit = "lbs";
    }
    return array($hdc_size_unit, $hdc_weight_unit);
}

// mimic javaScripts encodeURIComponent
function hdc_encodeURIComponent($str)
{
    $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
    return strtr(rawurlencode($str), $revert);
}

// NOTE: PREFIX
function encrypt_openssl($msg, $key, $iv = null)
{
    $iv_size = openssl_cipher_iv_length('AES-128-CBC');
    if (!$iv) {
        $iv = openssl_random_pseudo_bytes($iv_size);
    }
    $encryptedMessage = openssl_encrypt($msg, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encryptedMessage);
}

// NOTE: PREFIX
function decrypt_openssl($payload, $key)
{
    $raw = base64_decode($payload);
    $iv_size = openssl_cipher_iv_length('AES-128-CBC');
    $iv = substr($raw, 0, $iv_size);
    $data = substr($raw, $iv_size);
    return openssl_decrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
}

/* Generate the order id
NOTE: I'm really just using this to generate unique
invoice IDs at the moment, but this will also be
useful If I integrate HDInvoice at a later date
------------------------------------------------------- */
function hdc_generate_order_id($length)
{
    $add_dashes = false;
    $available_sets = 'luds';
    $sets = array();
    if (strpos($available_sets, 'l') !== false) {
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    }

    if (strpos($available_sets, 'u') !== false) {
        $sets[] = '1234567';
    }

    if (strpos($available_sets, 'd') !== false) {
        $sets[] = '23456789';
    }

    if (strpos($available_sets, 's') !== false) {
        $sets[] = '567890';
    }

    $all = '';
    $password = '';
    foreach ($sets as $set) {
        $password .= $set[array_rand(str_split($set))];
        $all .= $set;
    }
    $all = str_split($all);
    for ($i = 0; $i < $length - count($sets); $i++) {
        $password .= $all[array_rand($all)];
    }

    $password = str_shuffle($password);
    if (!$add_dashes) {
        return $password;
    }

    $dash_len = floor(sqrt($length));
    $dash_str = '';
    while (strlen($password) > $dash_len) {
        $dash_str .= substr($password, 0, $dash_len) . '-';
        $password = substr($password, $dash_len);
    }
    $dash_str .= $password;
    return $dash_str;
}

/* Creates the order emails and sends them to either
the shop owner, or the customer
------------------------------------------------------- */
function hdc_order_emails($order_id, $email_type)
{
    require dirname(__FILE__) . '/emails/hdc_email.php';
}

/* Saves a successful order as a Orders custom post type
------------------------------------------------------- */
function hdc_create_order($hdc_form)
{
    require dirname(__FILE__) . '/create-order.php';
}

/* Creates Orders Export
------------------------------------------------------- */
function hdc_export_orders()
{
    // TODO: Inlucde custom fields
    // create transient to delete order file after 1 hour
    // santize data for super duper extra safty

    function hdc_sanitize_array($data)
    {
        return sanitize_text_field($data);
    }

    function hdc_export_all_orders()
    {
        // loop through all orders and generate CSV file
        $data = array();

        // WP_Query arguments
        $args = array(
            'post_type' => array('hdc_orders'),
            'nopaging' => true,
            'posts_per_page' => '-1',
        );

        // The Query
        $query = new WP_Query($args);

        $row = array(
            "Order #",
            "Transaction ID",
            "Total",
            "Subtotal",
            "Total Tax Value",
            "Taxes",
            "Coupons",
            "First Name",
            "Last Name",
            "Email Address",
            "Phone #",
            "Country",
            "Sate/Province",
            "Address",
            "Address 2",
            "City",
            "ZIP/Postal Code",
            "Shipping Type",
            "Shipping Cost",
            "Customer Note",
            "Products",
        );
        array_push($data, $row);
        // The Loop
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $postID = get_the_ID();
                $row = array();

                $hdc_order_title = esc_attr(get_post_meta($postID, 'hdc_order_title', true));
                $hdc_order_title = explode("|", $hdc_order_title);
                $hdc_order_title = $hdc_order_title[0];
                array_push($row, $hdc_order_title);
                array_push($row, get_the_title());

                $hdc_payment_amount = esc_attr(get_post_meta($postID, 'hdc_payment_amount', true));
                $hdc_payment_amount = number_format((float) $hdc_payment_amount, 2, '.', '');
                array_push($row, $hdc_payment_amount);

                $hdc_checkout_tax_amount = esc_attr(get_post_meta($postID, 'hdc_checkout_tax_amount', true));
                $hdc_subtotal = floatval($hdc_payment_amount) - floatval($hdc_checkout_tax_amount);
                array_push($row, $hdc_subtotal);
                array_push($row, $hdc_checkout_tax_amount);

                $hdc_checkout_tax = esc_attr(get_post_meta($postID, 'hdc_checkout_tax', true));
                $hdc_checkout_tax = json_decode(html_entity_decode($hdc_checkout_tax), true);
                $hdc_checkout_tax_s = "";
                foreach ($hdc_checkout_tax as $tax) {
                    $hdc_checkout_tax_s .= $tax["name"] . ": " . $tax["value"] . PHP_EOL;
                }
                array_push($row, $hdc_checkout_tax_s);

                $hdc_coupons = esc_attr(get_post_meta($postID, 'hdc_coupons', true));
                $hdc_coupons = json_decode(html_entity_decode($hdc_coupons), true);

                $hdc_coupons_s = "";
                foreach ($hdc_coupons as $coupon) {
                    $hdc_coupons_s .= $coupon["code"] . ": " . $coupon["type"] . ' - ' . $coupon["amount"] . PHP_EOL;
                }
                array_push($row, $hdc_coupons_s);

                $hdc_checkout_first_name = esc_attr(get_post_meta($postID, 'hdc_checkout_first_name', true));
                $hdc_checkout_last_name = esc_attr(get_post_meta($postID, 'hdc_checkout_last_name', true));
                $hdc_checkout_email = esc_attr(get_post_meta($postID, 'hdc_checkout_email', true));
                $hdc_checkout_phone = esc_attr(get_post_meta($postID, 'hdc_checkout_phone', true));
                $hdc_checkout_country = esc_attr(get_post_meta($postID, 'hdc_checkout_country', true));
                $hdc_checkout_state = esc_attr(get_post_meta($postID, 'hdc_checkout_state', true));
                $hdc_checkout_address = esc_attr(get_post_meta($postID, 'hdc_checkout_address', true));
                $hdc_checkout_address2 = esc_attr(get_post_meta($postID, 'hdc_checkout_address2', true));
                $hdc_checkout_city = esc_attr(get_post_meta($postID, 'hdc_checkout_city', true));
                $hdc_checkout_zip = esc_attr(get_post_meta($postID, 'hdc_checkout_zip', true));
                $hdc_shipping_method = esc_attr(get_post_meta($postID, 'hdc_shipping_method', true));
                $hdc_shipping_method_name = esc_attr(get_post_meta($postID, 'hdc_shipping_method_name', true));
                $hdc_checkout_note = esc_attr(get_post_meta($postID, 'hdc_checkout_note', true));

                array_push($row, $hdc_checkout_first_name);
                array_push($row, $hdc_checkout_last_name);
                array_push($row, $hdc_checkout_email);
                array_push($row, $hdc_checkout_phone);
                array_push($row, $hdc_checkout_country);
                array_push($row, $hdc_checkout_state);
                array_push($row, $hdc_checkout_address);
                array_push($row, $hdc_checkout_address2);
                array_push($row, $hdc_checkout_city);
                array_push($row, $hdc_checkout_zip);
                array_push($row, $hdc_shipping_method_name);
                array_push($row, $hdc_shipping_method);
                array_push($row, $hdc_checkout_note);

                $hdc_checkout_products = esc_attr(get_post_meta($postID, 'hdc_checkout_products', true));
                $hdc_checkout_products_arr = json_decode(html_entity_decode($hdc_checkout_products), true);

                $hdc_checkout_products_s = "";
                foreach ($hdc_checkout_products_arr as $product) {
                    $hdc_checkout_products_s .= $product["name"] . ' - ' . $product["variation"] . ' - PRICE: ' . $product["price"] . ' QUANTITY ' . $product["quantity"] . PHP_EOL;
                }
                array_push($row, $hdc_checkout_products_s);
                array_push($data, $row);
            }

            $fp = fopen(dirname(__FILE__) . "/exports/order_export.csv", "w") or die("Unable to open file!");
            foreach ($data as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);

            echo '<p><a href = "' . plugin_dir_url(__FILE__) . '/exports/order_export.csv">DOWNLOAD EXPORT</a></p>';

        } else {
            echo '<p>You have no orders to export</p>';
        }

        // Restore original Post Data
        wp_reset_postdata();
    }

    $hdc_cart_nonce = sanitize_text_field($_POST['nonce']);

    if (wp_verify_nonce($hdc_cart_nonce, 'hdc_export_orders') != false) {
        $export_type = sanitize_text_field($_POST['export_type']);
        $date_range = array_map('hdc_sanitize_array', $_POST["date_rang"]);

        if ($export_type === "hdc_export_all_orders") {
            hdc_export_all_orders();
        } else {
            echo "hmmm";
        }
    } else {
        echo "<p>Permission not granted</p>";
    }
    die();
}
add_action('wp_ajax_hdc_export_orders', 'hdc_export_orders');

/* Creates an order via ajax for non self-hosted gateways
   such as built-in PayPal Gateway
------------------------------------------------------- */
function hdc_submit_order()
{

    $hdc_cart_id = sanitize_text_field($_POST['hdc_cart_id']);
    $hdc_cart_nonce = sanitize_text_field($_POST['hdc_cart_nonce']);

    if (wp_verify_nonce($hdc_cart_nonce, 'hdc_cart_nonce' . $hdc_cart_id) != false) {
        // TODO: Should pass form data to hdc_get_cart_post_data()
        // instead of doing it again here
        global $hdc_form;
        $hdc_form = new \stdClass();

        $hdc_store_currency = get_option("hdc_store_currency");
        $hdc_store_currency = explode("|", $hdc_store_currency);

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
        $hdc_form->hdc_shipping_method = floatval($_POST['hdc_shipping_method']);
        $hdc_form->hdc_shipping_method_name = sanitize_text_field($_POST['hdc_shipping_method_name']);
        $hdc_form->hdc_checkout_tax = sanitize_text_field($_POST['hdc_checkout_tax']);
        $hdc_form->hdc_checkout_tax_amount = floatval($_POST['hdc_checkout_tax_amount']);
        $hdc_form->hdc_checkout_note = wp_kses_post($_POST['hdc_checkout_note']);
        $hdc_form->hdc_checkout_products = sanitize_text_field($_POST['hdc_checkout_products']);
        $hdc_form->hdc_coupon_codes = sanitize_text_field($_POST['hdc_coupon_codes']);
        $hdc_form->hdc_payment_amount = floatval($_POST['hdc_payment_amount']);

        hdc_get_extra_form_fields(); // get extra fields submitted to the checkout

        if ($hdc_form->hdc_checkout_email != "" && $hdc_form->hdc_checkout_email != null && $hdc_form->hdc_payment_amount != "" && $hdc_form->hdc_payment_amount != null) {
            $hdc_form->hdc_cart_id = $hdc_cart_id;
            $hdc_form->hdc_cart_nonce = $hdc_cart_nonce;
            $hdc_form->hdc_order_status = "payment pending";
            // create the order
            hdc_create_order($hdc_form);
            global $order_id;
            echo $order_id;
        } else {
            echo 'complete';
        }
    }
    die();
}
add_action('wp_ajax_nopriv_hdc_submit_order', 'hdc_submit_order');
add_action('wp_ajax_hdc_submit_order', 'hdc_submit_order');

/* Order Payment Confirmed
------------------------------------------------------- */
function hdc_order_payment_confirmed($order_ID, $amount)
{
    $order_ID = intval($order_ID);
    $amount = floatval($amount);

	// todo: rename this to something more accurate
    do_action("hdc_before_payment_confirmed", $order_ID);

    // update order stats
    hdc_update_order_stats($amount);

    // send emails
    hdc_order_emails($order_ID, "store"); // send to store owner
    hdc_order_emails($order_ID, "customer"); // send to customer

    do_action("hdc_after_payment_confirmed", $order_ID);

}

/* Reduce Product Stock on order completion
------------------------------------------------------- */
function hdc_order_payment_update_stock($order)
{
    $products = esc_attr(get_post_meta($order, 'hdc_checkout_products', true));
    $products = json_decode(html_entity_decode($products), true);
    for ($i = 0; $i < count($products); $i++) {
        $p = hdc_get_cart_product(array($products[$i]["id"], $products[$i]["variation"], $products[$i]["quantity"]));
        $stock = intval($p->stock);
        $quantity = intval($products[$i]["quantity"]);
        $fields = get_product_data($products[$i]["id"]);
        if ($stock > 0) {
            $stock = $stock - $quantity;
            if (isset($p->variation) && $p->variation != "") {
                // update variation
                $permutations = $fields["product_permutations"]["value"];
                for ($x = 0; $x < count($permutations); $x++) {
                    if ($permutations[$x]["id"] == $p->variation) {
                        if ($permutations[$x]["options"]["stock"] != "" && $permutations[$x]["options"]["stock"] != null) {
                            $permutations[$x]["options"]["stock"] = $stock;
							$fields["product_permutations"]["value"] = $permutations;
                        }
                        break;
                    }
                }
            } elseif ($fields["product_stock"]["value"] != "" && $fields["product_stock"]["value"] != null) {
                // update product stock
                $fields["product_stock"]["value"] = $stock;
            }
			$fields = hdc_sanitize_data_save($fields);
            update_post_meta($products[$i]["id"], 'hdc_product_data', json_encode($fields), false);
        }
    }
}
add_action("hdc_before_payment_confirmed", "hdc_order_payment_update_stock", 10, 1);

/* Updates order stats option
------------------------------------------------------- */
function hdc_update_order_stats($amount)
{
    $amount = number_format(floatval($amount), 2);
    $date = date("m-Y");
    $hdc_order_stats = sanitize_text_field(get_option("hdc_order_stats"));
    if ($hdc_order_stats != "" && $hdc_order_stats != null) {
        // read JSON
        $hdc_order_stats = stripslashes($hdc_order_stats);
        $hdc_order_stats = json_decode(html_entity_decode($hdc_order_stats), true);
        // add this order to exising month, or push new array for month
        $counter = 0;
        $counter2 = -1;

        foreach ($hdc_order_stats as $value) {
            if ((string) $date == (string) $value[0]) {
                $counter2 = $counter;
            }
            $counter = $counter + 1;
        }

        if ($counter2 >= 0) {
            // there is an existing month
            $amountO = $hdc_order_stats[$counter2][1];
            $amount = number_format(floatval($amountO) + floatval($amount), 2);
            $hdc_order_stats[$counter2][1] = $amount;
            $orders = $hdc_order_stats[$counter2][2];
            $hdc_order_stats[$counter2][2] = $orders + 1;
        } else {
            // push the new month array
            array_push($hdc_order_stats, array($date, $amount, 1));
        }
        $hdc_order_stats = json_encode($hdc_order_stats);
        update_option("hdc_order_stats", $hdc_order_stats);
    } else {
        // store the date (m-Y) and the amount of the order
        $hdc_order_stats = array(array($date, $amount, 1));
        $hdc_order_stats = json_encode($hdc_order_stats);
        update_option("hdc_order_stats", $hdc_order_stats);
    }
}

/* HDCommerce unit converter. Converts dimensions and weight
 * kg, lbs. cm, inch
------------------------------------------------------- */
function hdc_convert_unit($value, $unit)
{
    $v = 0;

    // convert to float in case it was passed as string
    $value = floatval($value);

    // = Weight =
    // convert weight from pounds to kilograms
    if ($unit == "kg") {
        $v = $value * 0.453592;
    }
    // convert weight from kilograms to pounds
    if ($unit == "lbs") {
        $v = $value * 2.20462;
    }

    // = Measurements =
    // convert inches to centimeters
    if ($unit == "cm") {
        $v = $value * 2.54;
    }
    // convert centimeters to inches
    if ($unit == "inch") {
        $v = $value * 0.393701;
    }
    return number_format($v, 2);
}

/* Creates a new review for a product
------------------------------------------------------- */
function hdc_submit_review()
{

    $hdc_id = sanitize_text_field($_POST['hdc_id']);
    $hdc_cart_nonce = sanitize_text_field($_POST['hdc_review_nonce']);

    if (wp_verify_nonce($hdc_cart_nonce, 'hdc_product_review_' . $hdc_id . '_nonce') != false) {
        $hdc_name = sanitize_text_field($_POST['hdc_name']);
        $hdc_email = sanitize_email($_POST['hdc_email']);
        $hdc_review = sanitize_textarea_field($_POST['hdc_review']);
        $hdc_rating = intval($_POST['hdc_rating']);
        // submit the review
        $commentdata = array(
            'comment_post_ID' => $hdc_id,
            'comment_author' => $hdc_name,
            'comment_author_email' => $hdc_name,
            'comment_content' => $hdc_review,
            'comment_type' => '',
            'comment_parent' => 0,
        );
        $comment_id = wp_new_comment($commentdata);
        add_comment_meta($comment_id, 'hdc_rating', $hdc_rating);

    }
    die();
}
add_action('wp_ajax_nopriv_hdc_submit_review', 'hdc_submit_review');
add_action('wp_ajax_hdc_submit_review', 'hdc_submit_review');

/* Check if coupon code exists and as active
------------------------------------------------------- */
function hdc_check_coupon()
{

    // returns true if the coupon is invalid
    function hdc_check_coupon_restriction($coupon, $cart_total)
    {

        function hdc_check_coupon_restrict_date($expire_date)
        {
            if ($expire_date == "" || $expire_date == null) {
                return true;
            }
            if (new DateTime() > new DateTime($expire_date . " 16:00:00")) {
                return false;
            }
            return true;
        }
		
		function hdc_check_coupon_restrict_minimum_amount($minimum_amount, $cart_total)
		{
			if($minimum_amount == "" || $minimum_amount == 0){
				return true;
			}
			
			if($cart_total < $minimum_amount){
				return false;
			}			
			return true;
		}

        function hdc_check_coupon_restrict_categories($restrict_categories)
        {
            if (!isset($restrict_categories) || $restrict_categories == "") {
                return true;
            }

            $restrict_categories = explode(",", $restrict_categories);

            $hdc_cart_products = array();
            $hdc_checkout_page = intval(get_option('hdc_checkout_page'));
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

            $invalid = false;

            foreach ($restrict_categories as $coupon) {
                // make sure a product in cart matches a coupon category
                if (term_exists(intval($coupon), "hdc_products")) {
                    foreach ($hdc_cart_products as $prod) {
                        // get list of attached categories
                        $terms = get_the_terms($prod[0], 'hdc_products');
                        foreach ($terms as $term) {
                            if ($term->term_id == $coupon) {
                                $invalid = true;
                            }
                        }
                    }
                }
            }
            return $invalid;
        }

        function hdc_check_coupon_restrict_products($restrict_products)
        {
            if (!isset($restrict_products) || $restrict_products == "") {
                return true;
            }

            $restrict_products = explode(",", $restrict_products);
            $hdc_cart_products = array();
            $hdc_checkout_page = intval(get_option('hdc_checkout_page'));
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

            $isvalid = false;
            foreach ($restrict_products as $coupon) {
                // make sure a product is in the cart with id of $cat
                if (get_post_status($coupon) != false) {
                    foreach ($hdc_cart_products as $prod) {
                        if ($prod[0] == $coupon) {
                            $isvalid = true;
                            break;
                        }
                    }
                }
            }
            return $isvalid;
        }

        $expire_date = sanitize_text_field(get_post_meta($coupon, 'hdc_coupon_expire_date', true));
        if (!hdc_check_coupon_restrict_date($expire_date)) {
            return false;
        }
		
        $minimum_amount = sanitize_text_field(get_post_meta($coupon, 'hdc_coupon_minimum_amount', true));
        if (!hdc_check_coupon_restrict_minimum_amount($minimum_amount, $cart_total)) {
            return false;
        }		

        $restrict_categories = sanitize_text_field(get_post_meta($coupon, 'hdc_coupon_restrict_categories', true));
        if (!hdc_check_coupon_restrict_categories($restrict_categories)) {
            return false;
        }

        $restrict_products = sanitize_text_field(get_post_meta($coupon, 'hdc_coupon_restrict_products', true));
        if (!hdc_check_coupon_restrict_products($restrict_products)) {
            return false;
        }

        return true;
    }

    $hdc_nonce = sanitize_text_field($_POST['hdc_nonce']);
    $code = sanitize_text_field($_POST["coupon"]);
    $status = "fail";
	$cart_total = floatval($_POST['cart_amount']);

    if (wp_verify_nonce($hdc_nonce, 'hdc_cart_nonce') != false) {
        // WP_Query arguments
        $args = array(
            'post_type' => array('hdc_coupon'),
            'post_status' => array('publish'),
            'nopaging' => true,
            'posts_per_page' => '-1',
        );

        // The Query
        $query = new WP_Query($args);
        $coupon = new \stdClass();

        // The Loop
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $title = strtolower(get_the_title());
                $codeL = strtolower($code);
                if ($title === $codeL) {

                    // check for restrictions
                    if (hdc_check_coupon_restriction(get_the_ID(), $cart_total)) {
                        $status = "success";
                        $discount_type = sanitize_text_field(get_post_meta(get_the_ID(), 'hdc_coupon_percent_discount', true));
                        $discount_symbol = "%";
                        if ($discount_type === "no") {
                            $discount_type = "amount";
                            $discount_symbol = hdc_get_currency_symbol();
                        } else {
                            $discount_type = "percentage";
                        }
                        $discount_amount = number_format(floatval(get_post_meta(get_the_ID(), 'hdc_coupon_amount', true)), 2);
                        $coupon->type = $discount_type;
                        $coupon->symbol = $discount_symbol;
                        $coupon->amount = $discount_amount;
                    }
                }
            }
        }
        $coupon = json_encode($coupon);
        // Restore original Post Data
        wp_reset_postdata();
        echo '{"success": "' . $status . '", "code": "' . $code . '", "coupon": ' . $coupon . '}';
    } else {
        echo '{"success": "fail", "code": "' . $code . '"}';
    }
    die();
}
add_action('wp_ajax_nopriv_hdc_check_coupon', 'hdc_check_coupon');
add_action('wp_ajax_hdc_check_coupon', 'hdc_check_coupon');