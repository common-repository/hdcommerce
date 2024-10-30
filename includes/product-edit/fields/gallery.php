<?php

function hdc_printField_gallery($tab, $tab_slug, $fields)
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
                $options = 'data-options = "' . hdc_encodeURIComponent(json_encode($tab["options"])) . '"';
            }
        ?>
    </label>

    <div id="<?php echo $tab["name"] ?>" <?php echo $options; ?> data-value="<?php echo $value; ?>"
        data-tab="<?php echo $tab_slug; ?>" data-type="gallery" class="input input_image hderp_input">
        add images
    </div>
    <div id="<?php echo $tab["name"] ?>_container" class="image_gallery_container">
        <?php
            if ($value != "") {
                $value = explode(",", $value);
                for ($i = 0; $i < count($value); $i++) {
                    echo wp_get_attachment_image(
                        $value[$i],
                        "large",
                        "",
                        array(
                            "class" => "gallery_field_image",
                            "data-id" => $value[$i],
                            "data-parent" => $tab["name"],
                            "title" => "click to remove, or drag to reorder",
                        )
                    );
                }
            }
        ?>
    </div>

</div>

<?php
}