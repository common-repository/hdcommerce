<?php

include dirname(__FILE__) . '/fields.php';

function hdc_get_product_data($id)
{
    $fields = get_post_meta($id, 'hdc_product_data', true);
    $fields = json_decode($fields, true);
    return $fields;
}

function hdc_get_product_tabs()
{
	global $tabs;
    $tabs = array();
    $tab = new \stdClass();
    $tab->slug = "main";
    $tab->title = "Main";
    array_push($tabs, $tab);
    $tab = new \stdClass();
    $tab->slug = "description";
    $tab->title = "Description";
    array_push($tabs, $tab);
    $tab = new \stdClass();
    $tab->slug = "categories";
    $tab->title = "Categories";
    array_push($tabs, $tab);
    $tab = new \stdClass();
    $tab->slug = "shipping";
    $tab->title = "Shipping";
    array_push($tabs, $tab);
    $tab = new \stdClass();
    $tab->slug = "variations";
    $tab->title = "Variations";
    array_push($tabs, $tab);
    $tab = new \stdClass();
    $tab->slug = "images";
    $tab->title = "Images";
    array_push($tabs, $tab);
	
    do_action("hdc_add_product_admin_tab"); 
	
	// sanitize
	foreach ($tabs as $key => $v) {
		$tabs[$key]->slug = sanitize_title($tabs[$key]->slug);
		$tabs[$key]->title = sanitize_text_field($tabs[$key]->title);
	}	
    return $tabs;
}

function hdc_print_product_tabs()
{
    $tabs = hdc_get_product_tabs();
    for ($i = 0; $i < count($tabs); $i++) {
        $classes = "";
        if ($i == 0) {
            $classes = "tab_nav_item_active";
        }
        echo '<div class="tab_nav_item ' . $classes . '" data-id="' . $tabs[$i]->slug . '">' . $tabs[$i]->title . '</div>';
    }
}

function hdc_get_product_tab_content()
{
    $fields = array();
    // it's just easier this way
    $hdc_default = '{"main":[{"type":"col-1-1","children":[{"type":"text","name":"product_name","label":"Product Title","placeholder":"product name...","tooltip":"This is the main name of your product","value":"","required":true},{"type":"text","name":"product_sku","label":"SKU","placeholder":"sku...","tooltip":"Default SKU or unique product ID. Used for internal tracking or for warehouse shipping","value":""}]},{"type":"col-1-1","children":[{"type":"currency","name":"product_price","label":"Product Price","placeholder":"0.00","tooltip":"This is the <em>initial visible price</em> of the product,and is used as the default for variations","value":"","required":true},{"type":"currency","name":"product_sale_price","label":"Product Sale Price","placeholder":"0.00","tooltip":"Enter product sale price here. Leave blank to remove sale","value":""}]},{"type":"editor","name":"short_description","label":"Short Description","required":true,"media":true,"value":"","tooltip":"Think of this as your quick \"elevator pitch\". <br><br>Tell your customers what the product is and what makes it special in as few words as possible"}],"description":[{"type":"editor","name":"description","label":"Description","media":true,"value":"","tooltip":"This is where you can add as much detail to your product as you like. Things such as how the product was made,materials,ingredients,disclaimers etc."}],"categories":[{"type":"action","function":"printProductTabCategories"}],"shipping":[{"type":"content","value":"Default shipping ad stock controls for this product. This is only needed if you are using a shipping method that requires product weight and dimension. You can also overide these settings on a per-variation basis."},{"type":"col-1-1-1","children":[{"type":"float","name":"product_width","label":"Width","prefix":"size_unit","placeholder":"0","value":"","options":[{"name":"step","value":0.1},{"name":"min","value":0},{"name":"max","value":9999999}]},{"type":"float","name":"product_height","label":"Height","prefix":"size_unit","placeholder":"0","value":"","options":[{"name":"step","value":0.1},{"name":"min","value":0},{"name":"max","value":9999999}]},{"type":"float","name":"product_length","label":"Length","prefix":"size_unit","placeholder":"0","value":"","options":[{"name":"step","value":0.1},{"name":"min","value":0},{"name":"max","value":9999999}]}]},{"type":"col-1-1-1","children":[{"type":"float","name":"product_weight","label":"Weight","prefix":"weight_unit","placeholder":"0","value":"","options":[{"name":"step","value":0.1},{"name":"min","value":0},{"name":"max","value":9999999}]},{"type":"select","name":"shipping_class","label":"Shipping Class","value":"","options":[{"label":"A","value":"A"},{"label":"B","value":"B"},{"label":"C","value":"C"},{"label":"D","value":"D"},{"label":"E","value":"E"},{"label":"FREE","value":"F"}]},{"type":"integer","name":"product_stock","label":"Stock","tooltip":"","placeholder":"0","value":"Default stock for product. Set to zero (0) to disable adding this product to the cart","options":[{"name":"step","value":1},{"name":"min","value":0},{"name":"max","value":9999999}]}]}],"variations":[{"type":"content","value":"Product variations are any options that your customers will need to select in order to add a product to the cart. For example,if you are selling a shirt,then you might want to add size as a variation since a user would need to select what size they want before adding to the cart."},{"type":"action","function":"printProductTabVariations"}],"images":[{"type":"col-1-1","children":[{"type":"image","name":"featured_image","label":"Featured Image","tooltip":"This is the main or primary image used to represent this product","options":{"title":"Set Product Featured Image","button":"SET FEATURED IMAGE","multiple":false}},{"type":"gallery","name":"product_gallery","label":"Product Gallery","tooltip":"Additional images for this product","options":{"title":"Add Images To Product Gallery","button":"ADD IMAGES","multiple":true}}]}]}';
    $hdc_default = json_decode($hdc_default, true);
    $fields = $hdc_default;
	
	global $additional_fields;
	$additional_fields = array();
	do_action("hdc_add_product_meta");	
	$fields = create_all_fields($fields, $additional_fields);		
    return $fields;
}

