<?php
function hdc_printField_select($tab, $tab_slug, $fields)
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

    <select data-tab="<?php echo $tab_slug; ?>" data-type="select" class="input hderp_input"
        id="<?php echo $tab["name"]; ?>">
        <option value="">-</option>
        <?php
            if (isset($tab["options"])) {
                for ($i = 0; $i < count($tab["options"]); $i++) {
                    $n = $tab["options"][$i]["label"];
                    $v = $tab["options"][$i]["value"];
                    $selected = "";
                    if ($v == $value) {
                        $selected = "selected";
                    }
                    echo '<option value="' . $v . '" ' . $selected . '>' . $n . '</option>';
                }
            }
        ?>
    </select>
</div>

<?php
}