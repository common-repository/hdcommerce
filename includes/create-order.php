<?php
/*
	HDCommerce Create Order
	Runs after successful payment
*/

global $message;
global $hdc_form;
global $post_id;

// check the passed nonce again
$hdc_cart_id = sanitize_text_field($hdc_form->hdc_cart_id);
$hdc_cart_nonce = sanitize_text_field($hdc_form->hdc_cart_nonce);

if (wp_verify_nonce($hdc_cart_nonce, 'hdc_cart_nonce' . $hdc_cart_id) != false) {

    // retrieve and save the order # to option
    $hdc_order_number = get_option("hdc_order_number");
    if ($hdc_order_number == "" || $hdc_order_number == null) {
        $hdc_order_number = 0;
    }
    $hdc_order_number = intval($hdc_order_number) + 1;
    update_option("hdc_order_number", $hdc_order_number);

    $postTitle = hdc_generate_order_id(12);
    $order_title = "Order #" . $hdc_order_number . "|" . $hdc_form->hdc_checkout_first_name . " " . $hdc_form->hdc_checkout_last_name;

    // save the order - name and editor
    $post_information = array(
        'post_title' => $postTitle,
        'post_content' => '', // post_content is required, so we leave blank
        'post_type' => 'hdc_orders',
        'post_status' => 'publish',
    );
    $post_id = wp_insert_post($post_information);

    // now we save custom meta to the published order
    add_post_meta($post_id, 'hdc_order_status', $hdc_form->hdc_order_status, true);
    add_post_meta($post_id, 'hdc_order_title', $order_title, true);
    add_post_meta($post_id, 'hdc_checkout_first_name', sanitize_text_field($hdc_form->hdc_checkout_first_name), true);
    add_post_meta($post_id, 'hdc_checkout_last_name', sanitize_text_field($hdc_form->hdc_checkout_last_name), true);
    add_post_meta($post_id, 'hdc_checkout_email', sanitize_email($hdc_form->hdc_checkout_email), true);
    add_post_meta($post_id, 'hdc_checkout_phone', sanitize_text_field($hdc_form->hdc_checkout_phone), true);
    add_post_meta($post_id, 'hdc_checkout_country', sanitize_text_field($hdc_form->hdc_checkout_country), true);
    add_post_meta($post_id, 'hdc_checkout_state', sanitize_text_field($hdc_form->hdc_checkout_state), true);
    add_post_meta($post_id, 'hdc_checkout_address', sanitize_text_field($hdc_form->hdc_checkout_address), true);
    add_post_meta($post_id, 'hdc_checkout_address2', sanitize_text_field($hdc_form->hdc_checkout_address2), true);
    add_post_meta($post_id, 'hdc_checkout_city', sanitize_text_field($hdc_form->hdc_checkout_city), true);
    add_post_meta($post_id, 'hdc_checkout_zip', sanitize_text_field($hdc_form->hdc_checkout_zip), true);
    add_post_meta($post_id, 'hdc_shipping_method', floatval($hdc_form->hdc_shipping_method), true);
    add_post_meta($post_id, 'hdc_shipping_method_name', sanitize_text_field($hdc_form->hdc_shipping_method_name), true);
    add_post_meta($post_id, 'hdc_checkout_tax', sanitize_text_field($hdc_form->hdc_checkout_tax), true);
    add_post_meta($post_id, 'hdc_checkout_tax_amount', floatval($hdc_form->hdc_checkout_tax_amount), true);
    add_post_meta($post_id, 'hdc_checkout_note', sanitize_textarea_field($hdc_form->hdc_checkout_note), true);
    add_post_meta($post_id, 'hdc_payment_amount', floatval($hdc_form->hdc_payment_amount), true);
    add_post_meta($post_id, 'hdc_checkout_products', sanitize_text_field($hdc_form->hdc_checkout_products), true);

    add_post_meta($post_id, 'hdc_coupons', sanitize_text_field($hdc_form->hdc_coupon_codes), true);

    hdc_add_fields_to_order(); // save any extra fields

    // send order emails
    // these are seperated in case user wants to disable one of them
    // (I'll need to create an option for it)

    if ($hdc_form->hdc_order_status == "payment completed") {
        // update order stats
        hdc_order_payment_confirmed(
            intval($post_id),
            floatval($hdc_form->hdc_payment_amount)
        );

        // create a message to let the customer know that the order was successful
        // TODO: Make this message editable. Perhaps use the payment page content
        $message = "<h2>Thank you for your order.</h2><p>Your order was successfully created. An order confirmation email is being sent to your provided email address. Please allow for 20 minutes to recieve this email and remember to check your spam folder.</p>";
    } else {
        global $order_id;
        $order_id = $post_id;
        $message = "<h2>Redirecting you to the payment processor to complete your order</h2>";
    }
} else {
    $message = "There was an error validating your cart";
}
