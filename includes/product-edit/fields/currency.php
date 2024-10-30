<?php
function hdc_printField_currency($tab, $tab_slug, $fields)
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
    <div class="input_has_prefix">
        <div class="input_prefix"><?php echo hdc_get_currency_symbol(); ?></div>
        <input type="number" data-tab="<?php echo $tab_slug; ?>" data-type="currency"
            data-required="<?php echo $required; ?>" step="0.01" min="0" max="999999999" class="input hderp_input"
            id="<?php echo $tab["name"]; ?>" value="<?php echo $value; ?>" placeholder="0.00">
    </div>
</div>

<?php
}