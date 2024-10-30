<?php
/*
    HDCommerce Coupons Page Content
    Prints the coupon admin information
*/
?>

<?php
    $percent_discount = sanitize_text_field(get_post_meta($object->ID, 'hdc_coupon_percent_discount', true));
    $coupon_amount = number_format(floatval(get_post_meta($object->ID, 'hdc_coupon_amount', true)), 2);
	$coupon_minimum_amount = number_format(floatval(get_post_meta($object->ID, 'hdc_coupon_minimum_amount', true)), 2);
    $expire_date = sanitize_text_field(get_post_meta($object->ID, 'hdc_coupon_expire_date', true));
    $restrict_categories = sanitize_text_field(get_post_meta($object->ID, 'hdc_coupon_restrict_categories', true));
    $restrict_products = sanitize_text_field(get_post_meta($object->ID, 'hdc_coupon_restrict_products', true));
?>

<style>
    .hndle,
    .handlediv {
        display: none !important;
    }

    .postbox {
        background: transparent !important;
        border: none !important
    }

    .hdc_setting_row {
        margin: 0 0 40px 0;
        border: 1px solid #ccc;
        padding: 22px;
        background: #fff;
        display: grid;
        grid-gap: 2rem;
    }
	
    .col4 {
        grid-template-columns: 1fr 1fr 1fr 1fr;
    }	

    .col3 {
        grid-template-columns: 1fr 1fr 1fr;
    }

    .col2 {

        grid-template-columns: 1fr 1fr
    }

    .hdc_setting_row label {
        margin-bottom: 0 !important;
    }

    .hdc_settings_row div label {
        font-weight: bold !important;
        font-size: 1rem
    }

    .hdc-options-check {
        font-size: 24px;
        margin-top: 1rem
    }

    .hdc-options-check input[type=checkbox] {
        position: absolute;
        opacity: 0;
    }

    .hdc_setting_row label {
        display: block;
        margin-bottom: 0px;
    }

    .hdc-options-check label {
        width: 2em;
        height: 1em;
        position: relative;
        cursor: pointer;
    }

    .hdc-options-check label:before {
        content: '';
        position: absolute;
        width: 2em;
        height: 1em;
        left: 0.1em;
        transition: background 0.1s ease;
        background: #F25F5C;
        border-radius: 50px;
        box-shadow: inset 0px 1px 1px rgba(171, 66, 63, 0.5);
    }

    .hdc-options-check label:after {
        content: '';
        position: absolute;
        width: 1em;
        height: 1em;
        border-radius: 50px;
        left: 0;
        transition: all 0.2s ease;
        box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.3);
        background: #efefef;
        animation: hdc_switch-off .2s ease-out;
        z-index: 2;
    }

    .hdc-options-check input[type=checkbox]:checked+label:before {
        background: #72c474;
        box-shadow: inset 0px 1px 1px rgba(84, 152, 140, 0.5);
    }

    .hdc-options-check input[type=checkbox]:checked+label:after {
        animation: hdc_switch-on .2s ease-out;
        left: 1.1em;
        background: #efefef;
    }

    .hdc_input-effect input {
        border: 0;
        padding: 4px 0;
        border-bottom: 1px solid #ccc;
        background-color: transparent;
    }

    .hdc_input {
        padding: 8px 0 0px !important;
        font-size: 2em;
        width: 100%;
        max-width: fit-content;
        border: none !important;
        box-shadow: none !important;
        color: #2d2d2d;
        border-bottom: 1px dashed #aaa !important;
    }

    #hdc_product_wrapper h2 {
        font-size: 1.8rem;
        font-weight: bold
    }


    .hdc_full_label {
        display: block;
        width: calc(100% - 44px);
        padding: 12px 22px;
    }

    .hdc_category {
        color: #222;
        background: #ddd;
        font-size: 14px;
        border-bottom: 1px solid #395559;
    }
	
	.hdc_category_no_select {
		padding: 12px 22px;
	}

    .hdc_category.hdc_category_parent {
        background: #eee;
        border-bottom: 1px solid #4b6764;
        text-indent: 0;
    }

    .hdc_full_label:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .hdc_full_label {
        align-items: center;
        display: grid !important;
        grid-gap: 1rem;
        grid-template-columns: max-content 1fr;
    }

    .hdc_full_label .hdc-options-check {
        margin: 0 !important
    }


    .hdc_tooltip {
        display: inline-block;
        position: relative;
        top: -4px;
        color: #fff;
        cursor: pointer;
        width: 16px;
        height: 16px;
        line-height: 15px;
        text-align: center;
        background: #20699c;
        border-radius: 50%;
        font-weight: 400;
        font-size: 12px;
        z-index: 99;

    }

    .hdc_tooltip_line {
        position: absolute;
        height: 2px;
        width: 100%;
        bottom: 0;
        left: 0;
        background: linear-gradient(40deg, #2812da 0%, #e82aa3 100%);
        transform: scale3d(0, 1, 1);
        transform-origin: 100% 50%;
    }

    .hdc_tooltip_content {
        position: absolute;
        bottom: calc(100% + 0.8rem);
        font-size: 0.85rem;
        width: 260px;
        left: calc(50% - 32px);
        line-height: 1.4;
        margin-left: -130px;
        background: #20699c;
        border-radius: 10px;
        box-shadow: 0 -1px 10px rgba(72, 72, 72, 0.025), 0 10px 20px rgba(72, 72, 72, 0.05);
        color: #fff;
        padding: 2rem;
        text-align: left;
        pointer-events: none;
        opacity: 0;
        transform: translate3d(0, 30px, 0);
        transition: all 0.3s cubic-bezier(0.1, 1, 0.9, 1);
        transition-property: opacity, transform;
    }

    .hdc_tooltip:hover .hdc_tooltip_content {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }

    .hdc_tooltip_content::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -10px;
        display: block;
        border-style: solid;
        border-width: 10px 10px 0 10px;
        border-color: #20699c transparent transparent transparent;
    }

    .hdc_tooltip_content span {
        display: block;
    }
