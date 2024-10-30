<?php
/*
	HDCommerce options and settings page
	Creates options page for global settings
	// TODO: Refactor this like with product page
*/

/* Create the option page
------------------------------------------------------- */
function hdc_create_options_page(){

	if( current_user_can('edit_others_pages') ) {

		function hdc_print_scripts(){			
			wp_enqueue_style(
				'hdc_admin_style',
				plugin_dir_url(__FILE__ ) . '/css/hdc_admin_style.css'
			);
			wp_enqueue_script(
				'hdc_admin_script',
				plugins_url('/js/hdc_admin.js' , __FILE__ ),
				array('jquery', 'jquery-ui-draggable' ),
				'1.0',
				true
			);			

			wp_localize_script('hdc_admin_script', 'hdc_add_product_link', get_admin_url(null, "post-new.php?post_type=hdc_product"));
			do_action("hdc_global_enqueue"); // enqueue files that need to be on every page
			do_action("hdc_settings_enqueue"); // enqueue files that only need to be on the settings page
		}
		hdc_print_scripts();		
		
		
		global $hdc_options;
		global $hdc_payment_gateway;
		global $hdc_shipping;
		global $hdc_extra_tabs;
		$hdc_extra_tabs = array();
		// get the last selected tab so that we can reload it after saving
		$hdc_last_tab = "hdc_settings_main";
		if(isset($_POST["hdc_last_tab"])){
			$hdc_last_tab = sanitize_text_field($_POST["hdc_last_tab"]);
		};

		$hdc_options = new \stdClass();
		// field name, fieldType
	    $hdc_options->hdc_store_name = array("hdc_store_name", "text");
	    $hdc_options->hdc_store_currency = array("hdc_store_currency", "text");
		$hdc_options->hdc_store_selling_countries = array("hdc_store_selling_countries", "text");
		$hdc_options->hdc_featured_image_w = array("hdc_featured_image_w", "integer");
		$hdc_options->hdc_featured_image_h = array("hdc_featured_image_h", "integer");
		$hdc_options->hdc_payment_gateway = array("hdc_payment_gateway", "text");
		$hdc_options->hdc_tax_inclusive = array("hdc_tax_inclusive", "checkbox");
		$hdc_options->hdc_tax_billing = array("hdc_tax_billing", "checkbox");
		$hdc_options->hdc_tax_chart = array("hdc_tax_chart", "text");
		$hdc_options->hdc_shipping = array("hdc_shipping", "text");
		$hdc_options->hdc_shipping_disable = array("hdc_shipping_disable", "checkbox");
		$hdc_options->hdc_shipping_free = array("hdc_shipping_free", "float");
		$hdc_options->hdc_shipping_unit = array("hdc_shipping_unit", "checkbox");
		$hdc_options->hdc_store_address = array("hdc_store_address", "text");
		$hdc_options->hdc_store_address2 = array("hdc_store_address2", "text");
		$hdc_options->hdc_store_city = array("hdc_store_city", "text");
		$hdc_options->hdc_store_state = array("hdc_store_state", "text");
		$hdc_options->hdc_store_country = array("hdc_store_country", "text");
		$hdc_options->hdc_store_zip = array("hdc_store_zip", "text");
		$hdc_options->hdc_store_phone = array("hdc_store_phone", "text");
		$hdc_options->hdc_store_email = array("hdc_store_email", "email");
		$hdc_options->hdc_product_headings = array("hdc_product_headings", "text");
		$hdc_options->hdc_i_love_hdcommerce = array("hdc_i_love_hdcommerce", "checkbox");
		$hdc_options->hdc_product_view_type = array("hdc_product_view_type", "text");
		$hdc_options->hdc_disable_reviews = array("hdc_disable_reviews", "checkbox");
		do_action("hdc_settings_fields"); // grab any extra fields

		// load each option value as $option_name
		foreach($hdc_options as $value) {
			${"$value[0]"} = get_option("$value[0]");
		}

		$hidden_field_name = 'hdc_submit_hidden';

	    // See if the user has submitted the form
	    // If they did, this hidden field will be set to 'Y'
	    if(isset($_POST[ $hidden_field_name ]) && $_POST[$hidden_field_name] == 'Y' ) {
	    	$hdc_nonce = sanitize_text_field($_POST['hdc_settings_nonce']);
			if(wp_verify_nonce( $hdc_nonce, 'hdc_settings_nonce') != false){
		        // Save the posted value in the database
				foreach($hdc_options as $value) {
					if ($value[1] == "checkbox"){
						${"$value[0]"} = isset($_POST[$value[0]]);
					} else if ($value[1] == "text"){
						${"$value[0]"} =  sanitize_text_field($_POST[$value[0]]);
					} else if ($value[1] == "textarea"){
						${"$value[0]"} =  wp_kses_post($_POST[$value[0]]);
					} else if ($value[1] == "email"){
						${"$value[0]"} =  sanitize_email($_POST[$value[0]]);
					} else if ($value[1] == "integer"){
						${"$value[0]"} =  intval($_POST[$value[0]]);
						if (${"$value[0]"} == 0){
							${"$value[0]"} = "";
						}
					} else if ($value[1] == "float"){
						${"$value[0]"} =  floatval($_POST[$value[0]]);
						if (${"$value[0]"} == 0){
							${"$value[0]"} = "";
						}
					} else if ($value[1] == "encode"){
						// TODO: Abstract this out
						$k = wp_salt();
						$text = wp_kses_post($_POST[$value[0]]);
						$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
						$iv = openssl_random_pseudo_bytes($ivlen);
						$ciphertext_raw = openssl_encrypt($text, "AES-128-CBC", $k , $options=OPENSSL_RAW_DATA, $iv);
						$hmac = hash_hmac('sha256', $ciphertext_raw,$k, $as_binary=true);
						$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );						
						${"$value[0]"} = $ciphertext;						
					}
					update_option("$value[0]", ${"$value[0]"});
				}
				do_action("hdc_settings_saved_after"); // clear the cache and stuff	
	?>

<div class="updated notice notice-success is-dismissible">
    <p><strong>Settings have been saved</strong></p>
    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
</div>

	<?php
	    	}
	    }
	?>

<form id="hdc_settings_form" name="form1" method="post" action="">

    <div id="hdc_wrapper">
        <h1>HDCommerce Settings</h1>
        <div id="hdc_settings">

            <!-- Popup notification -->
            <div id="hdc_message_model">
                <div id="hdc_message_model_inner">
                    <div id="hdc_message_model_title">
                        <h4></h4>
                        <div class="button" id="hdc_message_model_close">X</div>
                    </div>
                    <div id="hdc_message_model_content"></div>
                    <div id="hdc_message_model_footer"></div>
                </div>
            </div>

            <div id="hdc_tabs">
                <ul>
                    <li <?php if ($hdc_last_tab == "hdc_settings_main") { echo 'class="hdc_active_tab"'; } ?>
                        data-hdc-content="hdc_settings_main">Main</li>
                    <li <?php if ($hdc_last_tab == "hdc_settings_payment_gateway") { echo 'class="hdc_active_tab"'; } ?>
                        data-hdc-content="hdc_settings_payment_gateway">Payment Gateways</li>
                    <li <?php if ($hdc_last_tab == "hdc_settings_tax") { echo 'class="hdc_active_tab"'; } ?>
                        data-hdc-content="hdc_settings_tax">Tax</li>
                    <li <?php if ($hdc_last_tab == "hdc_settings_shipping") { echo 'class="hdc_active_tab"'; } ?>
                        data-hdc-content="hdc_settings_shipping">Shipping</li>
                    <li <?php if ($hdc_last_tab == "hdc_settings_advanced") { echo 'class="hdc_active_tab"'; } ?>
                        data-hdc-content="hdc_settings_advanced">Advanced</li>
                    <li <?php if ($hdc_last_tab == "hdc_settings_support") { echo 'class="hdc_active_tab"'; } ?>
                        data-hdc-content="hdc_settings_support">Support</li>
                    <?php 
						// add additional tabs selections
						do_action("hdc_settings_tab_select");
						if(!empty($hdc_extra_tabs)){
							foreach($hdc_extra_tabs as $tab){ ?>
                   				 <li <?php if ($hdc_last_tab == $tab[1]) { echo 'class="hdc_active_tab"'; } ?>
                        data-hdc-content="<?php echo $tab[1]; ?>"><?php echo $tab[0]; ?></li>
                    <?php }
							}
					?>
                </ul>
                <div class="clear"></div>
                <button id="hdc_settings_save">SAVE</button>
            </div>
            <div id="hdc_settings_content">
                <?php wp_nonce_field('hdc_settings_nonce', 'hdc_settings_nonce'); ?>
                <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
                <!-- store hidden fields -->
                <input type="hidden" id="hdc_last_tab" name="hdc_last_tab" value="<?php echo $hdc_last_tab; ?>" />
                <input type="hidden" id="hdc_store_selling_countries" name="hdc_store_selling_countries"
                    value="<?php echo $hdc_store_selling_countries; ?>" />
                <input type="hidden" id="hdc_payment_gateway" name="hdc_payment_gateway"
                    value="<?php echo $hdc_payment_gateway; ?>" />
                <input type="hidden" id="hdc_tax_chart" name="hdc_tax_chart" value="" />
                <input type="hidden" id="hdc_shipping" name="hdc_shipping" value="<?php echo $hdc_shipping; ?>" />

                <div id="hdc_tab_content">
                    <?php
								require(dirname(__FILE__).'/settings/settings_main.php');
								require(dirname(__FILE__).'/settings/settings_payment_gateway.php');
								require(dirname(__FILE__).'/settings/settings_tax.php');
								require(dirname(__FILE__).'/settings/settings_shipping.php');
								require(dirname(__FILE__).'/settings/settings_advanced.php');
								require(dirname(__FILE__).'/settings/settings_support.php');
								// add additional tabs
								if(!empty($hdc_extra_tabs)){
									foreach($hdc_extra_tabs as $tab){
										$function = $tab[2];
										if($function != "" && $function != null){
											echo '<div class = "hdc_tab" id = "'.$tab[1].'">';
											$function();
											echo '</div>';
										}
									}
								}
							?>
                </div>

            </div>
        </div>
    </div>
</form>

<?php
	}
}
?>