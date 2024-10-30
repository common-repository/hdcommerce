<?php
/*
	HDCommerce Settings Extra Tab
	Contains:
		@hdc_tax_inclusive,
		@hdc_tax_billing,
		@hdc_tax_chart,
*/
?>


<div class="hdc_tab" id="hdc_settings_advanced">


    <h3>❤ HDCommerce <a class="hdc_tooltip">?<span class="hdc_tooltip_line"
                style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>Enabling
                    this is 100% optional. If enabled, then a discrete <em>powered by HDCommerce</em> link will appear
                    underneath the cart and at the bottom of sent HDCommerce emails.</span></span></a>
        <h3>
            <div class="hdc_setting_row" id="hdc_love">
                <label class="non-block" for="hdc_i_love_hdcommerce">I Support HDCommerce</label>
                <div class="hdc-options-check"><input type="checkbox" id="hdc_i_love_hdcommerce" value="yes"
                        name="hdc_i_love_hdcommerce" <?php if($hdc_i_love_hdcommerce == 1) {echo "checked";} ?> /><label
                        for="hdc_i_love_hdcommerce"></label></div>
            </div>

            <h3>Custom Category Image Size</h3>
            <div class="hdc_setting_row">
                <div class="hdc_row">
                    <div class="one_half">
                        <div class="hdc_input-effect">
                            <input
                                class="hdc_input <?php if($hdc_featured_image_w != null && $hdc_featured_image_w != "") {echo 'has-content';} ?>"
                                id="hdc_featured_image_w" name="hdc_featured_image_w" type="text"
                                value="<?php echo $hdc_featured_image_w; ?>">
                            <label for="hdc_featured_image_w">Width</label>
                            <span class="focus-border"></span>
                        </div>
                    </div>
                    <div class="one_half last">
                        <div class="hdc_input-effect">
                            <input
                                class="hdc_input <?php if($hdc_featured_image_h != null && $hdc_featured_image_h != "") {echo 'has-content';} ?>"
                                id="hdc_featured_image_h" name="hdc_featured_image_h" type="text"
                                value="<?php echo $hdc_featured_image_h; ?>">
                            <label for="hdc_featured_image_h">Height</label>
                            <span class="focus-border"></span>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>


            <h3>Disable Reviews <a class="hdc_tooltip">?<span class="hdc_tooltip_line"
                        style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span
                        class="hdc_tooltip_content"><span>Enabling this will remove reviews from all
                            products.</span></span></a>
                <h3>
                    <div class="hdc_setting_row" id="hdc_love">
                        <label class="non-block" for="hdc_disable_reviews">Disable Product Reviews</label>
                        <div class="hdc-options-check"><input type="checkbox" id="hdc_disable_reviews" value="yes"
                                name="hdc_disable_reviews"
                                <?php if($hdc_disable_reviews == 1) {echo "checked";} ?> /><label
                                for="hdc_disable_reviews"></label></div>
                    </div>


                    <h3>Product Headings <a class="hdc_tooltip">?<span class="hdc_tooltip_line"
                                style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span
                                class="hdc_tooltip_content"><span>Depending on your theme, you may want to change what
                                    heading product titles use, or remove them altogether if your theme already displays
                                    the titles in a header.</span></span></a></h3>
                    <div class="hdc_setting_row">
                        <div class="hdc_row">
                            <select class="hdc_select" id="hdc_product_headings" name="hdc_product_headings">
                                <option val="H1" <?php if($hdc_product_headings == "H1") {echo 'selected';} ?>>H1
                                </option>
                                <option val="H2"
                                    <?php if($hdc_product_headings == null || $hdc_product_headings == "" || $hdc_product_headings == "H2") {echo 'selected';} ?>>
                                    H2</option>
                                <option val="H3" <?php if($hdc_product_headings == "H3") {echo 'selected';} ?>>H3
                                </option>
                                <option val="Do not display"
                                    <?php if($hdc_product_headings == "Do not display") {echo 'selected';} ?>>Do not
                                    display</option>
                            </select>

                        </div>
                    </div>

                    <h3>Default Product View Type / Columns <a class="hdc_tooltip">?<span class="hdc_tooltip_line"
                                style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span
                                class="hdc_tooltip_content"><span>By default, the shop and category pages products are
                                    displayed in three columns.</span></span></a></h3>
                    <div class="hdc_setting_row">
                        <div class="hdc_row">
                            <select class="hdc_select" id="hdc_product_view_type" name="hdc_product_view_type">
                                <option val="1" <?php if($hdc_product_view_type == "1") {echo 'selected';} ?>>1</option>
                                <option val="2" <?php if($hdc_product_view_type == "2") {echo 'selected';} ?>>2</option>
                                <option val="3"
                                    <?php if($hdc_product_view_type == null || $hdc_product_view_type == "" || $hdc_product_view_type == "3") {echo 'selected';} ?>>
                                    3</option>
                                <option val="4" <?php if($hdc_product_view_type == "4") {echo 'selected';} ?>>4</option>
                            </select>

                        </div>
                    </div>

</div>