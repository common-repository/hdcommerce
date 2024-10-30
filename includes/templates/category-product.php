<?php
/*
	HDCommerce Product Category page
	Prints list of all products within the current category
*/

get_hdc_header();

$hdc_store_name = get_option('hdc_store_name');
?>

<div id="hdc_wrapper">
    <div id="hdc_category_page">

		<div id = "hdc_shop_category_header">		
        	<h2 id="hdc_category_page_title">Browsing <?php single_term_title(); ?></h2>
			<div id = "hdc_category_options">				
				<div id = "hdc_category_order"><!-- shop page only --></div>
				<div id = "hdc_category_style"><?php hdc_print_category_style(); ?></div>
			</div>
		</div>		
		
        <?php
            // TODO: Add a "no sidebar" options
			hdc_sidebar(); // include the sidebar to list categories
		?>

        <div id="hdc_products" class="hdc_product_columns<?php echo $hdc_product_view_type; ?>">

            <?php							
				if (have_posts() ) :
					while (have_posts() ) : the_post();
						// get product meta data
                        $hdc_product_name = get_the_title($post->ID);                        
                        $hdc_product = get_product_data($post->ID);

						$hdc_product_price = $hdc_product["product_price"]["value"];
						$hdc_product_sale_price = $hdc_product["product_sale_price"]["value"];
						$hdc_product_short_content = html_entity_decode(apply_filters("the_content", $hdc_product["short_description"]["value"]));
						$hdc_product_permalink = get_the_permalink($post->ID);
			?>

            <div class="hdc_category_product">
                <a href="<?php echo $hdc_product_permalink; ?>" class="hdc_product_link">
                    <div class="hdc_category_product_featured_image">
                        <?php
							if (wp_attachment_is_image($hdc_product["featured_image"]["value"])) { 						
								echo wp_get_attachment_image(
									$hdc_product["featured_image"]["value"],
									"large",
									"",
									array("class" => "image_field_image")
								);
							}	
						?>
                    </div>
                    <div class="hdc_category_product_inner">

                        <h3 class="hdc_category_product_title">
                            <?php echo $hdc_product_name ;?>
                        </h3>
                        <h4 class="hdc_category_product_price">
                            <?php
								if ($hdc_product_sale_price != "" && $hdc_product_sale_price != null){
									echo '<s>'.hdc_amount($hdc_product_price).'</s> '.hdc_amount($hdc_product_sale_price);
								} else {
									echo hdc_amount($hdc_product_price);
								}
							?>
                        </h4>
                        <div class="hdc_category_product_short_description">
                            <?php echo $hdc_product_short_content ?>
                        </div>
                        <div class="hdc_view_product_button">
                            view product
                        </div>
                    </div>
                    <?php
						if($hdc_product_view_type == 1){
							echo '<div class = "clear"></div>';
						}
					?>
                </a>
            </div>

            <?php
			endwhile;
				the_posts_pagination(
					array(
						'prev_text' => 'PREV',
						'next_text' => 'NEXT',
					)
				);
				else :
					echo '<h3>There are currently no products available in this category</h3>';
			endif;
			?>



        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="clear"></div>

<?php get_footer(); ?>