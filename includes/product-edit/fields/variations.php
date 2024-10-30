<?php

function printProductTabVariations($tab, $tab_slug, $fields)
{
    ?>
<div class="row">
    <div class="input_item">
        <label class="input_label" for="add_variation">
            Add Variation
            <span class="tooltip">
                ?
                <span class="tooltip_content">
                    <span>Enter the variation here: Example: Size, Colour, etc</span>
                </span>
            </span>
        </label>
        <input data-type="text" type="text" class="input input_enter" id="add_variation" value=""
            placeholder="variation name...">
    </div>
</div>

<div class="row col-1-1">
    <div id="product_variations" data-type="variations" class="hderp_input">
        <?php printProductVariationItems($tab, $tab_slug, $fields); ?>
    </div>
    <div id="product_permutations" data-type="permutations" class="hderp_input">
        <?php printProductPermutationItems($tab, $tab_slug, $fields); ?>
    </div>
</div>
<?php
}

function printProductVariationItems($tab, $tab_slug, $fields)
{ 

	if(!isset($fields["product_variations"]["value"])){
		return;
	}
	
	$variations = $fields["product_variations"]["value"];
	if(isset($variations) && $variations != ""){
		for($i = 0; $i < count($variations); $i++){
			?>


<div class="variation_item" data-name="<?php echo $variations[$i]["name"]; ?>"
    data-slug="<?php echo $variations[$i]["slug"]; ?>" id="variation_<?php echo $variations[$i]["slug"]; ?>">
    <div data-id="variation_<?php echo $variations[$i]["slug"]; ?>" class="variation_item_delete"
        title="delete this variation"></div>
    <h3 class="variation_title"><?php echo $variations[$i]["name"]; ?></h3>
    <div class="input_item">
        <label class="input_label" for="add_option_<?php echo $variations[$i]["slug"]; ?>">
            Add Variation Option
            <span class="tooltip">
                ?
                <span class="tooltip_content">
                    <span>Enter the variation options: Example: large, medium, small, or red, blue, green etc</span>
                </span>
            </span>
        </label>
        <input data-type="text" type="text" data-slug="<?php echo $variations[$i]["slug"]; ?>"
            data-name="<?php echo $variations[$i]["name"]; ?>" class="input variation_option input_enter"
            id="add_option_<?php echo $variations[$i]["slug"]; ?>" value="" placeholder="option name...">
    </div>
	<div class = "variation_options_wrapper">
    <?php
			for($x = 0; $x < count($variations[$i]["options"]); $x++){
	?>
    <div class="option_item" data-variation="<?php echo $variations[$i]["slug"]; ?>"
        data-variation-name="<?php echo $variations[$i]["slug"]; ?>"
        data-slug="<?php echo $variations[$i]["options"][$x]["slug"]; ?>"
        data-name="<?php echo $variations[$i]["options"][$x]["name"]; ?>" title="click to delete, drag to reorder">
        <?php echo $variations[$i]["options"][$x]["name"]; ?></div>

    <?php
			}
			echo '</div></div>';
		}
	}
}

function printProductPermutationItems($tab, $tab_slug, $fields)
{
	$variations = $fields["product_permutations"]["value"];
	if(isset($variations) && $variations != ""){
		for($i = 0; $i < count($variations); $i++){
			$stock = $variations[$i]["options"]["stock"];
			$json = urlencode(json_encode($variations[$i]["options"]));
    ?>

    <div class="product_item <?php if($stock === 0){ echo "permutation_no_stock"; } ?>" data-options="<?php echo $json; ?>" data-slug="<?php echo $variations[$i]["id"]; ?>"
        data-name="<?php echo $variations[$i]["name"]; ?>" title="select to set variation options">
        <?php echo urldecode($variations[$i]["title"]); ?>
	</div>
    <?php
		}
	}
}