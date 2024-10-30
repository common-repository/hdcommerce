<?php
/*
	HDCommerce Payment Gateway Functions
	Determine payment gateways
*/

/* Settings page: Display Payment Gateways
------------------------------------------------------- */
function hdc_settings_payment_gateway()
{
    global $hdc_payment_gateway;
    global $hdc_payment_gateway_methods;

    // name, function
    $hdc_payment_gateway = explode("|", $hdc_payment_gateway);
    // read in array
    // method slug, path to logo, settings function, cart function
    // NOTE: The slug should use underscore_ instead of hyphen-
	
    $hdc_payment_gateway_methods = array(
        array("stripe", plugin_dir_url(__FILE__) . "/../../images/stripe.jpg", "hdc_settings_include_payment_gateway_stripe", "hdc_payment_gateway_stripe_includes"),
        array("paypal", plugin_dir_url(__FILE__) . "/../../images/paypal.jpg", "hdc_settings_include_payment_gateway_paypal", "hdc_payment_gateway_paypal_includes"),
    );
    do_action('hdc_settings_payment_gateway'); // grab any extra shipping methods from plugins;

    // display gateways

    $counter = 0;
    foreach ($hdc_payment_gateway_methods as $value) {
        $counter = $counter + 1;

        if ($counter != 3) {
            ?>

<div class="one_third">
    <div id="settings_payment_gateway_<?php echo $value[0]; ?>" data-function="<?php echo $value[3]; ?>"
        data-select="<?php echo $value[0]; ?>"
        class="hdc_setting_image_select <?php if ($hdc_payment_gateway[0] == $value[0]) {echo "hdc_selected";}?>"><img
            src="<?php echo $value[1]; ?>" alt="<?php echo $value[0]; ?>" /></div>
</div>
<?php } else {?>
<div class="one_third last">
    <div id="settings_payment_gateway_<?php echo $value[0]; ?>" data-function="<?php echo $value[3]; ?>"
        data-select="<?php echo $value[0]; ?>"
        class="hdc_setting_image_select <?php if ($hdc_payment_gateway[0] == $value[0]) {echo "hdc_selected";}?>"><img
            src="<?php echo $value[1]; ?>" alt="<?php echo $value[0]; ?>" /></div>
</div>
<div class="clear"></div>

<?php
$counter = 0;
        }
    }

    echo '<div class="clear"></div><br/>';

    // Display the shipping method options/settings
    foreach ($hdc_payment_gateway_methods as $value) {
?>

<div id="hdc_<?php echo $value[0]; ?>" class="hdc_gateway <?php if ($hdc_payment_gateway[0] == $value[0]) {echo "hdc_active_select ";}?>">

    <?php
		$value[2]();
        echo '</div>';
    }
}

function hdc_payment_gateway_stripe_includes()
{
    require dirname(__FILE__) . '/../payment-gateways/stripe/stripe.php';
}

function hdc_payment_gateway_stripe_includes_payment($gateway)
{	
    require dirname(__FILE__) . '/../payment-gateways/stripe/stripe-payment.php';
}

function hdc_payment_gateway_paypal_includes()
{
    require dirname(__FILE__) . '/../payment-gateways/paypal/paypal.php';
}

function hdc_payment_gateway_paypal_includes_payment($gateway)
{
    require dirname(__FILE__) . '/../payment-gateways/paypal/paypal-payment.php';
}

function hdc_settings_stripe_read_save()
{
    global $hdc_options;
    $hdc_options->hdc_stripe_public_key = array("hdc_stripe_public_key", "text");
    $hdc_options->hdc_stripe_secret_key = array("hdc_stripe_secret_key", "encode");
}
add_action('hdc_settings_fields', 'hdc_settings_stripe_read_save');

function hdc_settings_include_payment_gateway_stripe()
{
    global $hdc_payment_gateway;
    $hdc_stripe_public_key = get_option("hdc_stripe_public_key");
    $hdc_stripe_secret_key = get_option("hdc_stripe_secret_key");
    if ($hdc_stripe_secret_key != "" && $hdc_stripe_secret_key != null) {
        $hdc_stripe_secret_key = hdc_decode($hdc_stripe_secret_key);
    }
    ?>

    <h3>Stripe Payment Gateway Settings</h3>
    <div class="hdc_setting_row">
        <div class="hdc_input-effect">
            <input
                class="hdc_input <?php if ($hdc_stripe_public_key != null && $hdc_stripe_public_key != " ") {echo 'has-content';}?>"
                id="hdc_stripe_public_key" name="hdc_stripe_public_key" type="text"
                value="<?php echo $hdc_stripe_public_key; ?>">
            <label for="hdc_stripe_public_key">Enter your Stripe Publishable Key</label>
            <span class="focus-border"></span>
        </div>
        <div class="hdc_input-effect">
            <input type="password"
                class="hdc_input <?php if ($hdc_stripe_secret_key != null && $hdc_stripe_secret_key != "") {echo 'has-content';}?>"
                id="hdc_stripe_secret_key" name="hdc_stripe_secret_key" type="text"
                value="<?php echo $hdc_stripe_secret_key; ?>">
            <label for="hdc_stripe_secret_key">Enter your Stripe Secret Key</label>
            <span class="focus-border"></span>
        </div>
    </div>

<?php
}

function hdc_settings_paypal_read_save()
{
    global $hdc_options;
    $hdc_options->hdc_paypal_address = array("hdc_paypal_address", "text");
    $hdc_options->hdc_paypal_sandbox = array("hdc_paypal_sandbox", "checkbox");
}
add_action('hdc_settings_fields', 'hdc_settings_paypal_read_save');

function hdc_settings_include_payment_gateway_paypal()
{
    global $hdc_payment_gateway;
    // read in the options
    $hdc_paypal_address = get_option("hdc_paypal_address");
    $hdc_paypal_sandbox = get_option("hdc_paypal_sandbox");
    ?>

    <h3>PayPal Payment Standard Gateway Settings</h3>
    <div class="hdc_setting_row">
        <div class="hdc_input-effect">
            <input
                class="hdc_input <?php if ($hdc_paypal_address != null && $hdc_paypal_address != " ") {echo 'has-content';}?>"
                id="hdc_paypal_address" name="hdc_paypal_address" type="text"
                value="<?php echo $hdc_paypal_address; ?>">
            <label for="hdc_paypal_address">Enter your PayPal email address</label>
            <span class="focus-border"></span>
        </div>
    </div>
    <div class="hdc_setting_row">
        <label class="non-block" for="hdc_paypal_sandbox">Enable Sandbox/Test Mode</label>
        <a class="hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span
                class="hdc_tooltip_content"><span>Use this mode to test orders using your test/develiper PayPal
                    account.</span></span></a>
        <div class="hdc-options-check">
            <?php
$checked = "";
    if (isset($hdc_paypal_sandbox) && $hdc_paypal_sandbox == true) {
        $checked = "checked";
    }
    ?>
            <input type="checkbox" id="hdc_paypal_sandbox" value="yes" name="hdc_paypal_sandbox"
                <?php echo $checked; ?>>
            <label for="hdc_paypal_sandbox"></label>
        </div>
    </div>
    <?php

}
?>