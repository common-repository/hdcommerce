<?php
/*
	HDCommerce Settings Payment Gateway Tab
	Contains:
		@hdc_selected_gateway,
		@hdc_stripe_public_key,
		@hdc_stripe_secret_key,
		@hdc_paypal_address,
		@hdc_square_app_id,
		@hdc_square_access_token
*/
?>

	<div class="hdc_tab" id="hdc_settings_payment_gateway">
		<?php
			// detect if the site is not using SSL and provide gateway warning
			if (empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] = 'off') {
				echo '<h2 style = "margin-top:0; padding-top:0">NOTE: Stripe and Square <strong>require</strong> an SSL certificate installed and in use.</h2>';
			}
		?>
		
		<?php 		
			hdc_settings_payment_gateway();
		?>				
	</div>