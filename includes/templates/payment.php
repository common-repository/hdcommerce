<?php
/*
	HDCommerce Payment Template
	Prints the order status
*/

get_hdc_header();

global $message;

$hdc_payment_gateway = get_option('hdc_payment_gateway');
$hdc_gateway = explode("|", $hdc_payment_gateway);
$hdc_gateway = $hdc_gateway[0];

$hdc_payment_gateway = explode("|", $hdc_payment_gateway);
$gateway = $hdc_payment_gateway[1] . '_payment';

if (function_exists($hdc_payment_gateway[1] . '_payment')) {
    $gateway($hdc_gateway);
} else {
    echo '<strong>ERROR:</strong> There is an issue with the payment gateway. Please contact the site admin.';
}

if ($message == "" || $message == null) {
    $message = "Sorry, but there was no completed order.";
}
?>

<div id = "hdc_wrapper">
	<div id = "hdc_product_page">
		<!-- order details go here -->
		<?php echo $message; ?>
    </div>
</div>
<?php get_footer();?>