<?php
include dirname(__FILE__) . '/functions.php';

function hdc_print_scripts()
{
    wp_enqueue_style(
        'hdc_admin_style',
        plugin_dir_url(__FILE__) . '../css/hdc_style_product.css'
    );
    wp_enqueue_script(
        'hdc_admin_script',
        plugins_url('../js/hdc_admin_product.js', __FILE__),
        array('jquery', 'jquery-ui-draggable'),
        '1.0',
        true
    );

    do_action("hdc_global_enqueue"); // enqueue files that need to be on every page
    do_action("hdc_products_enqueue"); // enqueue files that only need to be on the products or orders page
}
hdc_print_scripts();

wp_nonce_field('hdc_meta_products_nonce', 'hdc_meta_products_nonce');
$fields = hdc_get_product_data(get_the_ID());
?>

<div id="main">
    <div id="header">
        <h1 id="heading_title">
            <?php
				if (isset($fields["product_name"]["value"])) {
					echo 'Editing <span id="page_title_span">' . $fields["product_name"]["value"] . '</span>';
				} else {
					echo 'Adding New Product';
				}
			?>
        </h1>
        <div id="header_actions">
            <?php
				if (isset($fields["product_name"]["value"])) {
					$permalink = get_the_permalink();
					$slug = get_post_field("post_name");
					$permaSlug = str_replace("/" . $slug, "", $permalink);
					echo '<div id = "product_slug" data-slug = "' . $slug . '" data-permalink = "' . $permaSlug . '" title = "update product slug (url)">' . $permalink . '</div>';
					echo '<a href = "' . $permalink . '" target = "_blank" id="view_product_page" data-id="view_product_page" class="button button_secondary" title="view product page">view product page</a>';
				}
			?>
            <div id="save" data-id="save-product" class="button button_primary" title="save this product">SAVE</div>
        </div>
    </div>
    <div id="add_new_product" class="content">
        <div id="product_variation_sidebar"></div>
        <div id="content_tabs">
            <div id="tab_nav_wrapper">
                <div id="tab_nav">
                    <?php hdc_print_product_tabs();?>
                </div>
            </div>
            <div id="tab_content">
                <?php hdc_print_tab_content($fields);?>
            </div>
        </div>
    </div>
</div>