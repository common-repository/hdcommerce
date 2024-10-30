<?php
/*
	HDCommerce functions admin
	Functions required for backend admin are here
*/

/* Add admin menu pages
------------------------------------------------------- */
function hdc_register_menu_page()
{
    if (current_user_can('edit_others_pages')) {
        add_menu_page('HDCommerce Settings', 'HDCommerce', 'edit_posts', 'hdc_options', 'hdc_create_options_page', plugins_url('../images/logo-16.png', __FILE__), 2);
        add_menu_page('HDCommerce Addons', 'HDC Addons', 'edit_posts', 'hdc_addons', 'hdc_create_addons_page', "", 99);
        add_menu_page('Export Orders', 'Export Orders', 'manage_options', 'hdc_export_orders', 'hdc_meta_orders_export', plugins_url('../images/logo-16.png', __FILE__), 2);

        add_submenu_page('hdc_options', 'Orders', 'Orders', 'manage_options', '/edit.php?post_type=hdc_orders');
        add_submenu_page('hdc_options', 'Addons', 'Addons', 'manage_options', 'hdc_addons');
        add_submenu_page('hdc_options', 'Coupons', 'Coupons', 'manage_options', 'edit.php?post_type=hdc_coupon');

        remove_menu_page('edit.php?post_type=hdc_orders');
        remove_menu_page('hdc_addons');
        remove_menu_page('hdc_export_orders');
    }	
}
add_action('admin_menu', 'hdc_register_menu_page', 1);

/* Set HDC admin pages to only use a 1 column layout
------------------------------------------------------- */
function hdc_set_product_screen()
{
    // Only allow one column for product page
    function hdc_screen_layout_columns($columns)
    {
        $columns['hdc_product'] = 1;
        return $columns;
    }
    add_filter('screen_layout_columns', 'hdc_screen_layout_columns');

    // Set the column to 1
    function hdc_screen_layout()
    {
        return 1;
    }
    add_filter('get_user_option_screen_layout_hdc_product', 'hdc_screen_layout');
    add_filter('get_user_option_screen_layout_hdc_orders', 'hdc_screen_layout');
}
hdc_set_product_screen();

/* Remove default meta boxes from products to speed up the page
and enure that they aren't used by the user by mistake
------------------------------------------------------- */
function hdc_remove_meta_boxes()
{
    // product post type
    remove_meta_box('submitdiv', 'hdc_product', 'side'); // publish
    remove_meta_box('productdiv', 'hdc_product', 'side'); // custom product tax
    remove_meta_box('hdc_productsdiv', 'hdc_product', 'side'); // custom taxonomy
    remove_meta_box('postimagediv', 'hdc_product', 'side'); // featured image
    remove_meta_box('slugdiv', 'hdc_product', 'side'); // slug
    // order post type
    remove_meta_box('submitdiv', 'hdc_orders', 'side'); // publish
}
add_action('admin_menu', 'hdc_remove_meta_boxes');

/* Add custom page subtitle/post status to HDCommerce pages
------------------------------------------------------- */
function hdc_filter_display_post_states($post_states, $post)
{
    $hdc_shop_page = get_option('hdc_store_page');
    $hdc_checkout_page = get_option('hdc_checkout_page');
    $hdc_payment_page = get_option('hdc_payment_page');

    if ($hdc_shop_page == $post->ID) {
        echo ' - HDCommerce Shop Page';
    } else if ($hdc_checkout_page == $post->ID) {
        echo ' - HDCommerce Checkout Page';
    } else if ($hdc_payment_page == $post->ID) {
        echo ' - HDCommerce Payment Page';
    }
    return $post_states;
};
add_filter('display_post_states', 'hdc_filter_display_post_states', 10, 2);

/* Admin Columns for Orders custom post type
------------------------------------------------------- */
function hdc_custom_order_columns($columns)
{
    unset($columns['title']);
    $columns['order_title'] = __('Title', 'your_text_domain');
    $columns['order_total'] = __('Total', 'your_text_domain');

    foreach ($columns as $key => $value) {
        if ($key == 'date') {
            $new['order_title'] = $columns['order_title'];
        }
        if ($key == 'date') {
            $new['order_total'] = $columns['order_total'];
        }
        $new[$key] = $value;
    }
    return $new;
}
add_filter('manage_hdc_orders_posts_columns', 'hdc_custom_order_columns');

