<?php

function hdc_printField_editor($tab, $tab_slug, $fields)
{
    $value = hdc_getValue($tab, $fields);
    $placeholder = hdc_getPlaceholder($tab, $fields);
    $required = hdc_getRequired($tab, $fields);
?>

<div class="input_item" data-tab="<?php echo $tab_slug; ?>" data-required="<?php if($required){echo "required"; } ?>">
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
        ?>
    </label>

    <?php
        $media = true;
        if (isset($tab["media"]) && $tab["media"] == false) {
            $media = false;
        }   
        wp_editor(stripslashes(urldecode($value)), $tab["name"], array('textarea_name' => $tab["name"], 'editor_class' => "hderp_editor", 'media_buttons' => $media, 'textarea_rows' => 20, 'quicktags' => true, 'editor_height' => 240));
    ?>
</div>

<?php
}