<?php
/*
	HDCommerce Order Emails
	Grabs completed order details and sends out
	either a store order email or customer order email
	Contains:
		@order_id // id of the order
		@email-type // either "store" or "customer"
*/

$to = "";
$subject = "";
$hdc_store_name = get_option("hdc_store_name");
$hdc_store_email = get_option("hdc_store_email");

if($email_type == "store"){
	$hdc_subtitle = "NEW ORDER";
	$subject = "New Order";
	$to = get_option("hdc_store_email");
} else {
	$hdc_subtitle = "Thank you for your order";
	$to = esc_attr(get_post_meta($order_id, 'hdc_checkout_email', true ));
	$subject = "Your Order Details";
}
$hdc_order_title = esc_attr(get_post_meta($order_id, 'hdc_order_title', true ));
$hdc_order_title = explode("|", $hdc_order_title);

$hdc_checkout_first_name = esc_attr(get_post_meta($order_id, 'hdc_checkout_first_name', true ));
$hdc_checkout_last_name = esc_attr(get_post_meta($order_id, 'hdc_checkout_last_name', true ));
$hdc_checkout_email = esc_attr(get_post_meta($order_id, 'hdc_checkout_email', true ));
$hdc_checkout_phone = esc_attr(get_post_meta($order_id, 'hdc_checkout_phone', true ));
$hdc_checkout_country = esc_attr(get_post_meta($order_id, 'hdc_checkout_country', true ));
$hdc_checkout_state = esc_attr(get_post_meta($order_id, 'hdc_checkout_state', true ));
$hdc_checkout_address = esc_attr(get_post_meta($order_id, 'hdc_checkout_address', true ));
$hdc_checkout_address2 = esc_attr(get_post_meta($order_id, 'hdc_checkout_address2', true ));
$hdc_checkout_city = esc_attr(get_post_meta($order_id, 'hdc_checkout_city', true ));
$hdc_checkout_zip = esc_attr(get_post_meta($order_id, 'hdc_checkout_zip', true ));
$hdc_shipping_method = esc_attr(get_post_meta($order_id, 'hdc_shipping_method', true ));
$hdc_shipping_method_name = esc_attr(get_post_meta($order_id, 'hdc_shipping_method_name', true ));
$hdc_checkout_tax_amount = esc_attr(get_post_meta($order_id, 'hdc_checkout_tax_amount', true ));
$hdc_checkout_tax = esc_attr(get_post_meta($order_id, 'hdc_checkout_tax', true ));
$hdc_checkout_tax = json_decode(html_entity_decode($hdc_checkout_tax), true);
$hdc_checkout_note = esc_attr(get_post_meta($order_id, 'hdc_checkout_note', true ));
$hdc_payment_amount = esc_attr(get_post_meta($order_id, 'hdc_payment_amount', true )); // is this subtotal or teh total amount that was paid?
$hdc_payment_amount = number_format((float)$hdc_payment_amount, 2, '.', '');

$hdc_checkout_products = esc_attr(get_post_meta($order_id, 'hdc_checkout_products', true ));
$hdc_checkout_products = json_decode(html_entity_decode($hdc_checkout_products), true);

// inline all CSS for maximum compatability with older/shitty email clients
// TODO: build some form of automated CSS inliner 'cause this was super annoying

$message = "";
// email header
$message .= '<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
	<title>HDCommerce</title>
	<style>
	@media only screen and (max-width: 600px) {
		span.id {
			float: none !important;
			display: block !important;
			margin-bottom: 12px !important;
		}
	}
	</style>
</head>

<body style="margin:0; padding:0; background: #efefef">
	<div id="wrapper" style="max-width: 1100px; margin: 0 auto; background: #efefef">
		<div id="content" style="max-width: 1100px; padding: 40px 0; margin: 0 auto; background: #efefef">
			<h1 style="font-weight: 700; padding: 0 40px; margin: 20px 0;">'.$hdc_store_name.'</h1>
			<h2 style="font-weight: 700; padding: 0 40px; margin: 20px 0;">'.$hdc_subtitle.'</h2>
			<h3 style="font-weight: 700; padding: 0 40px; margin: 20px 0;">'.$hdc_order_title[0].' - <span class="name" style = "font-weight: 400;">'.$hdc_order_title[1].'</span> <span class="id" style="float: right;font-weight: 400;">Order ID: '.get_the_title($order_id).'</span></h3>
			<div id="hdc_orders" style="background: #fff;  padding: 20px;">
				<div id="hdc_order_highlight" class="hdc_order_section" style="width: 100%; max-width: 100%; padding: 0px; background: #efefef; margin-bottom: 12px;">';


// Order Table
$message .= '
					<table class="hdc_table" style="width: 100%; max-width: 100%; background: white; border-collapse: collapse; margin: auto; padding: 5px; width: 100%;">
						<tbody style="display: table-row-group; vertical-align: middle; border-color: inherit;">
							<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
								<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>Order Date</strong></td>
								<td style = "word-break: break-all;background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;">'.get_the_date("", $order_id).' at '.get_the_time("", $order_id).'</td>
							</tr>
							<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
								<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>Amount: </strong></td>
								<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>'.hdc_amount($hdc_payment_amount).'</strong></td>
							</tr>';