/* Add data to Orders custom post type columns
------------------------------------------------------- */
function hdc_order_column_data($column, $post_id)
{
    switch ($column) {
        case 'order_title':
            $order_title = esc_attr(get_post_meta($post_id, 'hdc_order_title', true));
            $order_status = esc_attr(get_post_meta($post_id, 'hdc_order_status', true));
            $order_title = explode("|", $order_title);
            echo '<a href = "' . get_edit_post_link($post_id) . '"><strong>' . $order_title[0] . '</strong> - <span class = "name">' . $order_title[1] . '</span></a>';
            if ($order_status == "payment pending" || $order_status == "payment failed") {
                echo ' - ' . $order_status;
            }
            break;
        case 'order_total':
            $hdc_payment_amount = esc_attr(get_post_meta($post_id, 'hdc_payment_amount', true));
            $hdc_payment_amount = number_format((float) $hdc_payment_amount, 2, '.', '');
            echo hdc_amount($hdc_payment_amount);
            break;
    }
}
add_action('manage_hdc_orders_posts_custom_column', 'hdc_order_column_data', 10, 2);

/* Add Dashboard Widget to show the shop stats
------------------------------------------------------- */
function hdc_dashboard_widget()
{
    if (current_user_can('edit_others_pages')) {
        wp_add_dashboard_widget(
            'hdc_store_dashboard_widget', // Widget slug.
            'HDCommerce Shop Stats', // Title.
            'hdc_dashboard_function' // Display function.
        );

        wp_enqueue_style(
            'hdc_admin_style',
            plugin_dir_url(__FILE__) . '../css/hdc_dashboard.css'
        );

    }
}
add_action('wp_dashboard_setup', 'hdc_dashboard_widget');

function hdc_dashboard_function()
{
    // get total number of orders
    $hdc_total_orders = wp_count_posts('hdc_orders')->publish;

    $args = array(
        'post_type' => 'hdc_orders',
        'meta_query' => array(
            array(
                'key' => 'hdc_order_status',
                'value' => 'payment completed',
            ),
        ),
    );

    $posts = new WP_Query($args);
    $hdc_completed_orders = $posts->found_posts;

    // get non completed orders
    $hdc_failed_orders = $hdc_total_orders - $hdc_completed_orders;

    // get total products
    // TODO: Extend to also show variations
    $hdc_total_products = wp_count_posts('hdc_product')->publish;

    // get order stats
    $date = date("m-Y");
    $hdc_order_stats = sanitize_text_field(get_option("hdc_order_stats"));
    $amount = 0;
    $orders = 0;
    if ($hdc_order_stats != "" && $hdc_order_stats != null) {
        // read JSON
        $hdc_order_stats = stripslashes($hdc_order_stats);
        $hdc_order_stats = json_decode(html_entity_decode($hdc_order_stats), true);

        $counter = 0;
        $counter2 = -1;

        foreach ($hdc_order_stats as $value) {
            if ((string) $date == (string) $value[0]) {
                $counter2 = $counter;
            }
            $counter = $counter + 1;
        }

        if ($counter2 >= 0) {
            // there is an existing month
            $amountO = $hdc_order_stats[$counter2][1];
            $amount = number_format($amountO, 2);
            $orders = $hdc_order_stats[$counter2][2];
        }
    }
    ?>

<div id="hdc_store_dashboard">
    <div class="hdc_l">
        <div class="hdc_row">
            Total Products: <?php echo $hdc_total_products; ?>
        </div>
        <div class="hdc_row">
            Completed Orders: <?php echo $hdc_completed_orders; ?>
        </div>
        <div class="hdc_row">
            Non-completed Orders: <?php echo $hdc_failed_orders; ?>
        </div>
    </div>
    <div class="hdc_r">
        <div class="hdc_row">
            <strong>Amount Made This Month</strong><br />
            <?php echo hdc_amount($amount); ?>
        </div>
        <div class="hdc_row">
            <strong>Completed Orders This Month</strong><br />
            <?php echo $orders; ?>
        </div>
    </div>
    <div class="clear"></div>
</div>

<?php }

