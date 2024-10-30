<?php
/*
	HDCommerce Settings Shipping Tab
	Contains:
		@hdc_selected_gateway,
		@hdc_stripe_public_key,
		@hdc_stripe_secret_key,
		@hdc_paypal_address,
		@hdc_square_app_id,
		@hdc_square_access_token
*/
?>

<div class="hdc_tab" id="hdc_settings_shipping">
	<div class = "hdc_setting_row">
		<br/>

		<div class = "one_third">
			<label class = "non-block" for="hdc_shipping_disable">Disable Shipping</label>
			<a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>Enable this if you will not be charging shipping on your products.</span></span></a>
			<div class="hdc-options-check">
				<input type="checkbox" id="hdc_shipping_disable" value="yes" name="hdc_shipping_disable" <?php if ($hdc_shipping_disable == 1) { echo "checked"; } ?> >
				<label for="hdc_shipping_disable"></label>
			</div>
		</div>
		<div class = "one_third">
			<label class = "non-block" for="hdc_shipping_unit">Use Imperial measurements</label>
			<a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>By default, Metric is used for all calculations.</span></span></a>
			<div class="hdc-options-check">
				<input type="checkbox" id="hdc_shipping_unit" value="yes" name="hdc_shipping_unit" <?php if ($hdc_shipping_unit == 1) { echo "checked"; } ?> >
				<label for="hdc_shipping_unit"></label>
			</div>
		</div>
		<div class = "one_third last">
			<div class="hdc_input-effect">
				<input class="hdc_input <?php if($hdc_shipping_free != null && $hdc_shipping_free != "") {echo 'has-content';} ?>" id="hdc_shipping_free" name="hdc_shipping_free" type="text" value="<?php echo $hdc_shipping_free; ?>">
				<label for="hdc_shipping_free">Free Shipping <a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>Enter the amount required <em>before</em> tax to enable free shipping on the entire cart.</span></span></a></label>
				<span class="focus-border"></span>
			</div>
		</div>
		<div class = "clear"></div>
	</div>

		<hr/>
		<br/>

	<!-- the shipping company selection -->
	<p style = "margin-top:0; padding-top:0">The following shipping methods are used to calculate shipping cost. You do not need to actually ship with your chosen provider. If you do not wish to use an integrated shipping calculator, then select the "Flat Fee" method below.</p>

	<p>Please remember that these are quotes only, and the real price may vary depending on your package size and contents.</p>

	<?php
		hdc_settings_shipping() // grab shipping methods
	?>
</div>