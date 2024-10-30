<?php
/*
	HDCommerce Product Meta Data "Categories Tab"
	Prints list off all product categories for selection
	NOTE: Only works "3" categories deep. Parent -> Child -> Child
	Contains:
		@hdc_category
*/
?>

<div class = "hdc_tab" id="hdc_categories">
	<h2>Product categories</h2>

	<?php
		// create nonce for creating a new category
		wp_nonce_field('hdc_meta_prod_category_nonce', 'hdc_meta_prod_category_nonce');

		$terms = get_the_terms( get_the_ID(), 'hdc_products' );
		$product_categories = array();
		if(!empty($terms)){
			foreach ( $terms as $term ) {
				array_push($product_categories, $term->term_id);
			}
		}

		// first, grab a list of parent categories
		// and store in $parentsList
		$terms = get_terms(array(
			'taxonomy' => 'hdc_products',
			'hide_empty' => false,
			'parent' => 0
		));

		$parentsList = array();
		$termsList = array();

		if ($terms && ! is_wp_error($terms ) ) {
    		foreach ($terms as $term ) {
				 array_push($parentsList, array($term->name, $term->term_id, $term->parent, null));
    		}
		}

		// now that we have a list of the parents,
		// and their IDs, let's get the children
		$counter = 0;
		foreach ($parentsList as $termId ) {
			$terms = get_terms(array(
				'taxonomy' => 'hdc_products',
				'hide_empty' => false,
				'parent' => $termId[1]
			));

			if ($terms && ! is_wp_error($terms ) ) {
				$counter2 = 0;
				foreach ($terms as $term ) {
					 $counter2 = $counter2 + 1;
					 if ($counter2 == 1){
						 // also push the original parent
						 array_push($termsList, array($parentsList[$counter][0],$parentsList[$counter][1],$parentsList[$counter][2]));
					 }
					 array_push($termsList, array($term->name, $term->term_id, $term->parent, null));
					// now we need to go another layer deep.
					// This will be three layers, or 2 children from a parent
					$terms2 = get_terms(array(
						'taxonomy' => 'hdc_products',
						'hide_empty' => false,
						'parent' => $term->term_id
					));
					if ($terms2 && ! is_wp_error($terms ) ) {
						foreach ($terms2 as $term2 ) {
							 array_push($termsList, array($term2->name, $term2->term_id, $term2->parent, "isSubChild"));
						}
					}
				}
				$counter = $counter + 1;
			} else {
				array_push($termsList, array($parentsList[$counter][0],$parentsList[$counter][1],$parentsList[$counter][2], null));
				$counter = $counter + 1;
			}
		}
?>

	<div class = "one_half">
		<div class="hdc_input-effect">
			<div class = "hdc_input_notification"><span></span>Press "ENTER" to add the category</div>
			<input type="text" class="hdc_input" id="addNewCategory" value="">
			<label for="addNewCategory">Create new category <a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>If you need to create a child category, then it's best to use the default WordPress category page located under <strong>Products</strong> -> <strong>Categories</strong>.</span></span></a></label>
			<span class="focus-border"></span>
		</div>
		&nbsp;
	</div>
	<div class = "one_half last">
		<select  class="hdc_select" id = "addNewCategoryChild">
			<option value="hide">Select Parent Category</option>
			<option value="0">A Main Category</option>
			<?php
				foreach ($termsList as $term ) {
					echo '<option value = "'.$term[1].'">'.$term[0].'</option>';
				}
			?>
		</select>
	</div>
	<div class = "clear"></div>
	<br/>

	<div id = "hdc_category_list">
	<?php
		// now print out the array into an ordered list
		$counter = 0;
		$totalCategories = count($termsList);
    	foreach ($termsList as $term ) {
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
				if($term[1] == $termsList[$counter][2]){
					echo '<div class = "hdc_category_no_select">'.$term[0].'</div>';
				} else {
					$isChecked = "";
					foreach ($product_categories as $cat_id ) {
						if ($cat_id == $term[1]){
							$isChecked = "checked";
						}
					}
					echo '<label class = "hdc_full_label" for="term_'.$term[1].'"><div class="hdc-options-check"><input type="checkbox" id="term_'.$term[1].'" value="yes" name="term_'.$term[1].'" '.$isChecked.'><label for="term_'.$term[1].'"></label></div>'.$term[0].'</label>';
				}
			} else {
				$isChecked = "";
				foreach ($product_categories as $cat_id ) {
					if ($cat_id == $term[1]){
						$isChecked = "checked";
					}
				}
				echo '<label class = "hdc_full_label" for="term_'.$term[1].'"><div class="hdc-options-check"><input type="checkbox" id="term_'.$term[1].'" value="yes" name="term_'.$term[1].'" '.$isChecked.'><label for="term_'.$term[1].'"></label></div>'.$term[0].'</label>';
			}

			echo '</div>';
    	}
	?>
	</div>
</div>