/* Add Export button to order CPT
------------------------------------------------------- */
function hdc_add_export_to_orders()
{
    global $current_screen;
    if ($current_screen->post_type != "hdc_orders") {
        return;
	}
	
    ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    let filter_button = document.getElementById("post-query-submit");
    let data = '<a href = "<?php echo admin_url('
    admin.php ? page = hdc_export_orders '); ?>" class = "button button-primary">EXPORT ORDERS</a>';
    filter_button.insertAdjacentHTML("afterend", data);
});
</script>

<?php
}
add_action('admin_head-edit.php', 'hdc_add_export_to_orders');

/* If detected cache plugin is installed,
 * automatically clear the cache when a product
 * is saved or updated, or if settings have updated
// TODO: Add more popular cache plugin compatability
------------------------------------------------------- */
function hdc_clear_cache()
{
    // WP Fastest Cache
    if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')) {
        $GLOBALS['wp_fastest_cache']->deleteCache(true);
    }
}
add_action('hdc_product_saved_after', 'hdc_clear_cache');
add_action('hdc_settings_saved_after', 'hdc_clear_cache');

/* decrypt and HDC encoded string
------------------------------------------------------- */
function hdc_decode($ciphertext)
{
    $c = base64_decode($ciphertext);
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, wp_salt(), $options = OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, wp_salt(), $as_binary = true);
    if (hash_equals($hmac, $calcmac)) {
        return wp_kses_post($original_plaintext);
    } else {
        return "error decoding";
    }
}



