<?php
/*
	HDCommerce Orders Page Content
	Prints the order information
*/

wp_enqueue_style(
    'hdc_admin_style',
    plugin_dir_url(__FILE__) . '../css/hdc_admin_style.css'
);

/* Grab meta data
------------------------------------------------------- */
$hdc_order_title = esc_attr(get_post_meta($object->ID, 'hdc_order_title', true));
$hdc_order_title = explode("|", $hdc_order_title);
$hdc_transaction_id = esc_attr(get_post_meta($object->ID, 'hdc_transaction_id', true));

$hdc_checkout_first_name = esc_attr(get_post_meta($object->ID, 'hdc_checkout_first_name', true));
$hdc_checkout_last_name = esc_attr(get_post_meta($object->ID, 'hdc_checkout_last_name', true));
$hdc_checkout_email = esc_attr(get_post_meta($object->ID, 'hdc_checkout_email', true));
$hdc_checkout_phone = esc_attr(get_post_meta($object->ID, 'hdc_checkout_phone', true));
$hdc_checkout_country = esc_attr(get_post_meta($object->ID, 'hdc_checkout_country', true));
$hdc_checkout_state = esc_attr(get_post_meta($object->ID, 'hdc_checkout_state', true));
$hdc_checkout_address = esc_attr(get_post_meta($object->ID, 'hdc_checkout_address', true));
$hdc_checkout_address2 = esc_attr(get_post_meta($object->ID, 'hdc_checkout_address2', true));
$hdc_checkout_city = esc_attr(get_post_meta($object->ID, 'hdc_checkout_city', true));
$hdc_checkout_zip = esc_attr(get_post_meta($object->ID, 'hdc_checkout_zip', true));
$hdc_shipping_method = esc_attr(get_post_meta($object->ID, 'hdc_shipping_method', true));
$hdc_shipping_method_name = esc_attr(get_post_meta($object->ID, 'hdc_shipping_method_name', true));
$hdc_checkout_tax_amount = esc_attr(get_post_meta($object->ID, 'hdc_checkout_tax_amount', true));
$hdc_checkout_tax = esc_attr(get_post_meta($object->ID, 'hdc_checkout_tax', true));
$hdc_checkout_tax = json_decode(html_entity_decode($hdc_checkout_tax), true);
$hdc_checkout_note = esc_attr(get_post_meta($object->ID, 'hdc_checkout_note', true));
$hdc_payment_amount = esc_attr(get_post_meta($object->ID, 'hdc_payment_amount', true));
$hdc_payment_amount = number_format((float) $hdc_payment_amount, 2, '.', '');
$hdc_checkout_products = esc_attr(get_post_meta($object->ID, 'hdc_checkout_products', true));
$hdc_checkout_products = json_decode(html_entity_decode($hdc_checkout_products), true);
$hdc_coupons = esc_attr(get_post_meta($object->ID, 'hdc_coupons', true));
$hdc_coupons = json_decode(html_entity_decode($hdc_coupons), true);
$hdc_subtotal = hdc_calculate_subtotal($hdc_checkout_products);

function hdc_calculate_subtotal($hdc_checkout_products)
{
    $subtotal = 0;
    foreach ($hdc_checkout_products as $p) {
        $subtotal = $subtotal + floatval($p["price"]) * floatval($p["quantity"]);
    }
    return $subtotal;
}

?>

<h1><?php echo $hdc_order_title[0]; ?> - <span class="name"><?php echo $hdc_order_title[1]; ?></span> <span
        class="id">Order ID: <?php the_title();?></span></h1>
