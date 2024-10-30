<?php
/*
	HDCommerce Product Cart/Checkout page
	Prints the cart and checkout fields
*/

get_hdc_header();
global $hdc_payment_page;

global $hdc_shipping_disable;
global $hdc_shipping_free;
$shipping_customer = "Shipping";
if($hdc_shipping_disable == 1){
	$shipping_customer = "Customer";
}
?>

<div id="hdc_wrapper">
    <div id="hdc_product_page">
        <h2><?php the_title(); ?></h2>


        <?php
		if($hdc_shipping_free != "" && $hdc_shipping_free != null && $hdc_shipping_free > 0 && $hdc_shipping_disable != 1){ ?>
        <div class="hdc_notification hdc_notification_center">
            <h3>FREE SHIPPING ON ALL ORDERS OVER <?php echo  hdc_amount($hdc_shipping_free); ?></h3>
        </div>
        <?php }
		?>


        <?php get_hdc_cart(); ?>

        <div id="hdc_checkout">
            <form name="hdc_checkout_form" id="hdc_checkout_form" action="<?php echo $hdc_payment_form; ?>"
                method="POST">
                <?php
					wp_nonce_field('hdc_cart_nonce'.session_id(), 'hdc_cart_nonce');
				?>
                <input type="hidden" name="hdc_cart_id" id="hdc_cart_id" value="<?php echo session_id(); ?>" />
                <input type="hidden" name="hdc_shipping_method_name" id="hdc_shipping_method_name" value="" />
                <input type="hidden" name="hdc_checkout_tax" id="hdc_checkout_tax" value="" />
                <input type="hidden" name="hdc_checkout_tax_amount" id="hdc_checkout_tax_amount" value="" />
                <input type="hidden" name="hdc_checkout_products" id="hdc_checkout_products" value="" />
                <input type="hidden" name="hdc_payment_amount" id="hdc_payment_amount"
                    value="<?php echo $total_cost; ?>" />
                <input type="hidden" name="hdc_coupon_codes" id="hdc_coupon_codes" value="" />

                <div id="hdc_contact_shipping">
                    <h2><?php echo $shipping_customer; ?> Information</h2>
                    <h3>Contact Information</h3>
                    <div class="hdc_checkout_row">
                        <div class="one_half">
                            <label for="hdc_checkout_first_name"><span class="required_span">*</span>First Name</label>
                            <input id="hdc_checkout_first_name" class="required" name="hdc_checkout_first_name"
                                type="text" placeholder="first name" required />
                        </div>
                        <div class="one_half last">
                            <label for="hdc_checkout_last_name"><span class="required_span">*</span>Last Name</label>
                            <input id="hdc_checkout_last_name" class="required" name="hdc_checkout_last_name"
                                type="text" placeholder="last name" required />
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="hdc_checkout_row">
                        <div class="one_half">
                            <label for="hdc_checkout_email"><span class="required_span">*</span>Email Address</label>
                            <input id="hdc_checkout_email" class="hdc_email_checkout required" name="hdc_checkout_email"
                                type="email" placeholder="email address" required />
                        </div>
                        <div class="one_half last">
                            <label for="hdc_checkout_phone">Phone Number</label>
                            <input id="hdc_checkout_phone" name="hdc_checkout_phone" type="text"
                                placeholder="phone number" />
                        </div>
                        <div class="clear"></div>
                    </div>
                    <h3>Address</h3>
                    <div class="hdc_checkout_row">
                        <div class="one_half">
                            <label for="hdc_checkout_country"><span class="required_span">*</span>Country</label>
                            <select id="hdc_checkout_country" class="required required-shipping"
                                name="hdc_checkout_country" class="hdc_select" required>

                                <?php
									if($hdc_store_selling_countries != "" && $hdc_store_selling_countries != null){
										if(count($hdc_store_selling_countries) > 1){
											echo '<option value="">*Select a Country</option>';
										}
										foreach($hdc_store_selling_countries as $value) {
											echo '<option value = "'.$value["country"].'" data-country-code = "'.$value["code"].'" data-states = "'.$value["states"].'">'.$value["country"].'</div>';
										}
									}
								?>
                            </select>
                        </div>
                        <div class="one_half last">
                            <label for="hdc_checkout_state"><span class="required_span">*</span>State/Province</label>
                            <select id="hdc_checkout_state" class="required required-shipping" name="hdc_checkout_state"
                                class="hdc_select" required>
                                <option value="">*Select State/Provice/County</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="hdc_checkout_row">
                        <div class="one_half">
                            <label for="hdc_checkout_address"><span class="required_span">*</span>Address</label>
                            <input id="hdc_checkout_address" class="required" name="hdc_checkout_address" type="text"
                                placeholder="*address" required />
                        </div>
                        <div class="one_half last">
                            <label for="hdc_checkout_address2">Apt or unit number</label>
                            <input id="hdc_checkout_address2" name="hdc_checkout_address2" type="text"
                                placeholder="apt or unit #" />
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="hdc_checkout_row">
                        <div class="one_half">
                            <label for="hdc_checkout_city"><span class="required_span">*</span>City</label>
                            <input id="hdc_checkout_city" class="required" name="hdc_checkout_city" type="text"
                                placeholder="*City" />
                        </div>
                        <div class="one_half last">
                            <label for="hdc_checkout_zip"><span class="required_span">*</span>ZIP/Postal Code</label>
                            <input id="hdc_checkout_zip" class="required required-shipping" name="hdc_checkout_zip"
                                type="text" placeholder="*zip/postal code"
                                onkeyup="this.value = this.value.toUpperCase()" required />
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="hdc_checkout_row">
                        <label for="hdc_checkout_zip">Shipping Method</label>
                        <?php
							global $hdc_shipping_disable;
							if ($hdc_shipping_disable != 1){ ?>
                        <select id="hdc_shipping_method" name="hdc_shipping_method" required>
                            <option value="">*Select shipping method</option>
                        </select>
                        <?php
							}
						?>
                    </div>

                    <?php
						hdc_checkout_extra_fields(); // grab any extra fields
					?>

                    <div class="hdc_checkout_row">
                        <textarea id="hdc_checkout_note" name="hdc_checkout_note"
                            placeholder="leave an additional note to the seller"></textarea>
                    </div>
                    <div role="button" id="hdc_continue_to_checkout" class="hdc_button">
                        Continue to checkout
                    </div>
                </div>
                <div id="hdc_payment_form">
                    <div id="hdc_shipping_method_wrap">
                    </div>
                    <?php get_hdc_checkout(); ?>
                </div>
            </form>
        </div>
        <div class="clear"></div>
    </div>
</div>

<?php get_footer(); ?>