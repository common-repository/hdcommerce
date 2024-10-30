<?php
function hdc_printField_text($tab, $tab_slug, $fields)
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
        ?>
    </label>
    <input data-tab="<?php echo $tab_slug; ?>" data-type="text" data-required="<?php echo $required; ?>" type="text"
        class="input hderp_input" id="<?php echo $tab["name"]; ?>" value="<?php echo $value; ?>"
        placeholder="<?php echo $placeholder; ?>">
</div>
<?php
}