</style>

<input type="hidden" name="hdc_save" value="Y" />

<h2>General</h2>
<div class="hdc_setting_row col4">
    <div>
        <label style="font-size: 1rem; font-weight: bold" for="hdc_coupon_percent_discount">Percent Discount</label>
        <div class="hdc-options-check"><input type="checkbox" id="hdc_coupon_percent_discount" value="yes"
                name="hdc_coupon_percent_discount" <?php if ($percent_discount === "yes") {echo "checked";}?>><label
                for="hdc_coupon_percent_discount"></label>
        </div>
    </div>
	
    <div>
        <label for="hdc_coupon_amount" style="font-size: 1rem; font-weight: bold"><span
                id="hdc_coupon_amount_symbol"><?php if ($percent_discount != "yes") {echo hdc_get_currency_symbol();} else {echo "%";}?></span>
            Amount
        </label>
        <input class="hdc_input" id="hdc_coupon_amount" name="hdc_coupon_amount" type="number" step=".01"
            value="<?php echo $coupon_amount; ?>">
    </div>	
	
    <div>
        <label for="hdc_coupon_minimum_amount" style="font-size: 1rem; font-weight: bold">			
            Minimum Cart Amount <a
                class="hdc_tooltip">?<span class="hdc_tooltip_line"
                    style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>The minimum amount that needs to be in the cart for this coupon to be valid. Example: if this is set to 20, then the coupon will only be valid if at least <?php echo hdc_get_currency_symbol(); ?>20 is in the cart.</span></span></a>
        </label>
        <input class="hdc_input" id="hdc_coupon_minimum_amount" name="hdc_coupon_minimum_amount" type="number" step=".01"
            value="<?php echo $coupon_minimum_amount; ?>">
    </div>	
	
    <div>
        <label for="hdc_coupon_expire_date" style="font-size: 1rem; font-weight: bold">Expiry Date <a
                class="hdc_tooltip">?<span class="hdc_tooltip_line"
                    style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>This is
                        the date that the coupon is no longer valid. So selecting January 1st means that as of midnight
                        January 1st, the coupon will no longer be valid.</span></span></a></label>
        <input class="hdc_input" id="hdc_coupon_expire_date" name="hdc_coupon_expire_date" type="date" min="0001-01-01"
            max="9999-12-31" value="<?php echo $expire_date; ?>">
    </div>
</div>

<hr />

<h2>Restrictions</h2>
<div class="hdc_setting_row col2">
    <div id="restrict_cats">
        <label style="font-size: 1rem; font-weight: bold">Category</label>
        <p>
            This coupon will only be valid if a product from a selected category is in the cart
        </p>
        <input type="hidden" id="hdc_coupon_restrict_categories" name="hdc_coupon_restrict_categories"
            value="<?php echo $restrict_categories; ?>">
        <?php hdc_coupons_get_category_list($restrict_categories);?>
    </div>
    <div id="restrict_prods">
        <label style="font-size: 1rem; font-weight: bold">Products</label>
        <p>
            This coupon will only be valid if a selected product is in the cart
        </p>
        <input type="hidden" id="hdc_coupon_restrict_products" name="hdc_coupon_restrict_products"
            value="<?php echo $restrict_products; ?>" />
        <?php
            // WP_Query arguments
            $args = array(
                'post_type' => array('hdc_product'),
            );

            // The Query
            $query = new WP_Query($args);

            // The Loop
            if ($query->have_posts()) {
                $restrict_products = explode(",", $restrict_products);
                while ($query->have_posts()) {
                    $query->the_post();
                    $isChecked = "";
                    if (in_array(get_the_ID(), $restrict_products)) {
                        $isChecked = "checked";
                    }
                    echo '<div class = "hdc_category hdc_category_parent"><label class = "hdc_full_label" for="prod_' . get_the_ID() . '"><div class="hdc-options-check"><input type="checkbox" data-prod-ID = "' . get_the_ID() . '" id="prod_' . get_the_ID() . '" value="yes" name="prod_' . get_the_ID() . '" ' . $isChecked . '><label for="prod_' . get_the_ID() . '"></label></div>' . get_the_title() . '</label></div>';
                }
            }
            wp_reset_postdata();
        ?>
    </div>
