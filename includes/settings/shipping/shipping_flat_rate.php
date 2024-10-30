<?php
/*
HDCommerce Settings Shipping Flat Rate
Contains:
@hdc_shipping_usps
 */

global $hdc_shipping;
// read in the options
$hdc_shipping_flat_rate_shipping_cart = get_option("hdc_shipping_flat_rate_shipping_cart");
if ($hdc_shipping_flat_rate_shipping_cart != "" && $hdc_shipping_flat_rate_shipping_cart != null) {
    floatval($hdc_shipping_flat_rate_shipping_cart);
}
$hdc_shipping_flat_rate_shipping_class_a = floatval(get_option("hdc_shipping_flat_rate_shipping_class_a"));
$hdc_shipping_flat_rate_shipping_class_b = floatval(get_option("hdc_shipping_flat_rate_shipping_class_b"));
$hdc_shipping_flat_rate_shipping_class_c = floatval(get_option("hdc_shipping_flat_rate_shipping_class_c"));
$hdc_shipping_flat_rate_shipping_class_d = floatval(get_option("hdc_shipping_flat_rate_shipping_class_d"));
$hdc_shipping_flat_rate_shipping_class_e = floatval(get_option("hdc_shipping_flat_rate_shipping_class_e"));

?>

<!-- Flate Rate -->
<h3>Flate Rate Shipping</h3>
<p>
	Flat rate shipping works by assigning each product a shipping class. The rate of the class is what will be used to calculate shipping for that product. Generally speaking, it is recommended that Shipping Class A be used for your smallest and cheapest to ship products, and Shipping Class E being used for your largest and most expensive to ship products.
</p>


<div class = "hdc_setting_row">
	<div class="hdc_input-effect">
		<input class="hdc_input <?php if ($hdc_shipping_flat_rate_shipping_cart != null && $hdc_shipping_flat_rate_shipping_cart != "") {echo 'has-content';}?>" id="hdc_shipping_flat_rate_shipping_cart" name="hdc_shipping_flat_rate_shipping_cart" type="text" value="<?php echo $hdc_shipping_flat_rate_shipping_cart; ?>">
		<label for="hdc_shipping_flat_rate_shipping_cart">Flat rate for the entire cart <a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>Only use this if you want to charge a flat rate for the entire cart. This means that it doesn't matter how many products, or what products are in the cart, shipping will be this number.</span></span></a></label>
		<span class="focus-border"></span>
	</div>
</div>

<div class = "hdc_setting_row">

	<table class="hdc_table" id="hdc_shipping_flat_rate_table">
		<thead>
			<tr>
			<th>Class</th>
			<th>Amount</th>
		</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					Class A
				</td>
				<td>
					<input class="hdc_input" id="hdc_shipping_flat_rate_shipping_class_a" name="hdc_shipping_flat_rate_shipping_class_a" type="text" value="<?php echo $hdc_shipping_flat_rate_shipping_class_a; ?>" placeholder = "rate">
				</td>
			</tr>
			<tr>
				<td>
					Class B
				</td>
				<td>
					<input class="hdc_input" id="hdc_shipping_flat_rate_shipping_class_b" name="hdc_shipping_flat_rate_shipping_class_b" type="text" value="<?php echo $hdc_shipping_flat_rate_shipping_class_b; ?>" placeholder = "rate">
				</td>
			</tr>
			<tr>
				<td>
					Class C
				</td>
				<td>
					<input class="hdc_input" id="hdc_shipping_flat_rate_shipping_class_c" name="hdc_shipping_flat_rate_shipping_class_c" type="text" value="<?php echo $hdc_shipping_flat_rate_shipping_class_c; ?>" placeholder = "rate">
				</td>
			</tr>
			<tr>
				<td>
					Class D
				</td>
				<td>
					<input class="hdc_input" id="hdc_shipping_flat_rate_shipping_class_d" name="hdc_shipping_flat_rate_shipping_class_d" type="text" value="<?php echo $hdc_shipping_flat_rate_shipping_class_d; ?>" placeholder = "rate">
				</td>
			</tr>
			<tr>
				<td>
					Class E
				</td>
				<td>
					<input class="hdc_input" id="hdc_shipping_flat_rate_shipping_class_e" name="hdc_shipping_flat_rate_shipping_class_e" type="text" value="<?php echo $hdc_shipping_flat_rate_shipping_class_e; ?>" placeholder = "rate">
				</td>
			</tr>

		</tbody>
	</table>

</div>