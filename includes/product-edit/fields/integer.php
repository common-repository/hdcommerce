<?php
function hdc_printField_integer($tab, $tab_slug, $fields)
{
    $value = hdc_getValue($tab, $fields);
    $placeholder = hdc_getPlaceholder($tab, $fields);
    $required = hdc_getRequired($tab, $fields);
    $hasPrefix = false;
    if (isset($tab["prefix"])) {
        $hasPrefix = true;
    }
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

    <?php
        if ($hasPrefix) {
            $prefix = $tab["prefix"];
            if ($prefix == "size_unit") {
                $prefix = hdc_get_shipping_units();
                $prefix = $prefix[0];
            }
            if ($prefix == "weight_unit") {
                $prefix = hdc_get_shipping_units();
                $prefix = $prefix[1];
            }
    ?>

    <div class="input_has_prefix">
        <div class="input_prefix"><?php echo $prefix; ?></div>

        <?php
        }

        $options = "";
        if (isset($tab["options"])) {
            for ($i = 0; $i < count($tab["options"]); $i++) {
                $n = $tab["options"][$i]["name"];
                $v = $tab["options"][$i]["value"];
                $options .= $n . ' = "' . $v . '"';
            }
        }
    ?>

        <input type="number" data-tab="<?php echo $tab_slug; ?>" data-type="integer" <?php echo $options; ?>
            class="input hderp_input" id="<?php echo $tab["name"]; ?>" steps="1" value="<?php echo $value; ?>"
            placeholder="<?php echo $placeholder; ?>">

        <?php if ($hasPrefix) { echo '</div>';} ?>
    </div>

    <?php
}