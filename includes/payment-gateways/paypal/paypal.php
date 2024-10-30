<?php
/*
	HDCommerce Payment Gateway: PayPal
	Creates the form for PayPal payment
*/

// get the currency code
$hdc_store_currency = get_option("hdc_store_currency");
$hdc_store_currency = explode("|", $hdc_store_currency);
$hdc_store_currency = $hdc_store_currency[0];
global $hdc_payment_page;
?>

<input type="hidden" name="paypal" value="paypal">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo sanitize_email(get_option('hdc_paypal_address')); ?>">
<input type="hidden" name="item_name" value="<?php echo get_option('hdc_store_name'); ?> Order">
<input type="hidden" id="hdc_order_id" name="item_number" value="0">
<input type="hidden" name="currency_code" value="<?php echo $hdc_store_currency; ?>">
<input type="hidden" id="hdc_paypal_amount" name="amount" value="0.00">
<input type="hidden" name="notify_url" value="<?php echo $hdc_payment_page; ?>">
<input type="hidden" name="return" value="<?php echo $hdc_payment_page; ?>?gateway=paypal">
<input type="hidden" name="cancel_return" value="<?php echo get_home_url(); ?>">
<input type="hidden" name="no_note" value="0">
<button id="hdc_pay" data-total="">
    PAY <span></span>
</button>