<?php

include dirname(__FILE__) . '/fields/currency.php';
include dirname(__FILE__) . '/fields/editor.php';
include dirname(__FILE__) . '/fields/float.php';
include dirname(__FILE__) . '/fields/gallery.php';
include dirname(__FILE__) . '/fields/image.php';
include dirname(__FILE__) . '/fields/integer.php';
include dirname(__FILE__) . '/fields/select.php';
include dirname(__FILE__) . '/fields/text.php';
include dirname(__FILE__) . '/fields/variations.php';
include dirname(__FILE__) . '/fields/categories.php';

function hdc_getValue($tab, $fields)
{
    $value = "";
    if (isset($fields[$tab["name"]])) {
        $value = $fields[$tab["name"]]["value"];
    }
    return $value;
}

function hdc_getPlaceholder($tab, $fields)
{
    $placeholder = "";
    if (isset($tab["placeholder"]) && $tab["placeholder"] != "") {
        $placeholder = $tab["placeholder"];
    }
    return $placeholder;
}

function hdc_getRequired($tab, $fields)
{
    $required = false;
    if (isset($tab["required"]) && $tab["required"] == true) {
        $required = true;
    }
    return $required;
}

function hdc_print_tab_requiredIcon()
{
    echo '<span class="required_icon">*</span>';
}

function hdc_print_fields_tooltip($tooltip)
{
    ?>
 <span class="tooltip">
    ?
    <span class="tooltip_content">
        <span><?php echo $tooltip; ?></span>
    </span>
</span>
<?php
}

function hdc_printField_col11($tab, $tab_slug, $fields)
{
    echo '<div class = "row col-1-1">';
    echo hdc_print_tab_fields($tab["children"], $tab_slug, $fields);
    echo '</div>';
}

function hdc_printField_col111($tab, $tab_slug, $fields)
{
    echo '<div class = "row col-1-1-1">';
    echo hdc_print_tab_fields($tab["children"], $tab_slug, $fields);
    echo '</div>';
}

function hdc_printField_content($tab, $tab_slug)
{
    echo '<div class = "row_description">';
    echo $tab["value"];
    echo '</div>';
}

function hdc_printField_action($tab, $tab_slug, $fields)
{
    if (!isset($tab["function"])) {
        return;
    }

    if (function_exists($tab["function"])) {
        $tab["function"]($tab, $tab_slug, $fields);
    }
}
?>