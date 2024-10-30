<?php
/*
	HDCommerce Settings Shipping Method Canada Post
	Contains:
		@hdc_shipping_canada_post
*/
global $hdc_shipping;
// read in the options
$hdc_shipping_canada_post_methods = sanitize_text_field(get_option("hdc_shipping_canada_post_methods"));
$hdc_shipping_methods = explode("," , $hdc_shipping_canada_post_methods);
$hdc_shipping_canada_post_merchant_user = sanitize_text_field(get_option("hdc_shipping_canada_post_merchant_user"));
$hdc_shipping_canada_post_merchant_password = sanitize_text_field(get_option("hdc_shipping_canada_post_merchant_password"));
$hdc_shipping_canada_post_customer = sanitize_text_field(get_option("hdc_shipping_canada_post_customer"));

$hdc_methods = array(
	array("Regular Parcel", "Regular Parcel"),
	array("Expedited Parcel", "Expedited Parcel"),
	array("Xpresspost", "Xpresspost"),
	array("Priority", "Priority"),
	array("Xpresspost Certified", "Xpresspost Certified"),
	array("Expedited Parcel USA", "Exupedited Parcel USA"),
	array("Tracked Packet USA", "Tracked Packet USA"),
	array("Tracked Packet USA (LVM)", "Tracked Packet USA (LVM)"),
	array("Priority Worldwide Envelope USA", "Priority Worldwide Envelope USA"),
	array("Priority Worldwide pak USA", "Priority Worldwide pak USA"),
	array("Priority Worldwide Parcel USA", "Priority Worldwide Parcel USA"),
	array("Small Packet USA Air", "Small Packet USA Air"),
	array("Small Packet USA Air (LVM)", "Small Packet USA Air (LVM)"),
	array("Tracked Packet USA (LVM)", "Tracked Packet USA (LVM)"),
	array("Small Packet USA Surface", "Small Packet USA Surface"),
	array("Xpresspost USA", "Xpresspost USA"),
	array("Xpresspost International", "Xpresspost International"),
	array("International Tracked Packet", "International Tracked Packet"),
	array("International Parcel Air", "International Parcel Air"),
	array("International Parcel Surface", "International Parcel Surface"),
	array("Priority Worldwide Envelope International", "Priority Worldwide Envelope International"),
	array("Priority Worldwide pak International", "Priority Worldwide pak International"),
	array("Priority Worldwide parcel International", "Priority Worldwide parcel International"),
	array("Small Packet International Air", "Small Packet International Air"),
	array("Small Packet International Surface", "Small Packet International Surface")
);


?>
<input type = "hidden" id = "hdc_shipping_canada_post_methods" name = "hdc_shipping_canada_post_methods" value = "<?php echo $hdc_shipping_canada_post_methods; ?>"/>

<?php
	// convert to array so we can check it against each checkbox
	$hdc_shipping_canada_post_methods = explode("," , $hdc_shipping_canada_post_methods);
?>

<!-- Canada Post -->
	<h3>Canada Post Shipping</h3>
	<p>Canada Post, is a Crown corporation which functions as the primary postal operator in Canada. </p>

<div class = "hdc_accordion">
	<h3>Instructions</h3>
	<div>
		<p>
			In order to use Canada Post Shipping, you will need to sign up for their API. This can be done by going to their <a href= "https://www.canadapost.ca/cpotools/apps/drc/home" target = "_blank">registration page</a> and selecting <strong>Join Now</strong>.</p>
		<p>Once registered, you will need to sign in, and enrole in their developer program. This can be done by going to your <strong>Dashboard</strong>, selecing <strong>Tools</strong> from the top menu, scrolling down to the bottom, and selecting <strong>Web services and APIs</strong> under Developer Program.</p>
		<p>Once done, you will be provided with API keys. Your Customer ID will have been sent to you in your registration email (do not include the preceding zeros).</p>
	</div>
</div>




	<div class = "hdc_setting_row">
		<div id = "hdc_canada_post_methods">
			<div class = "one_third">
				<div class="hdc_input-effect">
					<input class="hdc_input <?php if($hdc_shipping_canada_post_merchant_user != null && $hdc_shipping_canada_post_merchant_user != "") {echo 'has-content';} ?>" id="hdc_shipping_canada_post_merchant_user" name="hdc_shipping_canada_post_merchant_user" type="text" value="<?php echo $hdc_shipping_canada_post_merchant_user; ?>">
					<label for="hdc_shipping_canada_post_merchant_user">Merchant User Name</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "one_third">
				<div class="hdc_input-effect">
					<input class="hdc_input <?php if($hdc_shipping_canada_post_merchant_password != null && $hdc_shipping_canada_post_merchant_password != "") {echo 'has-content';} ?>" id="hdc_shipping_canada_post_merchant_password" name="hdc_shipping_canada_post_merchant_password" type="text" value="<?php echo $hdc_shipping_canada_post_merchant_password; ?>">
					<label for="hdc_shipping_canada_post_merchant_password">Merchant Password</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "one_third last">
				<div class="hdc_input-effect">
					<input class="hdc_input <?php if($hdc_shipping_canada_post_customer != null && $hdc_shipping_canada_post_customer != "") {echo 'has-content';} ?>" id="hdc_shipping_canada_post_customer" name="hdc_shipping_canada_post_customer" type="text" value="<?php echo $hdc_shipping_canada_post_customer; ?>">
					<label for="hdc_shipping_canada_post_customer">Customer Number</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "clear"></div>
		</div>
	</div>

	<div class = "hdc_setting_row">
		<p><strong>Please toggle all of the shipping options you wish to provide for your customers</strong>
			<br/>
			<br/>
		</p>


		<?php
			// slug, methods array, active methods
			hdc_get_shipping_methods("canada_post", $hdc_methods, $hdc_shipping_methods);
		?>
	</div>