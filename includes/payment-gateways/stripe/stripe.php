<?php
/*
	HDCommerce Payment Gateway: Stripe
	Creates the form for stripe payment and enqueues the correct styles and js
*/


wp_enqueue_script(
    'hdc_stripe',
    'https://js.stripe.com/v2/',
    array('jquery'),
    '1.0',
    true
);
wp_localize_script('hdc_script', 'hdc_stripe_publishable', get_option('hdc_stripe_public_key'));
?>


<h2>Payment</h2>
<fieldset>
    <div class="hdc_row">
        <label for="hdc_cart_name">Name</label>
        <input id="hdc_cart_name" placeholder="Jane Doe" data-stripe="name" required="">
        <div class="clear"></div>
    </div>
    <div class="hdc_row">
        <label for="hdc_cart_zip">ZIP</label>
        <input id="hdc_cart_zip" placeholder="zip or postal code" data-stripe="address_zip"
            onkeyup="this.value = this.value.toUpperCase()" required="">
        <div class="clear"></div>
    </div>
</fieldset>
<fieldset>
    <div class="hdc_row">
        <label for="hdc_cart_card">CARD</label>
        <input id="hdc_cart_card" placeholder="please enter your card number" minlength="12" maxlength="20"
            data-stripe="number" required="">
        <div class="clear"></div>
    </div>
    <div class="hdc_row lastrow">
        <div class="one_third">
            <label for="hdc_cart_month">MONTH</label>
            <input id="hdc_cart_month" placeholder="MM" minlength="2" maxlength="2" data-stripe="exp_month" required="">
        </div>
        <div class="one_third">
            <label for="hdc_cart_year">YEAR</label>
            <input id="hdc_cart_year" placeholder="YY" minlength="2" maxlength="2" data-stripe="exp_year" required="">
        </div>
        <div class="one_third last">
            <label for="hdc_cart_cvv">CVV</label>
            <input id="hdc_cart_cvv" placeholder="XXX" minlength="3" maxlength="3" data-stripe="cvc" required="">
        </div>
        <div class="clear"></div>
    </div>
</fieldset>
<button id="hdc_pay" data-total="">
    PAY <span></span>
</button>