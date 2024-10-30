<?php
// USPS
// ______________________________________________________________



// figure out if we should use domestic shipping or international
$domestic_codes = array("US", "PR", "VI");
$domestic = true;
if(in_array($hdc_t_country, $domestic_codes)){
	$domestic = true;
} else {
	$domestic = false;
}

start_hdc_shipping_usps($hdc_t_zip, $hdc_weight, $hdc_width, $hdc_height, $hdc_length, $domestic, $hdc_quantity);

function start_hdc_shipping_usps($dZip, $weight, $height, $width, $length, $domestic, $quantity){

	// get USPS username
	$hdc_shipping_usps_user = sanitize_text_field(get_option("hdc_shipping_usps_user"));
	// get active shipping methods
	$hdc_shipping_usps_methods = sanitize_text_field(get_option("hdc_shipping_usps_methods"));
	$hdc_shipping_usps_methods = explode(",", $hdc_shipping_usps_methods);

	// get the store zip
	$oZip = sanitize_text_field(get_option("hdc_store_zip"));

	// get international shipping price
	$hdc_shipping_usps_international = sanitize_text_field(get_option("hdc_shipping_usps_international"));
	if($hdc_shipping_usps_international != "" && $hdc_shipping_usps_international != null){
		$hdc_shipping_usps_international = floatval($hdc_shipping_usps_international);
	}

	// determine shipping unit
	$hdc_shipping_unit = intval(get_option("hdc_shipping_unit"));
	$weight = floatval($weight);

	// if the customer is entering the weight using metric
	// then we need to convert it to lbs
	if($hdc_shipping_unit == 1){
		$weight = hdc_convert_unit($weight, "lbs");
	}

	$wasError = false;

	// get the shipping methods
	if ($domestic){
		$methods = get_hdc_usps_rates_domestic($hdc_shipping_usps_user, $value, $weight, $oZip, $dZip);
		// only keep the methods we've selected in HDCommerce Settings
		if (!empty($methods)) {
			foreach($methods as $key => $value){
				if($value[1] == "error"){
					$wasError = true;
				}
				if(!in_array($value[1] , $hdc_shipping_usps_methods )){
					unset($methods[$key]);
				}
			}
		}
	} else {
		$methods = get_hdc_usps_rates_international($hdc_shipping_usps_international, $quantity);
	}

	if (!empty($methods)) {
		sort($methods);
		foreach($methods as $value){
			if($value[1] != "error"){
				echo '<option value = "'.$value[0].'">'.hdc_amount($value[0]).' | '.$value[1].'</option>';
			}
		}
	} else if (!$wasError) {
		echo 'no options';
	}
}


function get_hdc_usps_rates_domestic($username, $method, $weight, $oZip, $dZip){
	// create the XML
	$xml = '<RateV4Request USERID="'.$username.'">';
	$xml .= '<Revision>2</Revision>';
	$xml .= '<Package ID="1ST">';
	$xml .= '<Service>ALL</Service>';
	$xml .= '<ZipOrigination>'.$oZip.'</ZipOrigination>';
	$xml .= '<ZipDestination>'.$dZip.'</ZipDestination>';
	$xml .= '<Pounds>'.$weight.'</Pounds>';
	$xml .= '<Ounces>0</Ounces>';
	$xml .= '<Container/>';
	$xml .= '<Size>REGULAR</Size>';
	$xml .= '<Machinable>true</Machinable>';
	$xml .= '</Package>';
	$xml .= '</RateV4Request>';

	$data = wp_remote_post("http://production.shippingapis.com/ShippingAPI.dll?API=RateV4&xml=".$xml);
	$data = $data["body"];
	$data = simplexml_load_string('<root>' . preg_replace('/<\?xml.*\?>/','',$data) . '</root>');

	// print_r($data);

	$methods = array();
	$rates = $data->{'RateV4Response'}->{'Package'}->{'Postage'};

	if(!empty($rates)){
		foreach ($rates as $value){
			$name = $value->{'MailService'};
			$name = str_replace("&#174;", "", $name); // reserved
			$name = str_replace("&#169;", "", $name); // copyright
			$name = str_replace("&#8482;", "", $name); // tm
			$name = str_replace("&lt;sup&gt;&lt;/sup&gt;", "", $name); // the rest
			$price = $value->{'Rate'};
			$price = number_format(floatval($price), 2);
			if($price != "" && $price != null){
				array_push($methods, array($price, $name));
			}
		}
	} else {
		array_push($methods, array(99, "error"));
	}
	return $methods;
}

function get_hdc_usps_rates_international($hdc_shipping_usps_international, $quantity){
	$methods = array();
	$value = $hdc_shipping_usps_international * $quantity;
	array_push($methods, array($value, "International shipping"));
	return $methods;
}



	/*
	 * International API is not ready yet.
	 * Will use flat rate shipping instead for international orders

	$date = date("Y-m-d\TH:i:s", strtotime('+5 hours'));

	$xml = '<IntlRateV2Request USERID="'.$username.'">
			<Revision>2</Revision>
			<Package ID="1ST">
			<Pounds>'.$weight.'</Pounds>
			<Ounces>0</Ounces>
			<Machinable>True</Machinable>
			<MailType>ALL</MailType>
			<ValueOfContents>0</ValueOfContents>
			<Country>Canada</Country>
			<Container>RECTANGULAR</Container>
			<Size>LARGE</Size>
			<Width>10</Width>
			<Length>15</Length>
			<Height>10</Height>
			<Girth>0</Girth>
			<OriginZip>'.$oZip.'</OriginZip>
			<AcceptanceDateTime>'.$date.'</AcceptanceDateTime>
			<DestinationPostalCode>'.$dZip.'</DestinationPostalCode>
			</Package>
			</IntlRateV2Request>';

			$data = wp_remote_post("http://production.shippingapis.com/ShippingAPI.dll?API=IntlRateV2&xml=".$xml);
			$data = $data["body"];
			$data = simplexml_load_string('<root>' . preg_replace('/<\?xml.*\?>/','',$data) . '</root>');

			print_r($data);

			$methods = array();
			$rates = $data->{'IntlRateV2Response'}->{'Service'}->{'Postage'};

			print_r($rates);

			*/

?>