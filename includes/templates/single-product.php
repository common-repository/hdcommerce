<?php
/*
	HDCommerce Product Single Product Template
	Prints the full product page
*/

hdc_cart_cookies();
get_hdc_header();

$fields = get_product_data($post->ID);
?>

<div id="hdc_wrapper">
    <div id="hdc_product_page">

        <?php if(isset($hdc_product_added)){ ?>
        <div class="hdc_notification">
            <?php global $hdc_checkout_page; ?>
            <a href="<?php echo get_the_permalink($hdc_checkout_page); ?>" class="hdc_button">View Cart</a>
            <h3>PRODUCT ADDED TO THE CART</h3>
            <div class="clear"></div>
        </div>
        <?php } ?>
        <div id="product_main">
            <div id="product_main_content">
                <?php hdc_get_product_title(); ?>
                <h3 id="product_price">
                    <?php
						if ($fields["product_sale_price"]["value"] != "" && $fields["product_sale_price"]["value"] != null){
							echo '<s>'.hdc_amount($fields["product_price"]["value"]).'</s> '.hdc_amount($fields["product_sale_price"]["value"]);
						} else {
							echo hdc_amount($fields["product_price"]["value"]);
						}
					?>
                </h3>

                <?php
					if ($hdc_disable_reviews != 1){
						hdc_show_rating();
					}
				?>
                <?php hdc_breadcrumb(); ?>

                <div id="product_short_content">
                    <?php
						// html_entity_decode is needed for newer themes and PHP versions
						echo html_entity_decode(apply_filters("the_content", $fields["short_description"]["value"]));
					?>
                </div>
                <div id="product_variations">
                    <?php
						if(isset($fields["product_variations"]["value"]) && $fields["product_variations"]["value"] != ""){
							$variations = $fields["product_variations"]["value"];
							if($variations != "" && $variations != null){
								for($i = 0; $i < count($variations); $i++){
									echo '<div class = "hdc_variation_type" data-has-selection = ""><h3 class = "variation_type">'.$variations[$i]["name"].'</h3>';
									for($x = 0; $x < count($variations[$i]["options"]); $x++){
										echo '<div class="hdc_variation" role="button" aria-pressed="false" data-variation-type="'.$variations[$i]["slug"].'" data-variation-name="'.$variations[$i]["options"][$x]["slug"].'">'.$variations[$i]["options"][$x]["name"].'</div>';
									}
									echo '</div>';
								}
							}
						} else {
							$variations = "";
						}
					?>
                </div>

                <?php
					if($fields["product_stock"]["value"] != "0"){ ?>
                <div role="button" id="add_to_cart"
                    class="<?php if ($variations == "" || $variations == null){ echo 'hdc_button_active'; } ?>">
                    Add to cart
                </div>
                <?php
					} else {
						echo 'this product is out of stock';
					}
				?>

            </div>
            <div id="product_media">
                <div id="product_featured" class="hdc_product_gallery"
                    data-hdc-gallery-url="<?php echo  wp_get_attachment_url( $fields["featured_image"]["value"] ); ?>">
                    <?php 				
						if (wp_attachment_is_image($fields["featured_image"]["value"])) { 						
							echo wp_get_attachment_image(
								$fields["featured_image"]["value"],
								"large",
								"",
								array("class" => "image_field_image")
							);
						}
					?>
                </div>
                <div id="product_gallery">
                    <?php
						if($fields["product_gallery"]["value"] != null && $fields["product_gallery"]["value"] != ""){
							$fields["product_gallery"]["value"] = explode(",", $fields["product_gallery"]["value"]);
							foreach ($fields["product_gallery"]["value"] as $attachmentId) {
								if (wp_attachment_is_image($attachmentId)) {
									$image =  wp_get_attachment_image($attachmentId, "thumbnail", "", array("class" => "img-responsive"));
									$url = wp_get_attachment_image_src($attachmentId, "full", false);
									echo '<div class = "product_gallery_image hdc_product_gallery" data-hdc-gallery-url = "'.$url[0].'" >'.$image.'</div>';
								}
							}
						}
					?>
                    <div class="clear"></div>
                </div>
            </div>
        </div>




		<div id ="main_product_content">			
			<?php
				if ($hdc_disable_reviews != 1){
					// show tabs for description and reviews
					?>
					<div id = "hdc_tabs">						
						<ul>
							<?php
								if ($fields["description"]["value"] != ""){
									echo '<li class="hdc_active_tab" data-hdc-content="hdc_full_description">Description</li>';
								}
							?>
							<li class="<?php if ($fields["description"]["value"] == "") { echo 'hdc_active_tab';} ?>" data-hdc-content="hdc_reviews">Reviews</li>	
						</ul>
						<div class="clear"></div>
						<div id = "hdc_tab_content">						
							<div class="hdc_tab" id="hdc_full_description">
								<?php 
									echo html_entity_decode(apply_filters("the_content", $fields["description"]["value"]));
								?>
							</div>
							<div class="hdc_tab" id="hdc_reviews">								
								<?php hdc_get_reviews(); ?>
							</div>
						</div>
					</div>
					<?php
				} else {
					if ($hdc_product_content != ""){
						// don't display tabs, just the description
						$hdc_product_content = html_entity_decode($hdc_product_content);
						$hdc_product_content = apply_filters("the_content", $hdc_product_content);
						echo $hdc_product_content;
					}
				}
			?>
		</div>	

    </div>
</div>

<div id="hdc_gallery">
    <div id="hdc_gallery_actions">
        <div id="hdc_gallery_prev" class="hdc_gallery_action">&larr;</div>
        <div id="hdc_gallery_next" class="hdc_gallery_action">&rarr;</div>
        <div id="hdc_gallery_close" class="hdc_gallery_action">x</div>
    </div>
    <div id="hdc_gallery_content"></div>
</div>

<form id="hdc_product_add_to_cart" name="hdc_product_add_to_cart" method="post" action="">
    <input type="hidden" name="hdc_form_submit" value="Y">
    <input type="hidden" name="hdc_product_id" value="<?php echo $post->ID; ?>">
    <input type="hidden" id="hdc_variation_name" name="hdc_variation_name" value="">
</form>


<?php get_footer(); ?>