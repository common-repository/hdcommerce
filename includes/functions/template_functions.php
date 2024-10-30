<?php
/*
	HDCommerce Template Functions
	Functions that can be called in template files to grab sections
*/

/* Get product data
used in the shop header to grab product meta data
------------------------------------------------------- */
// TODO: PREFIX
function get_product_data($post)
{
    $fields = get_post_meta($post, 'hdc_product_data', true);
    $fields = json_decode($fields, true);
	if(isset($fields) && $fields != ""){
    	$fields = hdc_sanitize_fields($fields);
	}
	
	$permutations = array();
	if(isset($fields["product_permutations"]["value"])){
		 $permutations = $fields["product_permutations"]["value"];
	}
	
	// TODO: only load this on product pages

    wp_localize_script(
        'hdc_script',
        'productData',
        json_encode($permutations)
    );

    wp_localize_script(
        'hdc_script',
        'productPrice',
        json_encode(array($fields["product_price"]["value"], $fields["product_sale_price"]["value"]))
    );

    return $fields;
}

function hdc_sanitize_fields($fields)
{

    foreach ($fields as $key => $v) {
        if (
            $v["type"] == "text" ||
            $v["type"] == "select" ||
            $v["type"] == "radio" ||
            $v["type"] == "checkbox" ||
            $v["type"] == "image" ||
            $v["type"] == "gallery"
        ) {
            $fields[$key]["value"] = sanitize_text_field($v["value"]);
        } else if (
            $v["type"] == "currency" ||
            $v["type"] == "float"
        ) {
            if ($fields[$key]["value"] != "") {
                $fields[$key]["value"] = floatval($v["value"]);
            }
        } else if ($v["type"] == "integer") {
            if ($fields[$key]["value"] != "") {
                $fields[$key]["value"] = intval($v["value"]);
            }
        } else if ($v["type"] == "categories") {
			if(isset($v["value"]) && $v["value"] != ""){
				for($i = 0; $i < count($v["value"]); $i++){
					$fields[$key]["value"][$i] = intval($v["value"][$i]);
				}
			}
		} else if ($v["type"] == "editor") {
            $fields[$key]["value"] = wp_kses_post(stripslashes(urldecode($v["value"])));
        } else if ($v["type"] == "email") {
            $fields[$key]["value"] = sanitize_email($v["value"]);
        } else if ($v["type"] == "variations") {
            if (isset($v["value"]) && $v["value"] != "") {
                for ($x = 0; $x < count($v["value"]); $x++) {
                    $fields[$key]["value"][$x]["name"] = sanitize_text_field($v["value"][$x]["name"]);
                    $fields[$key]["value"][$x]["slug"] = sanitize_text_field($v["value"][$x]["slug"]);

                    for ($y = 0; $y < count($fields[$key]["value"][$x]["options"]); $y++) {
                        $fields[$key]["value"][$x]["options"][$y]["name"] = sanitize_text_field($v["value"][$x]["options"][$y]["name"]);
                        $fields[$key]["value"][$x]["options"][$y]["slug"] = sanitize_text_field($v["value"][$x]["options"][$y]["slug"]);
                    }
                }
            }
        } else if ($v["type"] == "permutations") {
            if (isset($v["value"]) && $v["value"] != "") {
                for ($x = 0; $x < count($v["value"]); $x++) {
                    $fields[$key]["value"][$x]["name"] = sanitize_text_field($v["value"][$x]["name"]);
                    $fields[$key]["value"][$x]["id"] = sanitize_text_field($v["value"][$x]["id"]);
                    $fields[$key]["value"][$x]["title"] = wp_kses_post(urldecode($v["value"][$x]["title"]));

                    // options
                    if ($v["value"][$x]["options"]["price"] != "") {
                        $fields[$key]["value"][$x]["options"]["price"] = floatval($v["value"][$x]["options"]["price"]);
                    }

                    if ($v["value"][$x]["options"]["sale"] != "") {
                        $fields[$key]["value"][$x]["options"]["sale"] = floatval($v["value"][$x]["options"]["sale"]);
                    }
                    $fields[$key]["value"][$x]["options"]["sku"] = sanitize_text_field($v["value"][$x]["options"]["sku"]);

                    if ($v["value"][$x]["options"]["stock"] != "") {
                        $fields[$key]["value"][$x]["options"]["stock"] = floatval($v["value"][$x]["options"]["stock"]);
                    }
                    if ($v["value"][$x]["options"]["weight"] != "") {
                        $fields[$key]["value"][$x]["options"]["weight"] = floatval($v["value"][$x]["options"]["weight"]);
                    }
                    if ($v["value"][$x]["options"]["width"] != "") {
                        $fields[$key]["value"][$x]["options"]["width"] = floatval($v["value"][$x]["options"]["width"]);
                    }
                    if ($v["value"][$x]["options"]["height"] != "") {
                        $fields[$key]["value"][$x]["options"]["height"] = floatval($v["value"][$x]["options"]["height"]);
                    }
                    if ($v["value"][$x]["options"]["length"] != "") {
                        $fields[$key]["value"][$x]["options"]["length"] = floatval($v["value"][$x]["options"]["length"]);
                    }
                    $fields[$key]["value"][$x]["options"]["default"] = sanitize_text_field($v["value"][$x]["options"]["default"]);
                    $fields[$key]["value"][$x]["options"]["shipping_class"] = sanitize_text_field($v["value"][$x]["options"]["shipping_class"]);
                }
            }
        } else {
			// unknown type, santize as string
            $fields[$key]["value"] = sanitize_text_field($v["value"]);
        }
    }

    return $fields;
}

