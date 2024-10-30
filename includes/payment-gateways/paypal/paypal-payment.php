<?php
/*
	HDCommerce Payment Gateway: Paypal
	accepts IPN to update the order status and send emails
	checks if user was redirected here from paypal and displays thank you message
*/

// check for STEP1
$hdc_payment_status = "";
$hdc_payment_status = isset($_POST['payment_status']);

if ($hdc_payment_status == "Completed" || $hdc_payment_status == "Processed") {
    hdc_step1(true);
} else if ($hdc_payment_status == "Failed" || $hdc_payment_status == "Denied") {
    hdc_step1(false);
}

// check for STEP2
$hdc_redirect = "";
$hdc_redirect = isset($_GET['gateway']);
if ($hdc_redirect == "paypal") {
    hdc_step2();
}

// STEP 1: User has successful or unsuccesfull payment from PayPal and we now have IPN data
function hdc_step1($status)
{
    // need to update the order based on status
    if ($status) {
        $hdc_order_status = "payment completed";
    } else {
        $hdc_order_status = "payment failed";
    }

    $item_number = intval($_POST['item_number']);
    $hdc_transaction_id = sanitize_text_field($_POST['txn_id']);
    $hdc_payment_amount = floatval($_POST['mc_gross']);

    update_post_meta($item_number, 'hdc_order_status', $hdc_order_status);
    add_post_meta($item_number, 'hdc_transaction_id', $hdc_transaction_id, true);

    // send the emails
    if ($status) {
        hdc_order_payment_confirmed($item_number, $hdc_payment_amount);
    }
}

// STEP 2: User was redirected here from PayPal upon successful payment
function hdc_step2()
{
    global $message;
    // TODO: Make this message editable. Perhaps use the payment page content
    $message = "<h2>Thank you for your order.</h2><p>Your order was successfully created. An order confirmation email is being sent to your provided email address. Please allow for 20 minutes to recieve this email and remember to check your spam folder.</p>";
}
