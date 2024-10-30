<?php
/*
    HDCommerce Orders Export Page Content
    Offers date range and exports the orders
*/

wp_enqueue_style(
    'hdc_admin_style',
    plugin_dir_url(__FILE__) . '../css/hdc_admin_style.css'
);

wp_enqueue_script(
    'hdc_admin_script',
    plugins_url('../js/hdc_admin.js', __FILE__),
    array('jquery', 'jquery-ui-draggable'),
    '1.0',
    true
);

?>

<div id="hdc_orders_export">
    <h1>
        Export HDCommerce Orders
    </h1>
    <p>
        Export a list of your orders to a standardized CSV file
    </p>
    <div id="hdc_export_choices">
        <div id="hdc_export_all_orders" data-start="" data-end="" class="button button-primary">
            EXPORT ALL ORDERS
        </div>
        <div id="hdc_export_choose_date_range" class="button button-secondary">
            CHOOSE DATE RANGE
        </div>
    </div>

    <div id="hdc_export_orders_date_range">
        <div>
            <label for="hdc_export_start_date">Start Date</label>
            <input id="hdc_export_start_date" type="date" />
        </div>
        <div>
            <label for="hdc_export_end_date">End Date</label>
            <input id="hdc_export_end_date" type="date" max="<?php echo date('Y-m-d'); ?>" />
        </div>
        <div>
            <div class="hdc_button" data-start="" data-end="" id="hdc_export_date_range_submit">
                EXPORT
            </div>
        </div>
    </div>

    <div id="hdc_export_orders_results">

    </div>
</div>