/* Print breadcrumb list for product page
------------------------------------------------------- */
function hdc_breadcrumb()
{
    global $post;
    $hdc_shop_page = get_option('hdc_store_page');
    echo '<ul id="hdc_breadcrumbs">';
    echo '<li><a href = "' . get_home_url() . '">Home</a></li>';
    echo '<li><a href = "' . get_the_permalink($hdc_shop_page) . '">Shop</a></li>';
    $terms = get_the_terms(get_the_ID(), 'hdc_products');
    if ($terms && !is_wp_error($terms)) {
        $url = get_term_link($terms[0]->term_id, 'hdc_products');
        echo '<li><a href = "' . $url . '">' . $terms[0]->name . '</a></li>';
    }
    echo '</ul>';
    echo '<div class = "clear"></div>';
}

/* Prints product title
------------------------------------------------------- */
function hdc_get_product_title()
{
    global $hdc_product_headings;
    if ($hdc_product_headings != "do not display") {
        if ($hdc_product_headings == "h1" || $hdc_product_headings == "h2" || $hdc_product_headings == "h3" || $hdc_product_headings == "h4") {
            echo '<' . $hdc_product_headings . ' id = "product_title">' . get_the_title() . '</' . $hdc_product_headings . '>';
        } else {
            $hdc_product_headings = "h2";
            echo '<' . $hdc_product_headings . ' id = "product_title">' . get_the_title() . '</' . $hdc_product_headings . '>';
        }
    }
}

/* Get the shop sidebar
------------------------------------------------------- */
function hdc_sidebar()
{
    include dirname(__FILE__) . '/../templates/sidebar.php';
}

/* Get the shop header
------------------------------------------------------- */
function get_hdc_header()
{
    require dirname(__FILE__) . '/../templates/header.php';
}

/* Get the shop cart
------------------------------------------------------- */
function get_hdc_cart()
{
    require dirname(__FILE__) . '/../templates/cart.php';
}

/* Get the checkout payment processor form
------------------------------------------------------- */
function get_hdc_checkout()
{
    global $hdc_payment_page;
    $hdc_payment_gateway = get_option('hdc_payment_gateway');
    $hdc_payment_gateway = explode("|", $hdc_payment_gateway);
    $gateway = $hdc_payment_gateway[1];
    if (function_exists($hdc_payment_gateway[1])) {
        $gateway();
    } else {
        echo '<strong>ERROR:</strong> There is an issue with the payment gateway. Please contact the site admin.';
    }
}

