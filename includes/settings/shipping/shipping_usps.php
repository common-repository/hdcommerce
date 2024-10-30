<?php
/*
HDCommerce Settings Shipping Method USPS
Contains:
@hdc_shipping_usps
 */
global $hdc_shipping;
// read in the options
$hdc_shipping_usps_methods = sanitize_text_field(get_option("hdc_shipping_usps_methods"));
$hdc_shipping_methods = explode(",", $hdc_shipping_usps_methods);
$hdc_shipping_usps_user = sanitize_text_field(get_option("hdc_shipping_usps_user"));
$hdc_shipping_usps_international = sanitize_text_field(get_option("hdc_shipping_usps_international"));

// pretty name, method return slug
$hdc_methods = array(
    array("First-Class Mail Large Envelope", "First-Class Mail Large Envelope"),
    array("First-Class Mail Large Postcards", "First-Class Mail Large Postcards"),
    array("First-Class Mail Metered Letter", "First-Class Mail Metered Letter"),
    array("First-Class Mail Postcards", "First-Class Mail Postcards"),
    array("First-Class Mail Stamped Letter", "First-Class Mail Stamped Letter"),
    array("First-Class Package Service - Retail", "First-Class Package Service - Retail"),
    array("Library Mail Parcel", "Library Mail Parcel"),
    array("Media Mail Parcel", "Media Mail Parcel"),
    array("Priority Mail 2-Day", "Priority Mail 2-Day"),
    array("Priority Mail 2-Day Flat Rate Envelope", "Priority Mail 2-Day Flat Rate Envelope"),
    array("Priority Mail 2-Day Gift Card Flat Rate Envelope", "Priority Mail 2-Day Gift Card Flat Rate Envelope"),
    array("Priority Mail 2-Day Large Flat Rate Box", "Priority Mail 2-Day Large Flat Rate Box"),
    array("Priority Mail 2-Day Legal Flat Rate Envelope", "Priority Mail 2-Day Legal Flat Rate Envelope"),
    array("Priority Mail 2-Day Medium Flat Rate Box", "Priority Mail 2-Day Medium Flat Rate Box"),
    array("Priority Mail 2-Day Padded Flat Rate Envelope", "Priority Mail 2-Day Padded Flat Rate Envelope"),
    array("Priority Mail 2-Day Small Flat Rate Box", "Priority Mail 2-Day Small Flat Rate Box"),
    array("Priority Mail 2-Day Small Flat Rate Envelope", "Priority Mail 2-Day Small Flat Rate Envelope"),
    array("Priority Mail 2-Day Window Flat Rate Envelope", "Priority Mail 2-Day Window Flat Rate Envelope"),
    array("Priority Mail Express 1-Day", "Priority Mail Express 1-Day"),
    array("Priority Mail Express 1-Day Flat Rate Envelope", "Priority Mail Express 1-Day Flat Rate Envelope"),
    array("Priority Mail Express 1-Day Flat Rate Envelope Hold For Pickup", "Priority Mail Express 1-Day Flat Rate Envelope Hold For Pickup"),
    array("Priority Mail Express 1-Day Hold For Pickup", "Priority Mail Express 1-Day Hold For Pickup"),
    array("Priority Mail Express 1-Day Legal Flat Rate Envelope", "Priority Mail Express 1-Day Legal Flat Rate Envelope"),
    array("Priority Mail Express 1-Day Legal Flat Rate Envelope Hold For Pickup", "Priority Mail Express 1-Day Legal Flat Rate Envelope Hold For Pickup"),
    array("Priority Mail Express 1-Day Padded Flat Rate Envelope", "Priority Mail Express 1-Day Padded Flat Rate Envelope"),
    array("Priority Mail Express 1-Day Padded Flat Rate Envelope Hold For Pickup", "Priority Mail Express 1-Day Padded Flat Rate Envelope Hold For Pickup"),
    array("USPS Retail Ground", "USPS Retail Ground"),
);

?>

<input type = "hidden" id = "hdc_shipping_usps_methods" name = "hdc_shipping_usps_methods" value = "<?php echo $hdc_shipping_usps_methods; ?>"/>

<!-- USPS -->
<h3>USPS Shipping</h3>
<p>The United States Postal Service is an independent agency of the United States federal government responsible for providing postal service in the United States, including its insular areas and associated states.</p>

<div class = "hdc_accordion">
	<h3>Instructions</h3>
	<div>
		<p>
			In order to use USPS Shipping, you will need to sign up for their API. This can be done by going to their <a href= "https://www.usps.com/business/web-tools-apis/welcome.htm" target = "_blank">registration page</a> and selecting <strong>Register Now</strong>. Once you've filled out their form, an email will be sent to you with your username and password. If you cannot sign up, then you can use <code>163HARMO4206</code> as your username, but please note that it is always recommended that you use your own API credidentials.
		</p>
	</div>
</div>

<div class = "hdc_setting_row">
		<div class = "one_half">
			<div class="hdc_input-effect">
				<input class="hdc_input <?php if ($hdc_shipping_usps_user != null && $hdc_shipping_usps_user != "") {echo 'has-content';}?>" id="hdc_shipping_usps_user" name="hdc_shipping_usps_user" type="text" value="<?php echo $hdc_shipping_usps_user; ?>">
				<label for="hdc_shipping_usps_user">Merchant User Name</label>
				<span class="focus-border"></span>
			</div>
		</div>
		<div class = "one_half last">
			<div class="hdc_input-effect">
				<input class="hdc_input <?php if ($hdc_shipping_usps_international != null && $hdc_shipping_usps_international != "") {echo 'has-content';}?>" id="hdc_shipping_usps_international" name="hdc_shipping_usps_international" type="text" value="<?php echo $hdc_shipping_usps_international; ?>">
				<label for="hdc_shipping_usps_international">International Price <a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>This price will be used for all international orders. This price is per product.</span></span></a></label>
				<span class="focus-border"></span>
			</div>
		</div>
		<div class = "clear"></div>
</div>

<div class = "hdc_setting_row">
	<p><strong>Please toggle all of the shipping options you wish to offer your customers</strong>
		<br/>
		<br/>NOTE: Currently only works for domestic orders. You will need to set a flat rate for international orders above if you are shipping outside of the US.
		<br/><br/>
	</p>

	<?php
// slug, methods array, active methods
hdc_get_shipping_methods("usps", $hdc_methods, $hdc_shipping_methods);
?>

</div>