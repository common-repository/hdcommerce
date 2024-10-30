<?php
/*
	HDCommerce options and settings page
	Creates options page for global settings
*/

/* Create the option page
------------------------------------------------------- */

function hdc_create_addons_page()
{
    if (current_user_can('edit_others_pages')) {

        wp_enqueue_style(
            'hdc_admin_style',
            plugin_dir_url(__FILE__) . '/css/hdc_admin_style.css'
        );
        wp_enqueue_script(
            'hdc_admin_script',
            plugins_url('/js/hdc_admin.js', __FILE__),
            array('jquery', 'jquery-ui-draggable'),
            '1.0',
            true
        );

        ?>

<div id="hdc_wrapper">
    <h1>HDCommerce Addons</h1>
    <p>
        HDCommerce is a new plugin to the market, so we are currently short on addons. If you are a developer and would
        like to include your addon plugin to this page, please message me <a
            href="https://hdselling.com/addons/submit-a-plugin?utm_source=HDCommerce&utm_medium=addonsPage">here</a>, or
        <a href="https://hdselling.com/docs?utm_source=HDCommerce&utm_medium=addonsPage">click here</a> to learn how to
        create your own addons.
    </p>
    <p>
        Plugins marked as "verified" are plugins that have either been developed by <a
            href="https://harmonicdesign.ca?utm_source=HDCommerce&utm_medium=addonsPage">Harmonic Design</a>, or have
        been audited and approved by Harmonic Design.
    </p>
    <p>
        <strong>All prices are listed in Canadian Dollars (CAD)</strong>
    </p>
    <div id="hdc_settings">
        <?php
			// TODO! convert to ajax for faster initial page load
			$data = wp_remote_get("https://hdselling.com/addons/json/");

			if (is_array($data)) {
				$data = $data["body"];
				$data = stripslashes(html_entity_decode($data));
				$data = json_decode($data);

				foreach ($data as $value) {
					$title = sanitize_text_field($value[0]);
					$thumb = sanitize_text_field($value[1]);
					$description = wp_kses_post($value[2]);
					$url = sanitize_text_field($value[3]);
					$category = $value[4];
					$verified = sanitize_text_field($value[5]);
					$price = sanitize_text_field($value[6]);
        ?>


        <div class="hdc_addon_item">
            <div class="one_third hdc_addon_item_image">
                <img src="<?php echo $thumb; ?>" alt="<?php echo $title; ?>">
            </div>
            <div class="two_third last">
                <h2><?php echo $title;if ($verified == true) {echo '<span class = "hdc_verified">verified</span>';} ?>
                    <span class="hdc_price"><?php echo $price; ?></span></h2>
                <p>Categories:
                    <?php
						foreach ($category as $value) {
                    		echo '<strong>' . $value . '</strong> ';
                		}
                	?>
                </p>
                <?php echo apply_filters('the_content', $description); ?>
                <a href="<?php echo $url; ?>?utm_source=HDCommerce&utm_medium=addonsPage"
                    class="hdc_button hdc_reverse">View Addon Page</a>
            </div>
            <div class="clear"></div>
        </div>

        <?php }
			} else {
				echo '<h2>There was an error loading the available addons. Please refresh this page to try again.</h2>';
			}
        ?>

    </div>
</div>


<?php }
}
?>