<div id="hdc_orders">
    <div id="hdc_order_highlight" class="hdc_order_section">
        <table class="hdc_table">
            <tr>
                <td><strong>Order Date</strong></td>
                <td width="80%"><?php echo get_the_date(); ?> at <?php echo get_the_time(); ?></td>
            </tr>
            <tr>
                <td><strong>Order Amount</strong></td>
                <td width="80%"> <?php echo hdc_amount($hdc_payment_amount); ?></td>
            </tr>
            <tr>
                <td><strong>Subtotal</strong></td>
                <td width="80%">
                    <?php echo hdc_amount(floatval($hdc_payment_amount) - floatval($hdc_checkout_tax_amount)); ?></td>
            </tr>
            <?php
				if ($hdc_checkout_tax != "" && $hdc_checkout_tax != null) {
					foreach ($hdc_checkout_tax as $value) {
						// calculate tax amount
						if ($hdc_checkout_tax_amount != "" && $hdc_checkout_tax_amount != null) {
							$hdc_checkout_tax_amount = ($hdc_payment_amount - (float) $hdc_shipping_method - (float) $hdc_checkout_tax_amount) * ((int) $value["value"] / 100);
						} else {
							$hdc_checkout_tax_amount = "";
						}

						echo '<tr>';
						echo '<td><strong>' . $value["name"] . ' ' . $value["value"] . '% </strong></td>';
						echo '<td>' . hdc_amount(number_format((float) $hdc_checkout_tax_amount, 2, '.', '')) . '</td>';
						echo '</tr>';
					}
				}
			?>

            <tr>
                <td><strong><?php echo $hdc_shipping_method_name; ?></strong></td>
                <td width="80%"><?php echo hdc_amount($hdc_shipping_method); ?></td>
            </tr>

            <?php
				if (isset($hdc_coupons)) {
					foreach ($hdc_coupons as $coupon) {
        	?>
            <tr>
                <td><strong>Coupon code</strong></td>
                <td width="80%">
                    <strong><?php echo $coupon["code"]; ?></strong> | -<?php		
																			echo hdc_get_currency_symbol();
																			if ($coupon["type"] === "amount") {
																				echo $coupon["amount"];
																			} else {;
																				$discount = floatval($coupon["amount"]) / 100;
																				$discount = floatval($hdc_subtotal) * floatval($discount);
																				echo $discount;
																			}
																		?>
		<br />
                    Discount <?php echo $coupon["type"]; ?>: <?php if ($coupon["type"] === "amount") {echo hdc_amount();} else {echo "%";}
        echo $coupon["amount"];?>
                </td>
            </tr>
            <?php
}
}

?>

        </table>
    </div>

    <?php hdc_display_fields_order($object->ID); ?>

    <div id="hdc_order_cart" class="hdc_order_section">
        <table class="hdc_table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th width="1">Quantity</th>
                </tr>
            </thead>

            <tbody>
                <?php
if ($hdc_checkout_products != "" && $hdc_checkout_products != null) {
    foreach ($hdc_checkout_products as $value) {
        $product_name = '<strong>' . $value["name"] . '</strong>';
        if ($value["variation"] != "" && $value["variation"] != null) {
            $variation = explode("|", $value["variation"]);
            for ($i = 0; $i < count($variation); $i++) {
                $variation[$i] = explode("*", $variation[$i]);
                $variation[$i] = join(" - ", $variation[$i]);
            }
            $variation = join(", ", $variation);
            $product_name = $product_name . "<br/>" . $variation;
        }
        echo '<tr>';
        echo '<td>' . $product_name . '</td>';
        echo '<td>' . hdc_amount(number_format((float) $value["price"], 2, '.', '')) . '</td>';
        echo '<td>' . $value["quantity"] . '</td>';
        echo '</tr>';
    }
}
?>
            </tbody>
        </table>
    </div>

    <div id="hdc_order_customer" class="hdc_order_section">
        <table class="hdc_table">
            <tr>
                <td><strong>Customer Name</strong></td>
                <td width="80%"><?php echo $hdc_checkout_first_name . ' ' . $hdc_checkout_last_name; ?></td>
            </tr>
            <tr>
                <td><strong>Customer Email</strong></td>
                <td width="80%"><a
                        href="mailto:<?php echo $hdc_checkout_email; ?>"><?php echo $hdc_checkout_email; ?></a></td>
            </tr>
            <tr>
                <td><strong>Customer Phone #</strong></td>
                <td width="80%"><a href="tel:<?php echo $hdc_checkout_phone; ?>"><?php echo $hdc_checkout_phone; ?></a>
                </td>
            </tr>
            <tr>
                <td><strong>Address</strong></td>
                <td width="80%"><?php echo $hdc_checkout_address; ?></td>
            </tr>
            <tr>
                <td><strong>Address 2</strong></td>
                <td width="80%"><?php echo $hdc_checkout_address2; ?></td>
            </tr>
            <tr>
                <td><strong>City</strong></td>
                <td width="80%"><?php echo $hdc_checkout_city; ?></td>
            </tr>
            <tr>
                <td><strong>State/Province</strong></td>
                <td width="80%"><?php echo $hdc_checkout_state; ?></td>
            </tr>
            <tr>
                <td><strong>Country</strong></td>
                <td width="80%"><?php echo $hdc_checkout_country; ?></td>
            </tr>
            <tr>
                <td><strong>ZIP/Postal Code</strong></td>
                <td width="80%"><?php echo $hdc_checkout_zip; ?></td>
            </tr>
            <tr>
                <td><strong>Customer Order Note</strong></td>
                <td width="80%"><?php echo $hdc_checkout_note; ?></td>
            </tr>
        </table>
    </div>

</div>