function hdc_print_tab_content($fields)
{
    $tabs = hdc_get_product_tabs();
    $content = hdc_get_product_tab_content();

    for ($i = 0; $i < count($tabs); $i++) {
        $tab = $tabs[$i]->slug;
		
		$hasContent = false;
		if(isset($content[$tab])){
			$hasContent = true;
        	$tab = $content[$tab];
		}

        $classes = "";
        if ($i == 0) {
            $classes = "tab_content_active";
        }
        echo '<div id="tab_' . $tabs[$i]->slug . '" class="tab_content ' . $classes . '"><h2 class="tab_heading">' . $tabs[$i]->title . '</h2>';
		if($hasContent){
        	hdc_print_tab_fields($tab, $tabs[$i]->slug, $fields);
		}
        echo '</div>';
    }
}

function hdc_print_tab_fields($tab, $tab_slug, $fields)
{

    for ($i = 0; $i < count($tab); $i++) {
		if(!isset($tab[$i]["type"])){
			return;
		}
        if ($tab[$i]["type"] == "col-1-1") {
            hdc_printField_col11($tab[$i], $tab_slug, $fields);
        } elseif ($tab[$i]["type"] == "col-1-1-1") {
            hdc_printField_col111($tab[$i], $tab_slug, $fields);
        } elseif ($tab[$i]["type"] == "content") {
            hdc_printField_content($tab[$i], $tab_slug);
        } elseif ($tab[$i]["type"] == "text") {
            hdc_printField_text($tab[$i], $tab_slug, $fields);
        } elseif ($tab[$i]["type"] == "currency") {
            hdc_printField_currency($tab[$i], $tab_slug, $fields);
        } elseif ($tab[$i]["type"] == "editor") {
            hdc_printField_editor($tab[$i], $tab_slug, $fields);
        } elseif ($tab[$i]["type"] == "float") {
            hdc_printField_float($tab[$i], $tab_slug, $fields);
        } elseif ($tab[$i]["type"] == "integer") {
            hdc_printField_integer($tab[$i], $tab_slug, $fields);
        } elseif ($tab[$i]["type"] == "select") {
            hdc_printField_select($tab[$i], $tab_slug, $fields);
        } elseif ($tab[$i]["type"] == "image") {
            hdc_printField_image($tab[$i], $tab_slug, $fields);
        } elseif ($tab[$i]["type"] == "gallery") {
            hdc_printField_gallery($tab[$i], $tab_slug, $fields);
        } elseif ($tab[$i]["type"] == "action") {
            hdc_printField_action($tab[$i], $tab_slug, $fields);
        }
    }
}

function create_all_fields($fields, $additional)
{
	if(count($additional) == 0){
		return $fields;
	}
	
	// get registered tabs
	$all_tabs = hdc_get_product_tabs();
	// only keep slugs;	
	$tabs = array();
	for($i = 0; $i < count($all_tabs); $i++){
		$tabs[$all_tabs[$i]->slug] = array();
		
	}
		
	for($i = 0; $i < count($additional); $i++){
		$tab = strtolower($additional[$i]["tab"]);
		if(isset($tabs[$tab])){
			array_push($tabs[$tab], $additional[$i]);
		}		
	}
	
	// now add elements to fields
	foreach ($tabs as $key => $v) {		
		if(count($v) > 0){	
			for($i = 0; $i < count($v); $i++){
				if(!isset($fields[$key])){					
					$fields[$key] = array();
				}
				if(isset($v[$i]["children"]) && count($v[$i]["children"]) > 0){
					for($x = 0; $x < count($v[$i]["children"]); $x++){
						$v[$i]["children"][$x]["label"] = sanitize_text_field($v[$i]["children"][$x]["label"]);
						$v[$i]["children"][$x]["name"] = sanitize_title($v[$i]["children"][$x]["name"]);
						$v[$i]["children"][$x]["type"] = sanitize_text_field($v[$i]["children"][$x]["type"]);
						echo $v[$i]["children"][$x]["required"];
						$v[$i]["children"][$x]["required"] = intval($v[$i]["children"][$x]["required"]);
					}
				} else {
					$v[$i]["label"] = sanitize_text_field($v[$i]["label"]);
					$v[$i]["name"] = sanitize_title($v[$i]["name"]);
					$v[$i]["type"] = sanitize_text_field($v[$i]["type"]);
					$v[$i]["required"] = intval($v[$i]["required"]);
				}				
				array_push($fields[$key], $v[$i]);
			}				
		}		
	}	
	return $fields;	
}