<?php
/*
	HDCommerce Payment Gateway: Stripe
	charges the customer's card
*/

$message = "";

$token = isset($_POST['stripeToken']);
if ($token != "" && $token != null) {
    hdc_charge_stripe($message);
} else {
    global $message;
    $message = "Sorry, but there was no order submitted for payment";
}

function hdc_charge_stripe($message)
{

    // get nonce
    $hdc_cart_id = sanitize_text_field($_POST['hdc_cart_id']);
    $hdc_cart_nonce = sanitize_text_field($_POST['hdc_cart_nonce']);

    // check nonce
    if (wp_verify_nonce($hdc_cart_nonce, 'hdc_cart_nonce' . $hdc_cart_id) != false) {

        // get the post data and global options
        $hdc_form = hdc_get_cart_post_data();

        // stripe charges in cents
        $hdc_payment_amount2 = number_format($hdc_form->hdc_payment_amount, 2, '.', '');
        $hdc_payment_amount2 = bcmul($hdc_payment_amount2, 100);

        // get stripe secret key
        $hdc_stripe_secret_key = get_option("hdc_stripe_secret_key");
        if ($hdc_stripe_secret_key != "" && $hdc_stripe_secret_key != null) {
            $hdc_stripe_secret_key = hdc_decode($hdc_stripe_secret_key);
        }
        // get the stripe post token
        $token = $_POST['stripeToken'];

        // include stripe
        require_once dirname(__FILE__) . '/6/init.php';
        \Stripe\Stripe::setApiKey("$hdc_stripe_secret_key");

        $success = true;

        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $hdc_payment_amount2, // Amount in cents
                "currency" => $hdc_form->hdc_store_currency,
                "source" => $token,
                "description" => "Need to generate invoice ID",
            ));
        } catch (\Stripe\Error\Card $e) {
            // The card has been declined
            global $message;
            echo $e;
            $message = "Sorry, but your payment was not accepted. Please go to the cart again and ensure that your payment info is accurate.";
            $success = false;

        }
    } else {
        $message = "Sorry, but there is a validation issue with your cart. Your card has not been processed. Please go to the cart again and ensure that your payment info is accurate.";
        $success = false;
    }

    if ($success) {
        $hdc_form->hdc_order_status = "payment completed";
        $hdc_form->message = $message;
        $hdc_form->message = $message;
        $hdc_form->hdc_cart_id = $hdc_cart_id;
        $hdc_form->hdc_cart_nonce = $hdc_cart_nonce;
        // payment has been made, create the order
        hdc_create_order($hdc_form);
    }
}