/* Get Reviews on the Product Page
------------------------------------------------------- */
function hdc_get_reviews()
{
    global $post;
    $comments = get_comments(array('post_id' => $post->ID));

    // create the review form
    ?>
	<h3>Submit A Review</h3>
	<div id = "hdc_submit_review_form">
		<?php wp_nonce_field('hdc_product_review_' . $post->ID . '_nonce', 'hdc_product_review_' . $post->ID . '_nonce');?>
		<div id="hdc_review_rating"><span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span></div>
		<input type = "hidden" id = "hdc_rating"/>
		<div class = "hdc_checkout_row">
			<input type = "text" id = "hdc_name" class = "required" placeholder = "Your name"/>
		</div>
		<div class = "hdc_checkout_row">
			<input type = "email" id = "hdc_email" class = "required" placeholder = "Your email"/>
		</div>
		<div class = "hdc_checkout_row">
			<textarea placeholder = "Your review" id = "hdc_review" class = "required" ></textarea>
		</div>
		<div id = "hdc_submit_review" class = "hdc_button">
			Submit Review
		</div>
	</div>

	<?php
		$hasReviews = false;
		$hasReviewsDisplayed = false;
		foreach ($comments as $comment) {
			if ($comment->comment_approved == 1) {
				$hasReviews = true;
				if ($hasReviews) {
					if (!$hasReviewsDisplayed) {
						$hasReviewsDisplayed = true;
						echo '<h3>Reviews for ' . get_the_title($post) . '</h3>';
					}
				}
     ?>
			<div class = "hdc_review">
				<div class = "hdc_review_name">
					<?php echo $comment->comment_author; ?>
					<span><?php echo $comment->comment_date; ?></span>
					<div class = "hdc_review_rating">
					<?php
						$stars = get_comment_meta($comment->comment_ID, "hdc_rating");
						$stars = $stars[0];
						for ($x = 1; $x <= $stars; $x++) {
							echo '★';
						}
					?>
					</div>
				</div>
				<div class = "clear"></div>
				<div class = "hdc_review_message">
					<?php echo $comment->comment_content; ?>
				</div>
			</div>
		<?php
		}
    }
}

/* Shows the total star rating for a product
------------------------------------------------------- */
function hdc_show_rating()
{
    global $post;
    $comments = get_comments(array('post_id' => $post->ID));
    $total_stars = 0;
    $total_reviews = 0;
    foreach ($comments as $comment) {
        if ($comment->comment_approved == 1) {
            $stars = get_comment_meta($comment->comment_ID, "hdc_rating");
            $stars = intval($stars[0]);
			if($stars != 0){
            	$total_stars = intval($total_stars) + intval($stars);
            	$total_reviews = $total_reviews + 1;
			}
        }
    }
    if ($total_stars > 0) {
        $average = $total_stars / $total_reviews;
        $realValue = round($average * 2) / 2;
        $nearestValue = floor($average);
        echo '<div id = "hdc_product_rating">';

        for ($x = 1; $x <= $nearestValue; $x++) {
            echo '★';
        }

        if ($realValue != $nearestValue) {
            echo '★ ' . $realValue;
        }

        echo '</div>';
        echo '<div class = "clear"></div>';
    }
}

/* Shows extra fields on the checkout page
------------------------------------------------------- */
function hdc_checkout_extra_fields()
{
    global $hdc_checkout_extra_fields;
    $hdc_checkout_extra_fields = array();
    do_action("hdc_checkout_extra_fields");
    if (!empty($hdc_checkout_extra_fields)) {
        echo '<div id = "hdc_checkout_extra">';
        echo '<h3>Additional Information</h3>';
        foreach ($hdc_checkout_extra_fields as $field) {
            echo '<div class = "hdc_checkout_row">';
            $safeId = sanitize_title($field[1][0]);
            $required = "";
            if ($field[1][3] == 1) {
                $required = "required";
            }
            if ($field[0] == "text") {
                echo '<input id="' . $safeId . '" class="' . $required . '" name="' . $safeId . '" type="text" placeholder="' . $field[1][2] . '" />';
            } else if ($field[0] == "email") {
                echo '<input id="' . $safeId . '" class="hdc_email_checkout ' . $required . '" name="' . $safeId . '" type="email" placeholder="' . $field[1][2] . '" />';
            } else if ($field[0] == "textarea") {
                echo '<textarea id="' . $safeId . '" class="' . $required . '" name="' . $safeId . '"placeholder="' . $field[1][2] . '"></textarea>';
            } else if ($field[0] == "checkbox") {
                $content = urldecode($field[1][1]);
                echo '<div style = "padding: 6px 0 ;position: relative; display: inline-block" class = "hdc_checkout_row"><label class="non-block" for="' . $safeId . '">' . $content . '</label>
			<div class="hdc-options-check"><input type="checkbox" id="' . $safeId . '" value="yes" name="' . $safeId . '" class = "hdc_checkbox_checkout ' . $required . '"><label for="' . $safeId . '"></label></div></div><div class = "clear"></div>';
            } else if ($field[0] == "general") {
                $content = urldecode($field[1][1]);
                echo '<div class = "hdc_checkout_row" style = "padding: 6px 0 ;">' . $content . '</div>';
            } else if ($field[0] == "hidden") {
                $function = $field[1][4]; // function
                if (function_exists($function)) {
                    $functionData = $function();
                    echo '<input type = "hidden" id = "' . $safeId . '" name="' . $safeId . '" value = "' . $functionData . '"/>';
                }
            }
            echo "</div>";
        }
        echo '</div>';
    }
}

