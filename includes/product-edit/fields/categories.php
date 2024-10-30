<?php
function printProductTabCategories($tab, $tab_slug, $fields)
{
	function hdc_print_category_list($terms)
	{
		// get currently attached terms
    	$product_categories = hdc_product_edit_get_current_terms();
		echo '<div id = "product_categories" data-type = "categories" class = "hderp_input">';
		$counter = 0;
		$totalCategories = count($terms);
    	foreach ($terms as $term ) {
			$counter = $counter + 1;
			$isParent = null;
			if($term[2] == 0){
				$isParent = "hdc_category_parent";
			} else if ($term[3] != "" && $term[3] != null){
				$isParent = "hdc_category_parent_child";
			}
        	echo '<div class = "hdc_category '.$isParent.'" data-cat-id = "'.$term[1].'">';
			// check if last pos in array
			if($totalCategories != $counter){
				// if has child
				if($term[1] == $terms[$counter][2]){
					echo '<div class = "hdc_category_no_select">'.$term[0].'</div>';
				} else {
					$isChecked = "";
					foreach ($product_categories as $cat_id ) {
						if ($cat_id == $term[1]){
							$isChecked = "checked";
						}
					}
					echo '<label class = "hdc_full_label" for="term_'.$term[1].'"><div class="hdc_checkbox">
					<input type="checkbox" class = "hdc_checkbox_input hdc_category_input" id="term_'.$term[1].'" data-id = "'.$term[1].'" value="yes" name="term_'.$term[1].'" '.$isChecked.'>
					<label for="term_'.$term[1].'"></label></div>'.$term[0].'</label>';
				}
			} else {
				$isChecked = "";
				foreach ($product_categories as $cat_id ) {
					if ($cat_id == $term[1]){
						$isChecked = "checked";
					}
				}
				echo '<label class = "hdc_full_label" for="term_'.$term[1].'"><div class="hdc_checkbox">
				<input type="checkbox" class = "hdc_checkbox_input hdc_category_input" id="term_'.$term[1].'" data-id = "'.$term[1].'" value="yes" name="term_'.$term[1].'" '.$isChecked.'>
				<label for="term_'.$term[1].'"></label></div>'.$term[0].'</label>';
			}

			echo '</div>';
    	}		
		echo '</div>';
	}		
	$terms = hdc_product_edit_create_terms_list();
	hdc_print_category_list($terms);
}


function hdc_product_edit_create_terms_list()
{
    function hdc_product_edit_get_current_terms()
    {
        $terms = get_the_terms(get_the_ID(), 'hdc_products');

        // just keep the term id
        $product_categories = array();
        if (!empty($terms)) {
            foreach ($terms as $term) {
                array_push($product_categories, $term->term_id);
            }
        }
        return $product_categories;
    }

    function hdc_product_edit_get_parent_terms()
    {
        $parentsList = array();

        $terms = get_terms(array(
            'taxonomy' => 'hdc_products',
            'hide_empty' => false,
            'parent' => 0,
        ));

        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                array_push($parentsList, array($term->name, $term->term_id, $term->parent, null));
            }
        }
        return $parentsList;
    }

    function hdc_product_edit_get_terms_list($parentsList)
    {

        $termsList = array();
        // now that we have a list of the parents,
        // and their IDs, let's get the children
        $counter = 0;
        foreach ($parentsList as $termId) {
            $terms = get_terms(array(
                'taxonomy' => 'hdc_products',
                'hide_empty' => false,
                'parent' => $termId[1],
            ));

            if ($terms && !is_wp_error($terms)) {
                $counter2 = 0;
                foreach ($terms as $term) {
                    $counter2 = $counter2 + 1;
                    if ($counter2 == 1) {
                        // also push the original parent
                        array_push($termsList, array($parentsList[$counter][0], $parentsList[$counter][1], $parentsList[$counter][2]));
                    }
                    array_push($termsList, array($term->name, $term->term_id, $term->parent, null));
                    // now we need to go another layer deep.
                    // This will be three layers, or 2 children from a parent
                    $terms2 = get_terms(array(
                        'taxonomy' => 'hdc_products',
                        'hide_empty' => false,
                        'parent' => $term->term_id,
                    ));
                    if ($terms2 && !is_wp_error($terms)) {
                        foreach ($terms2 as $term2) {
                            array_push($termsList, array($term2->name, $term2->term_id, $term2->parent, "isSubChild"));
                        }
                    }
                }
                $counter = $counter + 1;
            } else {
                array_push($termsList, array($parentsList[$counter][0], $parentsList[$counter][1], $parentsList[$counter][2], null));
                $counter = $counter + 1;
            }
        }
        return $termsList;
    }

    // nonce to allow creating categories
    wp_nonce_field('hdc_meta_prod_category_nonce', 'hdc_meta_prod_category_nonce');

    // get list of parent terms
    $parentsList = hdc_product_edit_get_parent_terms();
	// get full list
    $termsList = hdc_product_edit_get_terms_list($parentsList);
	return $termsList;
}
