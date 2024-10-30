<?php
/*
	HDCommerce Cart
	Prints a table that shows all products in the cart,
	along with the variation, price, and quantity
*/

global $hdc_cart_products;
global $total_cost;

if(count($hdc_cart_products) == 0){
	echo '<p>There are currently no products in your cart.</p>';
	return;
}
?>
<div id="hdc_cart">

<table class="hdc_table" id="hdc_cart_table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th width="1">Quantity</th>
            <th width="1">x</th>
        </tr>
    </thead>
    <tbody>
        <?php
			$total_cost = 0;
			$products_not_available = false;
			$products_quantity_reduced = false;
			foreach ($hdc_cart_products as $value) {				
				$product = hdc_get_cart_product($value);
					
				if(in_array("exist", $product->warnings)){
					$products_not_available = true;
				}				
				
				if(in_array("stock", $product->warnings)){
					$products_quantity_reduced = true;
				}				
				if (isset($product->id) && $product->stock > 0) { ?>
					<tr>
						<td>
							<a href="<?php echo $product->permalink; ?>"><?php echo $product->product_name; ?></a>
							<?php
								if ($product->variation_name != "") {
									echo '<br/>' . $product->variation_name;
								}
							?>
						</td>
						<td>
							<?php
								$main_price = $product->price;
								if ($product->sale != "") {
									echo "<s>" . hdc_amount($product->price) . '</s> ' . hdc_amount($product->sale);
									$main_price = $product->sale;
								} else {
									echo hdc_amount($product->price);
								}

								$total_cost += (floatval($main_price) * intval($product->quantity));
							?>
						</td>
						<td>
							<input class="hdc_cart_quantity <?php echo $product->classes; ?>" type="number" data-shipping-class="<?php echo $product->shipping_class; ?>"
								data-weight="<?php echo $product->weight; ?>" data-length="<?php echo $product->length; ?>"
								data-width="<?php echo $product->width; ?>" data-height=<?php echo $product->height; ?>"
								data-name="<?php echo $product->product_name; ?>" data-variation="<?php echo $product->variation; ?>"
								data-id="<?php echo $product->id; ?>" data-price="<?php echo $main_price; ?>" step="1" min="1"
								max="<?php echo $product->stock; ?>"
								data-stock = "<?php echo $product->quantity; ?>"
								value="<?php echo $product->quantity; ?>">
						</td>
						<td>
							<div  role="button" aria-label = "remove from cart" class="hdc_cart_remove_product"></div>
						</td>
					</tr>

			<?php
					}
				}
			?>
    </tbody>
</table>
																
<?php
	if($products_not_available){
		echo '<p style = "text-align: center;"><small>One or more products are no longer available for purchase and have been removed from your cart.</small></p>';
	}
																
	if($products_quantity_reduced){
		echo '<p style = "text-align: center;"><small>One or more products in your cart no longer has enough stock. The quantity has been automatically updated.</small></p>';
	}																
?>
																
<div id="hdc_cart_actions">
    <strong>Total Cart Amount: <span class="hdc_cart_total"><?php echo hdc_amount($total_cost); ?></span>.</strong>
    <br />
    <div class="hdc_checkout_row">
        <label for="hdc_cart_coupon_input" style="display:block">Coupon Code</label>
        <input type="text" id="hdc_cart_coupon_input" style="max-width: 300px; text-align:right" value=""
            placeholder="enter code..." />
        <div id="hdc_added_cart_coupons"></div>
    </div>
    <span class="hdc_cart_notice_tax"><small>Tax and shipping will be calculated once you've entered in your
            address</small></span>
    <?php
		$hdc_i_love_hdcommerce = get_option("hdc_i_love_hdcommerce");
		if ($hdc_i_love_hdcommerce == 1) {
			echo '<br/><small>powered by <a href = "https://hdselling.com?utm_source=HDCommerce&utm_medium=cartPage" title = "Ultimate WordPress eCommerce experience">HDCommerce</a></small>';
		}
	?>
</div>
									 
</div>
<div role="button" class="hdc_button" id="hdc_cart_continue">
	CONTINUE TO CHECKOUT
</div>