/* Read extra feilds from submitted checkout
------------------------------------------------------- */
function hdc_get_extra_form_fields()
{
    global $hdc_checkout_extra_fields;
    global $hdc_form;
    $hdc_checkout_extra_fields = array();
    do_action("hdc_get_extra_form_fields");

    foreach ($hdc_checkout_extra_fields as $field) {
        $safeId = sanitize_title($field[1][0]);
        if ($field[0] == "text") {
            $hdc_form->$safeId = sanitize_text_field($_POST[$safeId]);
        } else if ($field[0] == "email") {
            $hdc_form->$safeId = sanitize_email($_POST[$safeId]);
        } else if ($field[0] == "textarea") {
            $hdc_form->$safeId = sanitize_textarea_field($_POST[$safeId]);
        } else if ($field[0] == "checkbox") {
            $hdc_form->$safeId = isset($_POST[$safeId]);
        }
    }
}

/* Save extra fields to orders
------------------------------------------------------- */
function hdc_add_fields_to_order()
{
    global $hdc_checkout_extra_fields;
    global $hdc_form;
    global $post_id;
    $hdc_checkout_extra_fields = array();
    do_action("hdc_add_fields_to_order");

    foreach ($hdc_checkout_extra_fields as $field) {
        $safeId = sanitize_title($field[1][0]);
        if ($field[0] == "text" || $field[0] == "hidden") {
            add_post_meta($post_id, $safeId, sanitize_text_field($hdc_form->$safeId), true);
        } else if ($field[0] == "email") {
            add_post_meta($post_id, $safeId, sanitize_email($hdc_form->$safeId), true);
        } else if ($field[0] == "textarea") {
            add_post_meta($post_id, $safeId, sanitize_textarea_field($hdc_form->$safeId), true);
        } else if ($field[0] == "checkbox") {
            add_post_meta($post_id, $safeId, isset($hdc_form->$safeId), true);
        }
    }
}

/* Display extra fields on the order page
------------------------------------------------------- */
function hdc_display_fields_order($hdc_id)
{		
    global $hdc_checkout_extra_fields;
    $hdc_checkout_extra_fields = array();
    do_action("hdc_checkout_extra_fields");	
	
	
    if (!empty($hdc_checkout_extra_fields)) {
        echo '	<div id = "hdc_order_extra" class = "hdc_order_section">
		<table class = "hdc_table">
		<thead>
			<tr>
				<th>Field</th>
				<th>Value</th>
			</tr>
		</thead>
		<tbody>';

        foreach ($hdc_checkout_extra_fields as $field) {
            $safeId = sanitize_title($field[1][0]);
            $val = esc_attr(get_post_meta($hdc_id, $safeId, true));
            if ($field[0] == "checkbox" && $val == 1) {
                $val = "checked";
            } else if ($field[0] == "textarea") {
                $val = wpautop($val);
            }
            if ($field[0] != "general") {
                echo '<tr><td><strong>' . $field[1][0] . '</strong></td><td>' . $val . '</td></tr>';
            }
        }
        echo '</tbody></table></div>';
    }
}

