<?php
// Flat Rate Shipping Module
// ______________________________________________________________

// check to see if there is a flat rate cart override
$shipping_cart = sanitize_text_field(get_option("hdc_shipping_flat_rate_shipping_cart"));

if ($shipping_cart == "" || $shipping_cart == null){
	hdc_calculate_flat_rate_shipping_price($hdc_shipping_class);
} else {
	// need to check if all products in cart have shipping class F (free)
	$hdc_shipping_class = explode("|", $hdc_shipping_class);
	$free = "yes";
	foreach($hdc_shipping_class as $value){
		if($value != "F"){
			$free = "no";
		}
	}
	if($free === "yes"){
		echo '<option value = "0.00" id = "hdc_free_shipping">Free Shipping</option>';
	} else {
		echo '<option value = "'.$shipping_cart.'">'.hdc_amount($shipping_cart).' | Flat Rate Shipping 2</option>';
	}
}

function hdc_calculate_flat_rate_shipping_price ($hdc_shipping_class){
	// get the list of shipping classes

	$shipping_class_A = intval(get_option("hdc_shipping_flat_rate_shipping_class_A"));
	$shipping_class_B = intval(get_option("hdc_shipping_flat_rate_shipping_class_B"));
	$shipping_class_C = intval(get_option("hdc_shipping_flat_rate_shipping_class_C"));
	$shipping_class_D = intval(get_option("hdc_shipping_flat_rate_shipping_class_D"));
	$shipping_class_E = intval(get_option("hdc_shipping_flat_rate_shipping_class_E"));

	$hdc_shipping_class = explode("|", $hdc_shipping_class);

	// get the price of each method
	$price = 0;	
	foreach($hdc_shipping_class as $value){
		$class_price = 0;
		if($value == "A"){
			$class_price = $shipping_class_A;
		} else if ($value == "B"){
			$class_price = $shipping_class_B;
		} else if ($value == "C"){
			$class_price = $shipping_class_C;
		} else if ($value == "D"){
			$class_price = $shipping_class_D;
		} else if ($value == "E"){
			$class_price = $shipping_class_E;
		}
		$price = $price + $class_price;
	}

	echo '<option value = "'.$price.'">'.hdc_amount($price).' | Flat Rate Shipping</option>';
}

?>