</div>

<?php
function hdc_coupons_get_category_list($restrict_categories)
{
    // TODO: Clean this up.. a lot
    $product_categories = explode(",", $restrict_categories);

    // first, grab a list of parent categories
    // and store in $parentsList
    $terms = get_terms(array(
        'taxonomy' => 'hdc_products',
        'hide_empty' => false,
        'parent' => 0,
    ));

    $parentsList = array();
    $termsList = array();

    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            array_push($parentsList, array($term->name, $term->term_id, $term->parent, null));
        }
    }

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

    // now print out the array into an ordered list
    $counter = 0;
    $totalCategories = count($termsList);

    foreach ($termsList as $term) {
        $counter = $counter + 1;
        $isParent = null;
        if ($term[2] == 0) {
            $isParent = "hdc_category_parent";
        } else if ($term[3] != "" && $term[3] != null) {
            $isParent = "hdc_category_parent_child";
        }
        echo '<div class = "hdc_category ' . $isParent . '" data-cat-id = "' . $term[1] . '">';
        // check if last pos in array
        if ($totalCategories != $counter) {
            // if has child
            if ($term[1] == $termsList[$counter][2]) {
                echo '<div class = "hdc_category_no_select">' . $term[0] . '</div>';
            } else {
                $isChecked = "";
                foreach ($product_categories as $cat_id) {
                    if ($cat_id == $term[1]) {
                        $isChecked = "checked";
                    }
                }
                echo '<label class = "hdc_full_label" for="term_' . $term[1] . '"><div class="hdc-options-check"><input type="checkbox" data-cat-ID = "' . $term[1] . '"  id="term_' . $term[1] . '" value="yes" name="term_' . $term[1] . '" ' . $isChecked . '><label for="term_' . $term[1] . '"></label></div>' . $term[0] . '</label>';
            }
        } else {
            $isChecked = "";
            foreach ($product_categories as $cat_id) {
                if ($cat_id == $term[1]) {
                    $isChecked = "checked";
                }
            }
            echo '<label class = "hdc_full_label" for="term_' . $term[1] . '"><div class="hdc-options-check"><input type="checkbox" data-cat-ID = "' . $term[1] . '" id="term_' . $term[1] . '" value="yes" name="term_' . $term[1] . '" ' . $isChecked . '><label for="term_' . $term[1] . '"></label></div>' . $term[0] . '</label>';
        }

        echo '</div>';
    }
}
?>

<script>
    let percent_discount = document.getElementById("hdc_coupon_percent_discount");
    let amount_symbol = ["<?php echo hdc_amount(); ?>", document.getElementById("hdc_coupon_amount_symbol")];

    percent_discount.addEventListener("change", function() {
        if (this.checked) {
            amount_symbol[1].innerHTML = "%";
        } else {
            amount_symbol[1].innerHTML = amount_symbol[0];
        }
    })

    jQuery("#restrict_cats").on("change", ".hdc_full_label input", function() {
        let ID = this.getAttribute("data-cat-ID");
        let cats = jQuery("#hdc_coupon_restrict_categories").val();
        cats = cats.split(",");
        if (this.checked) {
            if (cats[0] == "") {
                cats[0] = ID;
            } else {
                cats.push(ID);
            }
        } else {
            let index = cats.indexOf(ID);
            if (index > -1) {
                cats.splice(index, 1);
            }
        }
        cats = cats.join(",");
        jQuery("#hdc_coupon_restrict_categories").val(cats)
    });

    jQuery("#restrict_prods").on("change", ".hdc_full_label input", function() {
        let ID = this.getAttribute("data-prod-ID");
        let cats = jQuery("#hdc_coupon_restrict_products").val();
        cats = cats.split(",");
        if (this.checked) {
            if (cats[0] == "") {
                cats[0] = ID;
            } else {
                cats.push(ID);
            }
        } else {
            let index = cats.indexOf(ID);
            if (index > -1) {
                cats.splice(index, 1);
            }
        }
        cats = cats.join(",");
        jQuery("#hdc_coupon_restrict_products").val(cats)
    });
</script>