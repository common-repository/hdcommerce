<?php
function hdc_printField_image($tab, $tab_slug, $fields)
{
    $value = hdc_getValue($tab, $fields);
    $placeholder = hdc_getPlaceholder($tab, $fields);
    $required = hdc_getRequired($tab, $fields);
?>

<div class="input_item">
    <label class="input_label" for="<?php echo $tab["name"]; ?>">
        <?php
            if ($required) {
                hdc_print_tab_requiredIcon();
                $required = "required";
            }
            echo $tab["label"];
            if (isset($tab["tooltip"]) && $tab["tooltip"] != "") {
                hdc_print_fields_tooltip($tab["tooltip"]);
            }

            $options = "";
            if (isset($tab["options"])) {
                $options = 'data-options = "' .hdc_encodeURIComponent(json_encode($tab["options"])).'"';
            }
        ?>
    </label>
    <div id="<?php echo $tab["name"]; ?>" <?php echo $options; ?> data-value="<?php echo $value; ?>"
        data-tab="<?php echo $tab_slug; ?>" data-type="image" class="input input_image hderp_input">
        <?php
            if ($value == "") {
                echo 'set image';
            } else {
                echo wp_get_attachment_image($value, "large", "", array("class" => "image_field_image"));				
            }
        ?>
    </div>
	<?php if($value != ""){echo '<p class = "remove_image_wrapper" style = "text-align:center"><span class = "remove_image" data-id = "'.$tab["name"].'">remove image</span></p>';} ?>
</div>

<?php
}