<?php
/*
    HDCommerce functions product custom post type admin
    Functions required adding and editing a product
*/

function hdc_add_new_product()
{
    if (!current_user_can('edit_others_pages')) {
        echo '{"error": "User level cannot modify products"}';
        return;
    }

    $hdc_nonce = sanitize_text_field($_POST['nonce']);
    if (!wp_verify_nonce($hdc_nonce, 'hdc_meta_products_nonce')) {
        echo '{"error": "Nonce was not valid"}';
        return;
    }

    // passed validation, get and sanitize fields
    // TODO: Pass through `template_functions.php` hdc_sanitize_fields(); for consitancy

    $id = intval($_POST["hdc_product_id"]);
    $fields = $_POST["payload"];
    $slug = sanitize_title($_POST["slug"]);

    $isNew = true;
    if ($id > 0) {
        $isNew = false;
    }

    $product_title = "";
	$fields = hdc_sanitize_data_save($fields); 
    $product_title = $fields["product_name"]["value"];

    if ($isNew) {		
        // NEW PRODUCT
        $post_information = array(
            'post_title' => $product_title,
            'post_content' => '', // need to set as blank
            'post_type' => 'hdc_product',
            'post_status' => 'publish',
        );
        $post_id = wp_insert_post($post_information);
        add_post_meta($post_id, 'hdc_product_data', json_encode($fields), false);
		wp_set_post_terms($post_id, $fields["product_categories"]["value"], "hdc_products", false);
        echo '{"success": {"id": "' . $post_id . '", "permalink": "' . get_permalink($post_id) . '", "slug" : "' . sanitize_title($product_title) . '"}}';
    } else {
        // EXISTING PRODUCT
        update_post_meta($id, 'hdc_product_data', json_encode($fields), false);
        // in case product title was changed
        $post_information = array(
            'ID' => $id,
            'post_title' => $product_title,
            'post_name' => $slug,
        );
        wp_update_post($post_information);
		wp_set_post_terms($id, $fields["product_categories"]["value"], "hdc_products", false);
        echo '{"success": {"id": "' . $id . '", "permalink": "' . get_permalink($id) . '", "slug" : "' . $slug . '"}}';
    }

    do_action('hdc_product_saved_after'); // hook
    die();
}
add_action('wp_ajax_hdc_add_new_product', 'hdc_add_new_product');


function hdc_sanitize_data_save($fields)
{
  // sanitize data
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
        } else if ($v["type"] == "editor") {
            $fields[$key]["value"] = urlencode(wp_kses_post($v["value"]));
        } else if ($v["type"] == "email") {
            $fields[$key]["value"] = sanitize_email($v["value"]);
        } else if ($v["type"] == "categories") {
			if(isset($v["value"]) && $v["value"] != ""){
				for($i = 0; $i < count($v["value"]); $i++){
					$fields[$key]["value"][$i] = intval($v["value"][$i]);
				}
			}
		} else if ($v["type"] == "variations") {
			if(isset($v["value"]) && $v["value"] != ""){
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
                    $fields[$key]["value"][$x]["title"] = urlencode(wp_kses_post($v["value"][$x]["title"]));

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
        }
    }
	return $fields;
}


function hdc_add_new_category()
{
    if (current_user_can('edit_others_pages')) {
        $hdc_nonce = sanitize_text_field($_POST['hdc_nonce']);
        if (wp_verify_nonce($hdc_nonce, 'hdc_meta_prod_category_nonce') != false) {
            // check each added category to see if it already exists,
            // if it doesn't, then create it
            $hdc_new_cat = sanitize_text_field($_POST['hdc_new_cat']);
            $hdc_new_cat = explode("|", $hdc_new_cat);

            if (!term_exists($hdc_new_cat[0], "hdc_products", $hdc_new_cat[1])) {
                if ($hdc_new_cat[1] == 0 || $hdc_new_cat[1] == null) {
                    $hdc_cat = wp_insert_term(
                        $hdc_new_cat[0], // the term
                        'hdc_products' // the taxonomy
                    );
                } else {
                    $hdc_cat = wp_insert_term(
                        $hdc_new_cat[0], // the term
                        'hdc_products', // the taxonomy
                        array(
                            'parent' => $hdc_new_cat[1],
                        )
                    );
                }
                echo $hdc_cat["term_id"];
            }

        } else {
            echo 'permission denied';
        }
    } else {
        echo 'permission denied';
    }
    die();
}
add_action('wp_ajax_hdc_add_new_category', 'hdc_add_new_category');
