<?php
/*
	HDCommerce Product Meta Data and Pages
	This creates all of the custom meta for products,
	and creates the hdc_product pages
*/

/* Add custom metabox to hdc_product pages
------------------------------------------------------- */
function hdc_create_hdc_product_page()
{
    function hdc_meta_products_setup()
    {
        add_action('add_meta_boxes', 'hdc_add_meta_products');
    }
    add_action('load-post.php', 'hdc_meta_products_setup');
    add_action('load-post-new.php', 'hdc_meta_products_setup');

    function hdc_add_meta_products()
    {
        add_meta_box(
            'hdc_meta_products',
            esc_html__('HD Commerce', 'example'),
            'hdc_meta_products',
            'hdc_product',
            'normal',
            'high'
        );

        function force_hdc_meta_products_to_top()
        {
            global $wp_meta_boxes;
            $meta = $wp_meta_boxes["hdc_product"]["normal"]["high"];

            function check_first_array($meta)
            {
                $t = array_shift($meta);

                if (isset($t["hdc_meta_products"])) {
                    array_push($meta, $t); // add removed back to end of array
                    check_first_array();
                } else {
                    array_push($meta, $t); // add removed back to end of array
                    return $meta;
                }
            }
            $meta = check_first_array($meta);
            $wp_meta_boxes["hdc_product"]["normal"]["high"] = $meta;
        }
        force_hdc_meta_products_to_top();

    }
}
hdc_create_hdc_product_page();

/* Add custom metabox to hdc_orders pages
------------------------------------------------------- */
function hdc_create_hdc_order_page()
{
    function hdc_meta_orders_setup()
    {
        add_action('add_meta_boxes', 'hdc_add_meta_orders');
    }
    add_action('load-post.php', 'hdc_meta_orders_setup');
    add_action('load-post-new.php', 'hdc_meta_orders_setup');

    function hdc_add_meta_orders()
    {
        add_meta_box(
            'hdc_meta_orders',
            esc_html__('HD Commerce', 'example'),
            'hdc_meta_orders',
            'hdc_orders',
            'normal',
            'default'
        );
    }
}
hdc_create_hdc_order_page();

/* Add custom metabox to hdc_orders pages
------------------------------------------------------- */
function hdc_create_hdc_coupon_page()
{
    function hdc_meta_coupons_setup()
    {
        add_action('add_meta_boxes', 'hdc_add_meta_coupons');
        add_action('save_post', 'hdc_meta_coupons_save', 10, 2);
    }
    add_action('load-post.php', 'hdc_meta_coupons_setup');
    add_action('load-post-new.php', 'hdc_meta_coupons_setup');

    function hdc_add_meta_coupons()
    {
        add_meta_box(
            'hdc_meta_coupons',
            esc_html__('Coupon Options', 'example'),
            'hdc_meta_coupons',
            'hdc_coupon',
            'normal',
            'default'
        );
    }
}
hdc_create_hdc_coupon_page();

function hdc_meta_products($object, $box)
{
    include dirname(__FILE__) . '/product-edit/template.php';
}

/* Orders meta
------------------------------------------------------- */
function hdc_meta_orders($object, $box)
{
    wp_nonce_field(basename(__FILE__), 'hdc_meta_orders_nonce');
    ?>
	<div id = "hdc_wrapper">
		<div id="hdc_product_wrapper">
				<?php
					// Load the order content
    				require dirname(__FILE__) . '/meta/meta_orders.php';
    			?>
		</div>
	</div>
<?php
}

/* Orders export meta
------------------------------------------------------- */
function hdc_meta_orders_export()
{
    wp_nonce_field('hdc_export_orders', 'hdc_export_orders');
    ?>
	<div id = "hdc_wrapper">
		<div id="hdc_product_wrapper">
				<?php
					// Load the order content
					require dirname(__FILE__) . '/meta/meta_orders_export.php';
    			?>
		</div>
	</div>
<?php
}

/* Coupons meta
------------------------------------------------------- */
function hdc_meta_coupons($object, $box)
{
    wp_nonce_field(basename(__FILE__), 'hdc_meta_coupons_nonce');
    ?>
	<div id = "hdc_wrapper">
		<div id="hdc_product_wrapper">
				<?php
					// Load the order content
					require dirname(__FILE__) . '/meta/meta_coupons.php';
				?>
		</div>
	</div>
<?php
}

function hdc_meta_coupons_save($post_id, $post)
{

    if ($post->post_type != "hdc_coupon") {
        return false;
    }

    if (isset($_POST['hdc_save']) && $_POST['hdc_save'] === "Y") {
        $percent_discount = "no";
        if (isset($_POST['hdc_coupon_percent_discount'])) {
            $percent_discount = sanitize_text_field($_POST['hdc_coupon_percent_discount']);
        }
        $coupon_amount = 0;
        if (isset($_POST['hdc_coupon_amount'])) {
            $coupon_amount = number_format(floatval($_POST['hdc_coupon_amount']), 2);
        }
        $coupon_minimum_amount = 0;
        if (isset($_POST['hdc_coupon_minimum_amount'])) {
            $coupon_minimum_amount = number_format(floatval($_POST['hdc_coupon_minimum_amount']), 2);
        }		
        $expire_date = "";
        if (isset($_POST['hdc_coupon_expire_date'])) {
            $expire_date = sanitize_text_field($_POST['hdc_coupon_expire_date']);
        }

        $restrict_categories = "";
        if (isset($_POST['hdc_coupon_restrict_categories'])) {
            $restrict_categories = sanitize_text_field($_POST['hdc_coupon_restrict_categories']);
        }

        $restrict_products = "";
        if (isset($_POST['hdc_coupon_restrict_products'])) {
            $restrict_products = sanitize_text_field($_POST['hdc_coupon_restrict_products']);
        }

        update_post_meta($post_id, "hdc_coupon_percent_discount", $percent_discount);
        update_post_meta($post_id, "hdc_coupon_amount", $coupon_amount);
		update_post_meta($post_id, "hdc_coupon_minimum_amount", $coupon_minimum_amount);
        update_post_meta($post_id, "hdc_coupon_expire_date", $expire_date);
        update_post_meta($post_id, "hdc_coupon_restrict_categories", $restrict_categories);
        update_post_meta($post_id, "hdc_coupon_restrict_products", $restrict_products);
    }
}
?>