<?php
// Canada Post
// ______________________________________________________________

$service_url = 'https://ct.soa-gw.canadapost.ca/rs/ship/price';

$hdc_shipping_canada_post_methods = sanitize_text_field(get_option("hdc_shipping_canada_post_methods"));
$hdc_shipping_canada_post_methods = explode("," , $hdc_shipping_canada_post_methods);
$username = sanitize_text_field(get_option("hdc_shipping_canada_post_merchant_user"));
$password = sanitize_text_field(get_option("hdc_shipping_canada_post_merchant_password"));
$mailedBy = intval(get_option("hdc_shipping_canada_post_customer"));
$fromZip = sanitize_text_field(get_option("hdc_store_zip"));
$toZip = sanitize_text_field($hdc_t_zip);
$hdc_shipping_unit = intval(get_option("hdc_shipping_unit"));

$weight = floatval($hdc_weight);

// if the customer is entering the weight using imperial
// then we need to convert it to kg
if($hdc_shipping_unit == 1){
	$weight = hdc_convert_unit($weight, "kg");
}



// REST URL
$service_url = 'https://ct.soa-gw.canadapost.ca/rs/ship/price';

// Create GetRates request xml
$originPostalCode = $fromZip;
$postalCode = $toZip;

// replace any spaces in the postal codes
$originPostalCode = str_replace(' ', '', $originPostalCode);
$postalCode = str_replace(' ', '', $postalCode);

// make sure it's uppercase
$originPostalCode = strtoupper($originPostalCode);
$postalCode = strtoupper($postalCode);

// figure out if we should use domestic , united states, or international

$destination = "";
if($hdc_t_country == "CA"){
	$destination = "CA";
} else if ($hdc_t_country == "US"){
	$destination = "US";
} else {
	$desination = "IN";
}

$xmlRequest = '<?xml version="1.0" encoding="UTF-8"?>
<mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v3">
  <customer-number>'.$mailedBy.'</customer-number>
  <parcel-characteristics>
	<weight>'.$weight.'</weight>
  </parcel-characteristics>
  <origin-postal-code>'.$originPostalCode.'</origin-postal-code>
   <destination>';

if($destination == "CA"){
	$xmlRequest  = $xmlRequest. '<domestic>
	  <postal-code>'.$postalCode.'</postal-code>
	</domestic>';
} else if ($destination == "US"){
	$xmlRequest  = $xmlRequest .= '	<united-states>
	  <zip-code>'.$postalCode.'</zip-code>
	</united-states>';
} else {
	$xmlRequest  = $xmlRequest .= '	<international>
		<country-code>'.$hdc_t_country.'</country-code>
	</international>';
}
$xmlRequest .= '</destination>
</mailing-scenario>';

$headers = array(
				'Content-Type'		=> 'application/vnd.cpc.ship.rate-v3+xml',
				'Accept'			=> 'application/vnd.cpc.ship.rate-v3+xml',
				'Authorization'		=> 'Basic ' . base64_encode($username . ':' . $password),
				'Accept-language'	=> 'en-CA'
			);

$data = wp_remote_post( $service_url,
	array(
		'method'           => 'POST',
		'timeout'          => 70,
		'sslverify'        => 0,
		'headers'          => $headers,
		'body'			   => $xmlRequest,
	)
);
$data = $data["body"];

$rates = array();
$xml = simplexml_load_string('<root>' . preg_replace('/<\?xml.*\?>/','',$data) . '</root>');
if (!$xml) {
	echo 'Failed loading XML' . "\n";
	echo $curl_response . "\n";
	foreach(libxml_get_errors() as $error) {
		echo "\t" . $error->message;
	}
} else {
	if ($xml->{'price-quotes'} ) {
		$priceQuotes = $xml->{'price-quotes'}->children('http://www.canadapost.ca/ws/ship/rate-v3');
		if ( $priceQuotes->{'price-quote'} ) {
			foreach ( $priceQuotes as $priceQuote ) {
				// if the method is marked as allowed in the HDCommerce settings, add it to the array
				if (in_array( $priceQuote->{'service-name'}, $hdc_shipping_canada_post_methods)) {
					$price = number_format(floatval($priceQuote->{'price-details'}->{'due'}), 2);
					array_push($rates, array($price, $priceQuote->{'service-name'}));
				}
			}
		}
	}

	// if there was an error
	// server error, wrong weight, postal code etc
	$isComplete = true;
	if ($xml->{'messages'} ) {
		$isComplete = false;
	}
	
	/*
	 * un comment this to see returned error messages from Canada Post
	if ($xml->{'messages'} ) {

		$messages = $xml->{'messages'}->children('http://www.canadapost.ca/ws/messages');
		foreach ( $messages as $message ) {
			echo 'Error Code: ' . $message->code . "\n";
			echo 'Error Msg: ' . $message->description . "\n\n";
		}
	}
	*/

}

// return the methods
if (!empty($rates)) {
	// sort the array to order by cheapest first
	sort($rates);
	// echo out the option fields
	foreach($rates as $value){
		echo '<option value = "'.$value[0].'">'.hdc_amount($value[0]).' | '.$value[1].'</option>';
	}
}

// if there were no errors, but no methods were returned
if (empty($rates) && $isComplete){
	echo 'no options';
}

?>