/* On plugin update, upgrade database
------------------------------------------------------- */
function hdc_upgrade(){
	

	function updateOlderProducts($product_id){
		$hdc_product_price = get_post_meta($product_id, 'hdc_product_price', true);

		if(!isset($hdc_product_price) || $hdc_product_price == ""){
			return; // product already upgraded
		}

		$hdc_product_sale_price = get_post_meta($product_id, 'hdc_product_sale_price', true);
		$hdc_product_sku = get_post_meta($product_id, 'hdc_product_sku', true);
		$hdc_saved_variation = get_post_meta($product_id, 'hdc_saved_variation', true);
		$hdc_saved_products = get_post_meta($product_id, 'hdc_saved_products', true);
		$hdc_product_content = get_post_meta($product_id, 'hdc_product_content', true);
		$hdc_product_short_content = get_post_meta($product_id, 'hdc_product_short_content', true);
		$hdc_product_featured_image = get_post_meta($product_id, 'hdc_product_featured_image', true);
		$hdc_product_featured_image_gallery = get_post_meta($product_id, 'hdc_product_featured_image_gallery', true);
		$hdc_product_categories = get_post_meta($product_id, 'hdc_product_categories', true);
		$hdc_shipping_width = get_post_meta($product_id, 'hdc_shipping_width', true);
		$hdc_shipping_height = get_post_meta($product_id, 'hdc_shipping_height', true);
		$hdc_shipping_length = get_post_meta($product_id, 'hdc_shipping_length', true);
		$hdc_shipping_weight = get_post_meta($product_id, 'hdc_shipping_weight', true);
		$hdc_shipping_class = get_post_meta($product_id, 'hdc_shipping_class', true);
		$hdc_shipping_stock = get_post_meta($product_id, 'hdc_shipping_stock', true);

		$item = new stdClass();
		$item->product_name->value = get_the_title($product_id);
		$item->product_name->name = "product_name";
		$item->product_name->type = "text";

		$item->sku->value = $hdc_product_sku;
		$item->sku->name = "sku";
		$item->sku->type = "text";

		$item->product_price->value = $hdc_product_price;
		$item->product_price->name = "product_price";
		$item->product_price->type = "currency";

		$item->product_sale_price->value = $hdc_product_sale_price;
		$item->product_sale_price->name = "product_sale_price";
		$item->product_sale_price->type = "currency";

		$item->short_description->value = hdc_encodeURIComponent($hdc_product_short_content);
		$item->short_description->name = "short_description";
		$item->short_description->type = "editor";

		$item->description->value = hdc_encodeURIComponent($hdc_product_content);
		$item->description->name = "description";
		$item->description->type = "editor";

		$item->product_width->value = $hdc_shipping_width;
		$item->product_width->name = "product_width";
		$item->product_width->type = "float";

		$item->product_height->value = $hdc_shipping_height;
		$item->product_height->name = "product_height";
		$item->product_height->type = "float";

		$item->product_length->value = $hdc_shipping_length;
		$item->product_length->name = "product_length";
		$item->product_length->type = "float";

		$item->product_weight->value = $hdc_shipping_weight;
		$item->product_weight->name = "product_weight";
		$item->product_weight->type = "float";

		$item->shipping_class->value = $hdc_shipping_class;
		$item->shipping_class->name = "shipping_class";
		$item->shipping_class->type = "select";

		$item->product_stock->value = $hdc_shipping_stock;
		$item->product_stock->name = "product_stock";
		$item->product_stock->type = "integer";

		$hdc_saved_variation = json_decode($hdc_saved_variation, true);	
		$product_variations = new stdClass();
		$product_variations->name = "product_variations";
		$product_variations->type = "variations";
		if(isset($hdc_saved_variation) && $hdc_saved_variation != ""){
			for($i = 0; $i < count($hdc_saved_variation); $i++){
				$values = array();
				foreach ($hdc_saved_variation[$i] as $key => $v) {
					$variations = new stdClass();
					$slug = trim(strtolower($key));
					$slug = sanitize_title($slug);			
					$variations->name = $key;
					$variations->slug = $slug;	

					$options = array();

					for($x = 0; $x < count($v); $x++){
						$option = new stdClass();
						$option->name = $v[$x];
						$option->slug = trim(strtolower(sanitize_title($v[$x])));
						array_push($options, $option);
					}
					$variations->options = $options;
					array_push($values, $variations);				
				}
				$product_variations->value = $values;
			}
		}	
		$item->product_variations = $product_variations;

		$hdc_saved_products = json_decode($hdc_saved_products, true);		
		$product_permutations = new stdClass();
		$product_permutations->name = "product_permutations";
		$product_permutations->type = "permutations";
		if(isset($hdc_saved_products) && $hdc_saved_products != ""){
			$value = array();
			foreach ($hdc_saved_products as $key => $v) {
				$permutation = new stdClass();
				$slug = trim(strtolower($key));
				$slug = str_replace("-", "*", $slug);
				$permutation->id = $slug;
				$permutation->name = $v[2];
				$permutation->title = $v[2];
				$options = new stdClass();
				$options->price = $v[0];
				$options->sale = $v[1];
				$options->sku = $v[4];
				$options->stock = $v[3];

				$options->width =$v[5];
				$options->height =$v[6];
				$options->length =$v[7];
				$options->weight =$v[8];
				$options->shipping_class = "";
				$options->default = $v[9];
				$permutation->options = $options;
				array_push($value, $permutation);
			}
			$product_permutations->value = $value;

		}	
		$item->product_permutations = $product_permutations;
		$item->featured_image->value = $hdc_product_featured_image;
		$item->featured_image->name = "featured_image";
		$item->featured_image->type = "image";

		$item->product_gallery->value = $hdc_product_featured_image_gallery;
		$item->product_gallery->name = "product_gallery";
		$item->product_gallery->type = "gallery";
		update_post_meta($product_id, 'hdc_product_data', json_encode($item), false);

	}

	function updateAllProducts(){
		$args = array(
			'post_type'              => array( 'hdc_product' ),
			'posts_per_page'         => '-1',
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				updateOlderProducts(get_the_ID());
			}
		} 
		wp_reset_postdata();	
	}
	updateAllProducts();
}