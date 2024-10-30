<?php
/*
	HDCommerce Product Sidebar Template
	Prints hierarchical list of categories that contain a product
*/
?>

<div id="hdc_sidebar">
    <h3>
        Categories
    </h3>
    <ul class="hdc_category_list">
        <?php
			$terms = array(
				'taxonomy' => 'hdc_products',
				'hide_empty' => true,
				'hierarchical' => true,
				'title_li'     => '',
			);
			wp_list_categories($terms);
		?>
    </ul>
</div>