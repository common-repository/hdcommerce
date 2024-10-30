<?php
/*
	HDCommerce Shipping Functions
	Functions that help calculate shipping costs,
	as well as determine the shipping method
*/

/* Settings page: Display Shipping Methods
------------------------------------------------------- */
function hdc_settings_shipping()
{
    global $hdc_shipping;
    global $hdc_shipping_methods;

    $hdc_shipping = explode("|", $hdc_shipping);
    // read in array
    // method slug, path to logo, settings function, cart function
    // NOTE: The slug should use underscore_ instead of hyphen-
    //

    $hdc_shipping_methods = array(
        array("flat", plugin_dir_url(__FILE__) . "/../../images/flat-rate.jpg", "hdc_settings_include_shipping_flat_rate", "hdc_shipping_flat_rate_includes"),
        array("usps", plugin_dir_url(__FILE__) . "/../../images/usps.jpg", "hdc_settings_include_shipping_usps", "hdc_shipping_usps_includes"),
        array("canada_post", plugin_dir_url(__FILE__) . "/../../images/canada-post.jpg", "hdc_settings_include_shipping_canada_post", "hdc_shipping_canada_post_includes"),
    );
    do_action('hdc_settings_shipping'); // grab any extra shipping methods from plugins;
    // display the "Select Shipping Method" items
    $counter = 0;
    foreach ($hdc_shipping_methods as $value) {
        $counter = $counter + 1;

        if ($counter != 3) {
            ?>

<div class="one_third">
    <div id="settings_shipping_<?php echo $value[0]; ?>" data-function="<?php echo $value[3]; ?>"
        data-select="<?php echo $value[0]; ?>"
        class="hdc_setting_image_select <?php if ($hdc_shipping[0] == $value[0]) {echo "hdc_selected";}?>"><img
            src="<?php echo $value[1]; ?>" alt="<?php echo $value[0]; ?>" /></div>
</div>
<?php } else {?>
<div class="one_third last">
    <div id="settings_shipping_<?php echo $value[0]; ?>" data-function="<?php echo $value[3]; ?>"
        data-select="<?php echo $value[0]; ?>"
        class="hdc_setting_image_select <?php if ($hdc_shipping[0] == $value[0]) {echo "hdc_selected";}?>"><img
            src="<?php echo $value[1]; ?>" alt="<?php echo $value[0]; ?>" /></div>
</div>
<div class="clear"></div>

<?php
$counter = 0;
        }
    }

    echo '<div class="clear"></div>
	<br/>';

    // Display the shipping method options/settings
    foreach ($hdc_shipping_methods as $value) {?>
<div id="hdc_<?php echo $value[0]; ?>"
    class="hdc_shipping <?php if ($hdc_shipping[0] == $value[0]) {echo "hdc_active_select ";}?>">

    <?php
$value[2]();
        echo '</div>';
    }
}

/* Get the shipping method on the cart
------------------------------------------------------- */
function get_hdc_shipping_methods()
{

    $hdc_shipping = sanitize_text_field(get_option("hdc_shipping"));
    $hdc_shipping = explode("|", $hdc_shipping);

    $hdc_shipping_class = sanitize_text_field($_POST['shipping_class']);
    $hdc_quantity = intval($_POST['quantity']);
    $hdc_weight = floatval($_POST['weight']);
    $hdc_width = floatval($_POST['width']);
    $hdc_height = floatval($_POST['height']);
    $hdc_length = floatval($_POST['length']);
    $hdc_t_address = sanitize_text_field($_POST['t_address']);
    $hdc_t_city = sanitize_text_field($_POST['t_city']);
    $hdc_t_state = sanitize_text_field($_POST['t_state']);
    $hdc_t_country = sanitize_text_field($_POST['t_country']);
    $hdc_t_zip = sanitize_text_field($_POST['t_zip']);
    $hdc_f_address = sanitize_text_field(get_option("hdc_store_address"));
    $hdc_f_city = sanitize_text_field(get_option("hdc_store_city"));
    $hdc_f_state = sanitize_text_field(get_option("hdc_store_state"));
    $hdc_f_country = sanitize_text_field(get_option("hdc_store_country"));
    $hdc_f_zip = sanitize_text_field(get_option("hdc_store_zip"));

    if ($hdc_shipping[1] != "" && $hdc_shipping[1] != null) {
        $hdc_shipping[1]($hdc_shipping_class, $hdc_quantity, $hdc_weight, $hdc_width, $hdc_height, $hdc_length, $hdc_t_address, $hdc_t_city, $hdc_t_state, $hdc_t_country, $hdc_t_zip, $hdc_f_address, $hdc_f_city, $hdc_f_state, $hdc_f_country, $hdc_f_zip);
    }

    die();
}
add_action('wp_ajax_nopriv_get_hdc_shipping_methods', 'get_hdc_shipping_methods');
add_action('wp_ajax_get_hdc_shipping_methods', 'get_hdc_shipping_methods');