// get tax info
			if($hdc_checkout_tax != "" && $hdc_checkout_tax != null){
				foreach($hdc_checkout_tax as $value) {
					// calculate tax amount
					if ($hdc_checkout_tax_amount != "" && $hdc_checkout_tax_amount != null){
						$hdc_checkout_tax_amount = ($hdc_payment_amount - (float)$hdc_shipping_method - (float)$hdc_checkout_tax_amount) * ((int)$value["value"] / 100);
					} else {
						$hdc_checkout_tax_amount = "";
					}

					$message = $message. '<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1); ">';
					$message = $message. '<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>'.$value["name"].' '.$value["value"].'% </strong></td>';
					$message = $message. '<td  style = " background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;">'.hdc_amount(number_format((float)$hdc_checkout_tax_amount, 2, '.', '')).'</td>';
					$message = $message. '</tr>';
				}
			}

$message .='
						</tbody>
					</table>
				</div>';

// get product info
$message .= '
				<div class="hdc-section" style="width: 100%; max-width: 100%; padding: 0px; background: #efefef; margin-bottom: 12px;">
					<table style="background: white; border-collapse: collapse; margin: auto; padding: 5px; width: 100%; border-bottom: 1px solid #C1C3D1;">
					<thead>
						<tr>
							<th style = "color: #000; background: #dedbdb; border-bottom: 0px solid #fff; border-right: 1px solid #C1C3D1; border-left: 1px solid #C1C3D1; font-size: 1em; font-weight: 400; padding: 8px 24px; text-align: left; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1); vertical-align: middle;">Product</th>
							<th style = "color: #000; background: #dedbdb; border-bottom: 0px solid #fff; border-right: 1px solid #C1C3D1; border-left: 1px solid #C1C3D1; font-size: 1em; font-weight: 400; padding: 8px 24px; text-align: left; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1); vertical-align: middle;">Price</th>
							<th style = "color: #000; background: #dedbdb; border-bottom: 0px solid #fff; border-right: 1px solid #C1C3D1; border-left: 1px solid #C1C3D1; font-size: 1em; font-weight: 400; padding: 8px 24px; text-align: left; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1); vertical-align: middle;">Quantity</th>
						</tr>
					</thead>
					<tbody style="display: table-row-group; vertical-align: middle; border-color: inherit;">';

						if($hdc_checkout_products != "" && $hdc_checkout_products != null){
							foreach($hdc_checkout_products as $value) {
								$product_name = '<strong>'.$value["name"].'</strong>';
								if($value["variation"] != "" && $value["variation"] != null){
									$variation = explode("|", $value["variation"]);
									for($i = 0; $i < count($variation); $i++){
										$variation[$i] = explode("*", $variation[$i]);
										$variation[$i] = join(" - ", $variation[$i]);
									}
									$variation = join(", ", $variation);						
									$product_name = $product_name. "<br/>".$variation;
								}
								$message .= '<tr border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">';
								$message .= '<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;">'.$product_name.'</td>';
								$message .= '<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;">'.hdc_amount(number_format((float)$value["price"], 2, '.', '')).'</td>';
								$message .= '<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;">'.$value["quantity"].'</td>';
								$message .= '</tr>';
							}
						}
$message .= '
					</tbody>
					</table>
				</div>';

// get customer info

$message .= '
<div class="hdc-section" style="width: 100%; max-width: 100%; padding: 0px; background: #efefef; margin-bottom: 12px;">
<table  style="background: white; border-collapse: collapse; margin: auto; padding: 5px; width: 100%;">
	<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
		<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>Customer Name</strong></td>
		<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"> '.$hdc_checkout_first_name.' '.$hdc_checkout_last_name.'</td>
	</tr>
	<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
		<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>Customer Email</strong></td>
		<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"> '.$hdc_checkout_email.'</td>
	</tr>
	<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
		<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>Customer Phone #</strong></td>
		<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"> '.$hdc_checkout_phone.'</td>
	</tr>
	<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
		<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>Address</strong></td>
		<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"> '.$hdc_checkout_address.'</td>
	</tr>
	<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
		<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>Address 2</strong></td>
		<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"> '.$hdc_checkout_address2.'</td>
	</tr>
	<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
		<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>City</strong></td>
		<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"> '.$hdc_checkout_city.'</td>
	</tr>
	<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
		<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>State/Province</strong></td>
		<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"> '.$hdc_checkout_state.'</td>
	</tr>
	<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
		<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>Country</strong></td>
		<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"> '.$hdc_checkout_country.'</td>
	</tr>
	<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
		<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>ZIP/Postal Code</strong></td>
		<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"> '.$hdc_checkout_zip.'</td>
	</tr>
	<tr style="border-top: 1px solid #C1C3D1; border-bottom: 1px solid #C1C3D1; color: #666B85; font-size: 14px; font-weight: normal; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);">
		<td style = "word-break: break-all;border-left: 1px solid #C1C3D1; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"><strong>Customer Order Note</strong></td>
		<td style = "word-break: break-all; background: #FFFFFF; padding: 10px 20px; text-align: left; vertical-align: middle; font-weight: 300; text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1); border-right: 1px solid #C1C3D1;"> '.$hdc_checkout_note.'</td>
	</tr>
</table></div>';


// end of message
$message .='
		</div>
	</div>
</body>

</html>';

if (filter_var($hdc_store_email, FILTER_VALIDATE_EMAIL)) {
	// valid email address, let's use it
	$from_email = $hdc_store_email;
} else {
	// the email was not valid, use generic domain email instead
	$site_domain = get_site_url();
	$find = array( 'http://', 'https://', 'www.' );
	$site_domain = str_replace( $find, '', $site_domain );
	$from_email = "noreply@".$site_domain;
}

$from_name = $hdc_store_name;
$headers = array('From: '.$from_name.' <'.$from_email.'>', 'Content-Type: text/html; charset=UTF-8');
wp_mail( $to, $subject, $message, $headers, "" );
?>