/* Display extra fields on the order page
------------------------------------------------------- */
function hdc_get_cart_product($value)
{
	$product = new \stdClass();
	
    $id = intval($value[0]);
    if (get_post_status($id) != "publish") {
		$product->warnings = array("exist");
        return $product;
    }

    $product->id = $id;
    $product->variation = sanitize_text_field($value[1]);
    $product->quantity = intval($value[2]);
    $product->permalink = get_the_permalink($id);
    $product->product_name = get_the_title($id);
	$product->classes = array();
	$product->warnings = array();
	
    $data = get_product_data($id);

    $product->price = $data["product_price"]["value"];
    $product->sale = $data["product_sale_price"]["value"];
    $product->shipping_class = $data["shipping_class"]["value"];
    $product->width = floatval($data["product_width"]["value"]);
    $product->height = floatval($data["product_height"]["value"]);
    $product->length = floatval($data["product_length"]["value"]);
    $product->weight = floatval($data["product_weight"]["value"]);
    $product->stock = $data["product_stock"]["value"];

    $product->variation_name = "";

    if ($product->variation != "") {
        // find the permutation with id of variation
        $permutations = $data["product_permutations"]["value"];
        for ($i = 0; $i < count($permutations); $i++) {
            if ($permutations[$i]["id"] == $product->variation) {
                $product->variation_name = urldecode($permutations[$i]["title"]);
                $product->variation_name = str_replace('<span class="product_item_name_spacer"></span>', ",&nbsp;&nbsp;", $product->variation_name);
                $product->price = $permutations[$i]["options"]["price"];
                $product->sale = $permutations[$i]["options"]["sale"];

                if (isset($permutations[$i]["options"]["weight"]) && $permutations[$i]["options"]["weight"] != "") {
                    $product->weight = $permutations[$i]["options"]["weight"];
                }

                if (isset($permutations[$i]["options"]["width"]) && $permutations[$i]["options"]["width"] != "") {
                    $product->width = $permutations[$i]["options"]["width"];
                }

                if (isset($permutations[$i]["options"]["height"]) && $permutations[$i]["options"]["height"] != "") {
                    $product->height = $permutations[$i]["options"]["height"];
                }

                if (isset($permutations[$i]["options"]["length"]) && $permutations[$i]["options"]["length"] != "") {
                    $product->length = $permutations[$i]["options"]["length"];
                }

                if (isset($permutations[$i]["options"]["shipping_class"]) && $permutations[$i]["options"]["shipping_class"] != "") {
                    $product->shipping_class = $permutations[$i]["options"]["shipping_class"];
                }

                if (isset($permutations[$i]["options"]["stock"]) && $permutations[$i]["options"]["stock"] !== "" && $product->stock !== 0) {
                    $product->stock = $permutations[$i]["options"]["stock"];
                }
                break;
            }
        }
        if ($product->variation_name == "") {
            $product_exists = false;
        }
    }
	
    if ($product->stock === "" || $product->stock === null) {
        $product->stock = 999;
    }

	if($product->quantity > $product->stock){		
		if($product->stock === 0){
			$product->warnings = array("exist");
		} else {
			$product->quantity = $product->stock;
			array_push($product->classes, "not_enough_stock");
			array_push($product->warnings, "stock");		
		}
	}
	
	$product->classes = join(" ", $product->classes);	
    return $product;
}

function hdc_print_category_style()
{ ?>
	<?php
 		$style = sanitize_text_field(get_option("hdc_product_view_type"));
 	?>
	<div role="button" aria-pressed="false" aria-label = "display 1 column" title = "view products as a list with description" class = "hdc_category_style_icon <?php if($style == "1") {echo "hdc_category_style_active";}?>" data-id = "hdc_product_columns1">
		<svg class = "hdc_icon" xmlns="http://www.w3.org/2000/svg" width = "16" height = "16" viewBox="0 0 512 512"><path d="M492 236H144a20 20 0 100 40h348a20 20 0 100-40zM492 86H144a20 20 0 100 40h348a20 20 0 100-40zM492 386H144a20 20 0 100 40h348a20 20 0 100-40z"/><circle cx="27" cy="106" r="27"/><circle cx="27" cy="256" r="27"/><circle cx="27" cy="406" r="27"/></svg>

	</div>
	<div role="button" aria-pressed="false" aria-label = "display 2 columns" title = "view products in two columns" class = "hdc_category_style_icon <?php if($style == "2") {echo "hdc_category_style_active";}?>" data-id = "hdc_product_columns2">
		<span class = "hdc_icon">
			||
		</span>
	</div>
	<div role="button" aria-pressed="false" aria-label = "display 3 columns" title = "view products in three columns" class = "hdc_category_style_icon <?php if($style == "3") {echo "hdc_category_style_active";}?>" data-id = "hdc_product_columns3">
		<span class = "hdc_icon">
			|||
		</span>
	</div>
	<div role="button" aria-pressed="false" aria-label = "display 4 columns" title = "view products in four columns" class = "hdc_category_style_icon <?php if($style == "4") {echo "hdc_category_style_active";}?>" data-id = "hdc_product_columns4">
		<span class = "hdc_icon">
			||||
		</span>
	</div>
<?php }

function hdc_print_category_order($order)
{ ?>
	<form id = "hdc_category_order_input_form" action = "<?php echo get_the_permalink(); ?>">
		<select id = "hdc_category_order_input" name = "hdc_category_order_input">
			<option value = "default" <?php if($order === "default"){echo 'selected';} ?>>default order</option>
			<option value = "recent" <?php if($order === "recent"){echo 'selected';} ?>>most recent</option>
			<!--
			// these order options are not ready yet
			<option value = "price-high" <?php if($order === "price-high"){echo 'selected';} ?>>price (high to low)</option>
			<option value = "price-low" <?php if($order === "price-low"){echo 'selected';} ?>>price (low to high)</option>
			-->
		</select>
	</form>
<?php }