/* Prints out the available shipping methods
 * for a given provider
------------------------------------------------------- */
function hdc_get_shipping_methods($slug, $hdc_methods, $hdc_shipping_usps_methods)
{

    $hdc_methods_length = sizeof($hdc_methods);
    $hdc_methods_length = (int) ($hdc_methods_length / 2) + 1;

    echo '<div id = "hdc_' . $slug . '_methods">';
    $counter = 0;
    foreach ($hdc_methods as $value) {
        if ($counter == 0) {
            echo '<div class = "one_half">';
        } else if ($counter == $hdc_methods_length) {
            echo '</div>
			<div class = "one_half last">';
        }
        $counter = $counter + 1;
        ?>
    <label for="hdc_<?php echo $slug; ?>_<?php echo $counter; ?>" title="<?php echo $value[0]; ?>">
        <?php
			if (strlen($value[0]) > 50) {
				$str = substr($value[0], 0, 47) . '...';
				echo $str;
			} else {
				echo $value[0];
			}
        ?>
        <div class="hdc-options-check" data-method="<?php echo $value[1]; ?>">
            <input type="checkbox" id="hdc_<?php echo $slug; ?>_<?php echo $counter; ?>" value="yes"
                <?php if (in_array($value[1], $hdc_shipping_usps_methods)) {echo 'checked';}?>>
            <label for="hdc_<?php echo $slug; ?>_<?php echo $counter; ?>"></label>
        </div>
    </label>
    <div class="clear"></div>
    <?php }
    echo '</div><div class="clear"></div></div>';

    return;
}

/* HDComemrce Default shipping methods
------------------------------------------------------- */

/* Method options to read/save */
// Canada Post: read and save settings

// Flat Rate: read and save settings
function hdc_settings_flat_rate_read_save()
{
    global $hdc_options;
    $hdc_options->hdc_shipping_flat_rate_shipping_cart = array("hdc_shipping_flat_rate_shipping_cart", "float");
    $hdc_options->hdc_shipping_flat_rate_shipping_class_a = array("hdc_shipping_flat_rate_shipping_class_a", "float");
    $hdc_options->hdc_shipping_flat_rate_shipping_class_b = array("hdc_shipping_flat_rate_shipping_class_b", "float");
    $hdc_options->hdc_shipping_flat_rate_shipping_class_c = array("hdc_shipping_flat_rate_shipping_class_c", "float");
    $hdc_options->hdc_shipping_flat_rate_shipping_class_d = array("hdc_shipping_flat_rate_shipping_class_d", "float");
    $hdc_options->hdc_shipping_flat_rate_shipping_class_e = array("hdc_shipping_flat_rate_shipping_class_e", "float");
}
add_action('hdc_settings_fields', 'hdc_settings_flat_rate_read_save');

// USPS: read and save settings
function hdc_settings_usps_read_save()
{
    global $hdc_options;
    $hdc_options->hdc_shipping_usps_methods = array("hdc_shipping_usps_methods", "text");
    $hdc_options->hdc_shipping_usps_user = array("hdc_shipping_usps_user", "text");
    $hdc_options->hdc_shipping_usps_international = array("hdc_shipping_usps_international", "float");
}
add_action('hdc_settings_fields', 'hdc_settings_usps_read_save');

function hdc_settings_canada_post_read_save()
{
    global $hdc_options;
    $hdc_options->hdc_shipping_canada_post_methods = array("hdc_shipping_canada_post_methods", "text");
    $hdc_options->hdc_shipping_canada_post_merchant_user = array("hdc_shipping_canada_post_merchant_user", "text");
    $hdc_options->hdc_shipping_canada_post_merchant_password = array("hdc_shipping_canada_post_merchant_password", "text");
    $hdc_options->hdc_shipping_canada_post_customer = array("hdc_shipping_canada_post_customer", "text");
}
add_action('hdc_settings_fields', 'hdc_settings_canada_post_read_save');

/* Display the method options */
// include USPS options
function hdc_settings_include_shipping_usps()
{
    require dirname(__FILE__) . '/../settings/shipping/shipping_usps.php';
}

// include Canada Post options
function hdc_settings_include_shipping_canada_post()
{
    require dirname(__FILE__) . '/../settings/shipping/shipping_canada_post.php';
}

function hdc_settings_include_shipping_flat_rate()
{
    require dirname(__FILE__) . '/../settings/shipping/shipping_flat_rate.php';
}

/* Include in the cart */
// Flat Rate
function hdc_shipping_flat_rate_includes($hdc_shipping_class, $hdc_quantity, $hdc_weight, $hdc_width, $hdc_height, $hdc_length, $hdc_t_address, $hdc_t_city, $hdc_t_state, $hdc_t_country, $hdc_t_zip, $hdc_f_address, $hdc_f_city, $hdc_f_state, $hdc_f_country, $hdc_f_zip)
{
    require dirname(__FILE__) . '/../shipping/flat-rate/flat-rate.php';
}

// USPS
function hdc_shipping_usps_includes($hdc_shipping_class, $hdc_quantity, $hdc_weight, $hdc_width, $hdc_height, $hdc_length, $hdc_t_address, $hdc_t_city, $hdc_t_state, $hdc_t_country, $hdc_t_zip, $hdc_f_address, $hdc_f_city, $hdc_f_state, $hdc_f_country, $hdc_f_zip)
{
    require dirname(__FILE__) . '/../shipping/usps/usps.php';
}

// Canada Post
function hdc_shipping_canada_post_includes($hdc_shipping_class, $hdc_quantity, $hdc_weight, $hdc_width, $hdc_height, $hdc_length, $hdc_t_address, $hdc_t_city, $hdc_t_state, $hdc_t_country, $hdc_t_zip, $hdc_f_address, $hdc_f_city, $hdc_f_state, $hdc_f_country, $hdc_f_zip)
{
    require dirname(__FILE__) . '/../shipping/canada-post/canada-post.php';
}
?>