<?php
/*
	HDCommerce Settings Tax Tab
	Contains:
		@hdc_tax_inclusive,
		@hdc_tax_billing,
		@hdc_tax_chart,
*/
?>

	<div class="hdc_tab" id="hdc_settings_tax">

		<div class = "one_half">
			<label class = "non-block" for="hdc_tax_inclusive">Will your entered prices include the tax?</label>
			<a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>Leave disabled to only show tax amount in the checkout/cart</span></span></a>			
			<div class="hdc-options-check">
				<input type="checkbox" id="hdc_tax_inclusive" value="yes" name="hdc_tax_inclusive" <?php if ($hdc_tax_inclusive == 1) { echo "checked"; } ?> >
				<label for="hdc_tax_inclusive"></label>
			</div>
		</div>
		<div class = "one_half last">
			<label class = "non-block" for="hdc_tax_billing"><s>Calculate tax based on customer's billing address?</s></label>
			<a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>This option will be removed in future versions.</span></span></a>
			<div class="hdc-options-check"><input type="checkbox" id="hdc_tax_billing" value="yes" name="hdc_tax_billing" <?php if($hdc_tax_billing == 1) {echo "checked";} ?> /><label for="hdc_tax_billing"></label></div>
		</div>

		<div class = "clear"></div>

		<hr/>
		<br/>
		<h3>Tax Chart - Drag and drop to change tax priority <a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>In the case of compounded taxes, the taxes listed first will be charged first.</span></span></a></h3>

	<div class = "hdc_setting_row">

		<table class="hdc_table" id ="hdc_tax_table">
			<thead>
				<tr>
				<th>Country</th>
				<th>State / Province</th>
				<th>Tax Name</th>
				<th>Rate %</th>
				<th width = "1">Compound</th>
				<th width = "1">x</th>
			</tr>
			</thead>
			<tbody>
<?php

		if($hdc_tax_chart != "" && $hdc_tax_chart != null){
			$hdc_tax_chart = str_replace("\\", "", $hdc_tax_chart);
		}

		if($hdc_tax_chart != "" && $hdc_tax_chart != null){
			$hdc_tax_chart = json_decode($hdc_tax_chart, true);
			foreach($hdc_tax_chart as $key => $value) {
				$counter = 0;
				echo '<tr class = "grab">';
				foreach($value as $value2) {
					if ($counter == 2) {
						echo '<td><div class = "hdc_tax_name" contenteditable = "true">'.$value2.'</div></td>';
					}
					if ($counter == 3) {
						echo '<td><span class = "hdc_tax_value" contenteditable = "true">'.$value2.'</span>%</td>';
					}
					if ($counter == 4) {
						$checked = "";
						if ($value2 == "yes"){
							$checked = "checked";
						}
						echo '<td><div class="hdc-options-check"><input type="checkbox" id="hdc_'.$key.'" value="yes" name="hdc_'.$key.'" '.$checked.'><label for="hdc_'.$key.'"></label></div></td>';
					} else if ($counter == 0 || $counter == 1){
						echo '<td>'.$value2.'</td>';
					}
					$counter = $counter + 1;
				}
				echo '<td><div class="hdc_remove_tax">X</div></td>';
				echo '</tr>';
			}
		}
?>

			</tbody>
		</table>
		</div>
		<h3>Select a Country and State/Province/Territory below to add a tax rate to the table</h3>
		<div class = "hdc_setting_row">

		<div class = "one_half">
			<!-- TODO: Make this list only show countries that we are selling to
				load this from CSV
			-->
			<select id = "hdc_tax_country" name = "hdc_tax_country">
				<option rel = "hide" value = "">Select a Country</option>
<option value = "United States" data-states="Alabama|Arizona|Arkansas|California|Colorado|Connecticut|Delaware|Florida|Georgia|Idaho|Illinois|Indiana|Iowa|Kansas|Kentucky|Louisiana|Maine|Maryland|Massachusetts|Michigan|Minnesota|Mississippi|Missouri|Montana|Nebraska|Nevada|New Hampshire|New Jersey|New Mexico|New York|North Carolina|North Dakota|Ohio|Oklahoma|Oregon|Pennsylvania|Rhode Island|South Carolina|South Dakota|Tennessee|Texas|Utah|Vermont|Virginia|Washington|West Virginia|Wisconsin|Wyoming">United States</option>

<option value = "Canada" data-states="Alberta|British Columbia|Manitoba|New Brunswick|Newfoundland and Labrador|Northwest Territories|Nova Scotia|Nunavut|Ontario|Prince Edward Island|Quebec|Saskatchewan|Yukon">Canada</option>

<!-- new -->
<option value = "Afghanistan" data-states="balh|jozjan|samangan|saripol|kunar|lagman|paktya|nimroz|nuristan|logar|kapisa|cabul|hilmand|hirat|qandahar|uruzgan|parwan|parwan|uruzgan|baglan|gawr|gazni|badgis|verdak|takar|qunduz|badakhshan|paktya|zabul|bamyan|faryab|farah|khost">Afghanistan</option>

<option value = "&#197;land Islands" data-states="">&#197;land Islands</option>

<option value = "Albania" data-states="elbasan|durres|lezhe|korce|gjirokaster|fier|durres|vlora|dibra|berat">Albania</option>

<option value = "Algeria" data-states="tihert|stif|saida|tlemcen|tizi ouzou|skidda|sidi bel abbes|biskra|bugia|ain defla|adrar|bona|ain tamouchent|batna|vialar|tipaza|tiaret|tamenghest|souk ahras|relizane|wargla|mestghanem|bechar|tbessa|argel|msila|medea|canrobert|mascara|oran|jijel|laghouat|djelfa|guelma|blida|bouira|mila|naama|el tarf|gharday|ilizi|khenchela|boumerdes|chlef|el bayad|biskra|borjbouarirej">Algeria</option>

<option value = "American Samoa" data-states="">American Samoa</option>

<option value = "Andorra" data-states="">Andorra</option>

<option value = "Angola" data-states="">Angola</option>

<option value = "Anguilla" data-states="">Anguilla</option>

<option value = "Antarctica" data-states="">Antarctica</option>

<option value = "Antigua and Barbuda" data-states="">Antigua and Barbuda</option>

<option value = "Argentina" data-states="la rioja|jujuy|el pampa|rio negro|salta|misiones|neuquen|feuerland|san juan|san luis|santiago del estero|tucuman|formosa|mendoza|santa fe|santa cruz|cordoba|chubut|corrientes|capital federal|chaco|catamarca">Argentina</option>

<option value = "Armenia" data-states="erevan|armavir|aragatsotn|lori|shirak|gegharkunik|kotayk">Armenia</option>

<option value = "Aruba" data-states="">Aruba</option>

<option value = "Australia" data-states="queensland|south australia|unknown|victoria|australian capital territory|northern territory|western australia">Australia</option>

<option value = "Austria" data-states="karnten|niederdonau|burgenland|styria|tyrol|alta austria|salzburg|vorarlberg|wien">Austria</option>

<option value = "Azerbaijan" data-states="shusha|kurdmir|klbcr|ismailli|imisli|goycay|goranboy|gnc|dvci|bilsuvar|salyan|agstafa|xocali|tovuz|zaqatala|zrdab|syunik|xacmaz|qbl|ucar|sdrk|trtr|sumqayit|susa|balakn|samaxi|smkir|fizuli|gdby|qax|beylqan|babk|neftcala|brd|dasksn|lerik|xocavnd|clilabad|mingcevir|qubadli|yevlax|srur|astara|xizi|agdas|li bayramli|agsu|baki|yardimli|agdam|qobustan|zngilan|quba|saatli|qusar|sabirabad">Azerbaijan</option>

<option value = "Bahamas" data-states="">Bahamas</option>

<option value = "Bahrain" data-states="">Bahrain</option>

<option value = "Bangladesh" data-states="barisal|sylhet|daca|daca|rajshahi|khulna">Bangladesh</option>

<option value = "B" data-states="christ church|saint thomas|saint george|saint james|saint john|saint joseph|saint lucy|saint michael|saint peter|saint andrew|saint philip">Barbados</option>

<option value = "Belarus" data-states="mogilev|witebsk|minsk|brest|hrodna|homje">Belarus</option>

<option value = "Belgium" data-states="vlaams brabant|limburg|namur|walloon brabant|thuin|hainaut|luxembourg|liege|antwerp|hal-vilvorde|west flanders|tielt|ath|namur|virton|maaseik|huy|brussels-capital|bruselas|anvers|ostflandern">Belgium</option>

<option value = "Belau" data-states="">Belau</option>

<option value = "Belize" data-states="cayo|corozal|belize|toledo|orange walk|stann creek">Belize</option>

<option value = "Benin" data-states="">Benin</option>

<option value = "Bermuda" data-states="">Bermuda</option>

<option value = "Bhutan" data-states="">Bhutan</option>

<option value = "Bolivia" data-states="santa cruz|tarija|pando|potosi|la paz|oruro|cochabamba|el beni|chuquisaca">Bolivia</option>

<option value = "Bonaire, Saint Eustatius and Saba" data-states="">Bonaire, Saint Eustatius and Saba</option>

<option value = "Bosnia and Herzegovina" data-states="">Bosnia and Herzegovina</option>

<option value = "Botswana" data-states="">Botswana</option>

<option value = "Bouvet Island" data-states="">Bouvet Island</option>

<option value = "Brazil" data-states="sergipe|distrito federal|acre|alagoas|amapa|amazone|bahia|ceara|goias|espirito santo|rondonia|roraima|rio grande do norte|rio grande do sul|piaui|rio de janeiro|parana|maranhao|mato grosso do sul|paraiba|para|minas|mato grosso|tocantins|pernambouc|sao paulo|santa catarina">Brazil</option>

<option value = "British Indian Ocean Territory" data-states="">British Indian Ocean Territory</option>

<option value = "British Virgin Islands" data-states="">British Virgin Islands</option>

<option value = "Brunei" data-states="">Brunei</option>

<option value = "Bulgaria" data-states="pernik|slivno|sofia|haskovo|plovdiv|ruse|lovec|varna">Bulgaria</option>

<option value = "Burkina Faso" data-states="">Burkina Faso</option>

<option value = "Burundi" data-states="">Burundi</option>

<option value = "Cambodia" data-states="">Cambodia</option>

<option value = "Cameroon" data-states="">Cameroon</option>

<option value = "Cape Verde" data-states="">Cape Verde</option>

<option value = "Cayman Islands" data-states="">Cayman Islands</option>

<option value = "Central African Republic" data-states="">Central African Republic</option>

<option value = "Chad" data-states="">Chad</option>

<option value = "Chile" data-states="ix|atacama|vii|coquimbo|tarapaca|rm|aisen del general carlos ibanez del campo|magallanes y antartica chilena|antofagasta|libertador|x|arica y parinacota|aconcagua">Chile</option>

<option value = "China" data-states="sichuan|gansu|yunnan|zhejiang|hubei|xinjiang uygur|hebei|hunan|guangxi|hainan|tibet|chongqing|guizhou|liaoning|shanghai|guangdong|heilongjiang|shanxi|shandong|shanxi|henan|tianjin|ningxia hui|nei mongol|jilin|jiangsu|fujian|gansu|anhui|jiangxi|tianjin">China</option>

<option value = "Christmas Island" data-states="">Christmas Island</option>

<option value = "Cocos (Keeling) Islands" data-states="zhejiang">Cocos (Keeling) Islands</option>

<option value = "Colombia" data-states="putumayo|antioquia|arauca|santander|amazonas|narino|norte de santander|atlantico|quindio|caqueta|cauca|tolima|caldas|boyaca|bolivar|bogota|meta|magdalena|vichada|vaupes|guania|guaviare|guajira|huila|choco|cesar|cordoba|risaralda|cundinamarca|sucre|casanare">Colombia</option>

<option value = "Comoros" data-states="">Comoros</option>

<option value = "Congo (Brazzaville)" data-states="">Congo (Brazzaville)</option>

<option value = "Congo (Kinshasa)" data-states="">Congo (Kinshasa)</option>

<option value = "Cook Islands" data-states="">Cook Islands</option>

<option value = "Costa Rica" data-states="san jose|limon|puntarenas|heredia|cartago|guanacaste|alajuela">Costa Rica</option>

<option value = "Croatia" data-states="una-sana|karlovac|koprivnicko-krievacka|split-dalmacia|varasd|zagreb|grad zagreb|ibensko-kninska|lika-senj|medimurje|brodsko-posavska|istra|primorsko-goranska|brodsko-posavska|sisak-moslavina|virovitica-podravina|bjelovar-bilagora">Croatia</option>

<option value = "Cuba" data-states="guantanamo|camaguey|cienfuegos|granma|la habana|holguin|las tunas|pinar del rio|ciudad de la habana|matanzas|isla de la juventud|villa clara|santiago de cuba|sancti spiritus">Cuba</option>

<option value = "Cura&ccedil;ao" data-states="">Cura&ccedil;ao</option>

<option value = "Cyprus" data-states="">Cyprus</option>

<option value = "Czech Republic" data-states="morava|carlsbad|budweis|liberec|iglau|stredocesky|kralovehradecky|prag">Czech Republic</option>

<option value = "Denmark" data-states="hovedstaden|north|southern|central|zealand">Denmark</option>

<option value = "Djibouti" data-states="">Djibouti</option>

<option value = "Dominica" data-states="">Dominica</option>

<option value = "Dominican Republic" data-states="espaillat|el seybo|el seybo|san pedro de macoris|barahona|santiago rodrigu|azua|samana|sanchez ramirez|dajabon|san juan|independencia|maria trinidad s|monte cristi|baoruco|pedernales|san rafael|la altagracia|la romana|hermanas|santo domingo|valverde|duarte|peravia|distrito nacional|trujillo|monte plata|monsenor nouel|la vega|puerto plata|santiago">Dominican Republic</option>

<option value = "Ecuador" data-states="pichincha|los rios|loja|imbabura|guayas|pastaza|morona santiago|manabi|orellana|sucumbios|tungurahua|zamora chinchipe|el oro|esmeraldas|galapagos|azuay|bolivar|canar|carchi|chimborazo|cotopaxi|napo">Ecuador</option>

<option value = "Egypt" data-states="giza|munufia|matruh|garbia|dumyat|ismailia|sud sinai|dagahlia|sohag|behera|faium|north sinai|alexandria|kafr el sheik|canal|beni suef|asyut|aswan|suez|sharqia|al wadi at jadid|kalyubia|cairo|minya">Egypt</option>

<option value = "El Salvador" data-states="">El Salvador</option>

<option value = "Equatorial Guinea" data-states="">Equatorial Guinea</option>

<option value = "Eritrea" data-states="northern red sea|maekel|anseba|gash barka|debub|obock">Eritrea</option>

<option value = "Estonia" data-states="polva|rapla|parnu|laane-viru|voru|viljandi|jogeva|jarva|laane|valga|harju|ida-viru|hiiu|tartu|hiiu">Estonia</option>

<option value = "Ethiopia" data-states="">Ethiopia</option>

<option value = "Falkland Islands" data-states="">Falkland Islands</option>

<option value = "Faroe Islands" data-states="">Faroe Islands</option>

<option value = "Fiji" data-states="rotuma|western|central|eastern|western">Fiji</option>

<option value = "Finland" data-states="pirkanmaa|tavastia proper|central finland|lappi|oulu|eastern finland|western finland|northern ostrobothnia|paijanne tavastia|southern ostrobothnia|south karelia|southern finland|north karelia|southern savonia|satakunta|satakunta|eastern uusimaa">Finland</option>

<option value = "France" data-states="upper normandy|franche-comte|pays de la loire|champagne-ardenne|centre|bretagne|burgundy|languedoc-rosellon|ile-de-france|lower normandy|auvergne|aquitaine|alsace|corse|lorena|midi-pyrenees|lemosin|picardie|poitou-charentes|nord-pas-de-calais|provence-alpes-cote-d&quot;azur|rhone-alpes">France</option>

<option value = "French Guiana" data-states="">French Guiana</option>

<option value = "French Polynesia" data-states="">French Polynesia</option>

<option value = "French Southern Territories" data-states="">French Southern Territories</option>

<option value = "Gabon" data-states="woleu-ntem|ogooue-maritime|moyen-ogooue|haut-ogooue|estuaire|ogooue-lolo|ogooue-ivindo|nyanga|ngounie">Gabon</option>

<option value = "Gambia" data-states="">Gambia</option>

<option value = "Georgia" data-states="guria|sokhumi|gori|qazax|akhaltsikhe|samux">Georgia</option>

<option value = "Germany" data-states="nordrhein-westfalen|saarland|bremen|baden-wurttemberg|rheinland-pfalz|brandenburg|schleswig-holstein|hesse|hamburg|berlin|thuringia|saxony-anhalt|saxony|mecklenburg-vorpommern|bayern|bremen">Germany</option>

<option value = "Ghana" data-states="">Ghana</option>

<option value = "Gibraltar" data-states="">Gibraltar</option>

<option value = "England" data-states="Bedfordshire|Buckinghamshire|Cambridgeshire|Cheshire|Cleveland|Cornwall|Cumbria|Derbyshire|Devon|Dorset|Durham|East Sussex|Essex|Gloucestershire|Greater London|Greater Manchester|Hampshire|Hertfordshire|Kent|Lancashire|Leicestershire|Lincolnshire|Merseyside|Norfolk|North Yorkshire|Northamptonshire|Northumberland|Nottinghamshire|Oxfordshire|Shropshire|Somerset|South Yorkshire|Staffordshire|Suffolk|Surrey|Tyne and Wear|Warwickshire|West Berkshire|West Midlands|West Sussex|West Yorkshire|Wiltshire|Worcestershire">England</option>

<option value = "Northern Ireland" data-states="Antrim|Armagh|Down|Fermanagh|Derry and Londonderry|Tyrone">Northern Ireland</option>

<option value = "Scotland" data-states="Aberdeen City|Aberdeenshire|Angus|Argyll and Bute|City of Edinburgh|Clackmannanshire|Dumfries and Galloway|Dundee City|East Ayrshire|East Dunbartonshire|East Lothian|East Renfrewshire|Eilean Siar|Falkirk|Fife|Glasgow City|Highland|Inverclyde|Midlothian|Moray|North Ayrshire|North Lanarkshire|Orkney Islands|Perth and Kinross|Renfrewshire|Scottish Borders|Shetland Islands|South Ayrshire|South Lanarkshire|Stirling|West Dunbartonshire|West Lothian">Scotland</option>

<option value = "Wales" data-states="Flintshire|Glamorgan|Merionethshire|Monmouthshire|Montgomeryshire|Pembrokeshire|Radnorshire|Anglesey|Breconshire|Caernarvonshire|Cardiganshire|Carmarthenshire|Denbighshire">Wales</option>

<option value = "Greece" data-states="thessaly|peloponnese|attiki|ionioi nisoi|sterea ellada|greece west|notio aigaio|macedonia west|crete|peloponnese|kirjali|aegean north|ayion oros|macedonia central|epirus">Greece</option>

<option value = "Greenland" data-states="qaasuitsup|national park|qeqqata|kujalleq|sermersooq|pituffik">Greenland</option>

<option value = "Grenada" data-states="">Grenada</option>

<option value = "Guadeloupe" data-states="">Guadeloupe</option>

<option value = "Guam" data-states="">Guam</option>

<option value = "Guatemala" data-states="">Guatemala</option>

<option value = "Guernsey" data-states="">Guernsey</option>

<option value = "Guinea" data-states="">Guinea</option>

<option value = "Guinea-Bissau" data-states="">Guinea-Bissau</option>

<option value = "Guyana" data-states="">Guyana</option>

<option value = "Haiti" data-states="">Haiti</option>

<option value = "Heard Island and McDonald Islands" data-states="">Heard Island and McDonald Islands</option>

<option value = "Honduras" data-states="">Honduras</option>

<option value = "Hong Kong" data-states="">Hong Kong</option>

<option value = "Hungary" data-states="pest|csongrad|fejer|veszprem|vas|tolna|jasz-nagykun-szolnok|bekes|zala|bacs-kiskun|somogy|nograd|baranya|komarom|borsod-abauj-zemplen|hajdu-bihar|heves|gyor-sopron|budapest|szabolcs-szatmar-bereg">Hungary</option>

<option value = "Iceland" data-states="akrahreppur|eyja- og miklaholtshreppur|vesturland|akureyri|south|vestfiroir|northland west|sveitarfelagio hornafjorour|akranes|eastland|biskupstungnahreppur|kelduneshreppur|hunabing vestra|vesturbyggo|myrdalshreppur|isafjaroarbar|austur-herao|arneshreppur|halshreppur|vestfiroir|west|capital|suournes">Iceland</option>

<option value = "India" data-states="meghalaya|chhattisgarh|arunachal pradesh|mizoram|dadra e nagar haveli|bihar|madhya pradesh|uttar pradesh|maisur|maharashtra|uttarakhand|kerala|jammu and kashmir|kerala|haryana|himachal pradesh|andhra pradesh|manipur|punjab|lakkadi|orissa|nagaland|tripura|rajputana|andaman et nicobar|sikkim|bangla|chandigarh|delhi|dadra e nagar haveli|goa|assam|vananchal">India</option>

<option value = "Indonesia" data-states="malut|benkulen|bali|aceh|jateng|jambi|jawa|sulteng|sultra|sumbar|sumut|ntb|sulsel|riou|ntt|jatim|jawa|kalbar|kalsel|kalteng|kaltim|lampung|sulsel|yapen|yapen|riou|gorontalo|babel|sumsel|banten|jabar|sulut">Indonesia</option>

<option value = "Iran" data-states="kerman|esfahan|tehran|semnan|lorestan|bushire|zanjan|golestan|qazvin|qom|markazi|bakhtaran|ilam|saheli|kurdistan|mazandaran|ardabil|khuzestan|kohgil|baluchestan va sistan|fars|west azarbaijan|bakhtiari|south khorasan|yazd|north khorasan|razavi khorasan|hamedan|gilan">Iran</option>

<option value = "Iraq" data-states="kerbela|tamin|diyala|erbil|kut|d&quot;hok|maysan|mosul|as-sulaymaniyah|diwaniyah|salaheddin|hilla|ramadi|al-muthanna|basra|dhi-qar|bagdad|an-najaf">Iraq</option>

<option value = "Ireland" data-states="cavan|sligo|tipperary|carlow|mayo|meath|cork|kings|westmeath|louth|longford|wicklow|wexford|leix|leitrim|waterford|limerick|kerry|galway|kilkenny|kildare|roscommon|clare|monaghan|donegal|dublin">Ireland</option>

<option value = "Isle of Man" data-states="">Isle of Man</option>

<option value = "Israel" data-states="al-quds|hefa|tel aviv|al-quds|hadarom|hamerkaz|hazafon">Israel</option>

<option value = "Italy" data-states="marche|molise|abruzzo|apulia|sardinia|tuscany|emilia romagna|trentino alto adige|umbria|veneto|lombardy|lazio|lacio|sicily|lazio|val d&quot;aoste|basilicata|calabria|friuli-venecia julia">Italy</option>

<option value = "Ivory Coast" data-states="">Ivory Coast</option>

<option value = "Jamaica" data-states="manchester|saint ann|saint andrew|saint thomas|trelawny|westmoreland|kingston|saint catherine|hanover|saint james|saint elizabeth|portland|clarendon|saint mary">Jamaica</option>

<option value = "Japan" data-states="yamanasi|yamaguti|yamagata|wakayama|toyama|tottori|edo|niigata|nara|gunma|hirosima|ezo|hiogo|ibaraki|isikawa|totigi|tokusima|fukui|sizuoka|saitama|siga|osaka|saga|oita|okayama|iwate|kagawa|nara|kanagawa|mie|hukusima|aomori|akita|aiti|fukuoka|hukui|ehime|tiba|kumamoto|koti|gifu|kioto|miyazaki|miyagi|nagano">Japan</option>

<option value = "Jersey" data-states="">Jersey</option>

<option value = "Jordan" data-states="kerak|tafela|zarqa|mafraq|madaba|jarash|aqaba|ajlun|ama|irbid|maan|belga">Jordan</option>

<option value = "Kazakhstan" data-states="">Kazakhstan</option>

<option value = "Kenya" data-states="central|coast|eastern|nairobi|north-eastern|nyanza|rift valley|western">Kenya</option>

<option value = "Kiribati" data-states="">Kiribati</option>

<option value = "Kuwait" data-states="al kuwayt|hawalli|al ahmadi|jahra|farwaniya">Kuwait</option>

<option value = "Kyrgyzstan" data-states="">Kyrgyzstan</option>

<option value = "Laos" data-states="bokeo|khammuan|viangchan|xekong|fong sali|salavan|namtha|loang prabang|xam nua|khong|xayabury|udomxay|atpu|svannakhet|borikane|xiengkhuang">Laos</option>

<option value = "Latvia" data-states="wolmar|valga|windau|saldus|riga|tukkum|talsi|mitau|gulbene|dobele|dvinsk|wenden|bauska|balvi|aluksne|zemgale|limbai|preili|latgale|riga|ludza|madona|ogre|jekabpils|kraslava|kurzeme|libau">Latvia</option>

<option value = "Lebanon" data-states="liban-nord|bekaa|liban-nord|mont-liban|mont-liban|bayrut|an nabatiyah|sayda">Lebanon</option>

<option value = "Lesotho" data-states="">Lesotho</option>

<option value = "Liberia" data-states="bong|nimba|river gee|gbapolu|lofa|grandkru|margibi|montserrado|bomi|grand cape mount|maryland|sinoe|grand bassa|nimba|rivercess|grandgedeh">Liberia</option>

<option value = "Libya" data-states="al jfara|al-ghufra|al-kufra|beida|al-hizam al-ahdar|taghura&quot; wa-n-nawahi al-arba|surt|zlitan|ajdabiya|homs|yafran|tobruk|shati|gat|zavia|homs|nigat al khums|gadamis|mizda|bengasi|al-qubba|murzuq|misratah|sawfajjin|sabha">Libya</option>

<option value = "Liechtenstein" data-states="">Liechtenstein</option>

<option value = "Lithuania" data-states="">Lithuania</option>

<option value = "Luxembourg" data-states="">Luxembourg</option>

<option value = "Macao S.A.R., China" data-states="">Macao S.A.R., China</option>

<option value = "Macedonia" data-states="sopiste|saraj|kavadartsi|centar|vevcani|tip|southwestern|southeastern|gazi baba|konce|ohrid|ilinden|jegunovce|vranetica|polog|eastern|kruevo|krivogatani|phecevo|kratovo|veles|butel|brvenica|southeastern|bogovinje|novatsi|caka|novo selo|kocani|gostivar|southwestern|bogdanci|prilep|probistip|radovis|kriva palanka|resen|vardar|vinitsa|kumanovo|demir hisar|centar upa|delcevo|gnjilane|debar|skopje|demir kapija|dolneni|pelagonia|zajas|brod|elino|zelenikovo|karbinci|karpo|mavrovo and rostusa|kicevo|n.a.|lipkovo|negotino|berovo|bitola|tetovo|vasilevo|valandovo|skopje|ohrid|cair|studenicani|southeastern|oslomej|tearce|sveti nikole">Macedonia</option>

<option value = "Madagascar" data-states="fianarantsoa|majunga|antseranana|toleary|tamatave|tananarive">Madagascar</option>

<option value = "Malawi" data-states="">Malawi</option>

<option value = "Malaysia" data-states="perlis|penang|kedah|kelantan|johor|pahang|perak|melaka|negri sembilan|sabah|selangor|selangor|sarawak|trengganu|selangor">Malaysia</option>

<option value = "Maldives" data-states="">Maldives</option>

<option value = "Mali" data-states="">Mali</option>

<option value = "Malta" data-states="">Malta</option>

<option value = "Marshall Islands" data-states="">Marshall Islands</option>

<option value = "Martinique" data-states="">Martinique</option>

<option value = "Mauritania" data-states="">Mauritania</option>

<option value = "Mauritius" data-states="">Mauritius</option>

<option value = "Mayotte" data-states="">Mayotte</option>

<option value = "Mexico" data-states="durango|guanajuato|guerrero|hidalgo|jalisco|mexico|michoacan|morelos|nayarit|nuevo leon|tabasco|quintana roo|veracruz|yucatan|queretaro|baja california sur|baja california|aguascalientes|coahuila|chihuahua|chiapas|yucatan|puebla|oaxaca|distrito federal|colima|sinaloa|san luis potosi|sonora|tlaxcala|tamaulipas">Mexico</option>

<option value = "Micronesia" data-states="">Micronesia</option>

<option value = "Moldova" data-states="">Moldova</option>

<option value = "Monaco" data-states="">Monaco</option>

<option value = "Mongolia" data-states="">Mongolia</option>

<option value = "Montenegro" data-states="roaje|avnik|kotor|bar">Montenegro</option>

<option value = "Montserrat" data-states="">Montserrat</option>

<option value = "Morocco" data-states="marrakech - tensift - al haouz|oriental|grand casablanca|souss - massa - draa|tanger - tetouan|taza - al hoceima - taounate|fes - boulemane|meknes - tafilalet|tadla - azilal|laayoune - boujdour - sakia el hamra|guelmim - es-semara|ed dakhla|doukkala - abda|gharb - chrarda - beni hssen|rabat - sale - zemmour - zaer|chaouia - ouardigha">Morocco</option>

<option value = "Mozambique" data-states="">Mozambique</option>

<option value = "Myanmar" data-states="">Myanmar</option>

<option value = "Namibia" data-states="erongo|oshana|karas|khomas|kunene|caprivi|kavango|ohangwena|omaheke|oshikoto|hardap">Namibia</option>

<option value = "Nauru" data-states="">Nauru</option>

<option value = "Nepal" data-states="janakpur|lumbini|mahakali|karnali|eastern|gorkha|rapti|banke|achham|bhaktapur|narayani|mechi|dhaulagiri|sagarmatha">Nepal</option>

<option value = "Netherlands" data-states="flevoland|frise|geldern|drenthe|nort|north holland|groninga|limburg|overijssel|utrecht|zuid-holland|eeklo">Netherlands</option>

<option value = "New Caledonia" data-states="">New Caledonia</option>

<option value = "New Zealand" data-states="nelson|canterbury|bay of plenty|auckland|west coast|northland|otago|marlborough|hawkes bay|wanganui-mawanatu|gisborne|nelson|southland|taranaki">New Zealand</option>

<option value = "Nicaragua" data-states="managua|carazo|nicaragua|atlantico norte|matagalpa|chinandega|masaya|boaco|nueva segovia|jinotega|granada|esteli|chontales|atlantico sur|madriz|leon">Nicaragua</option>

<option value = "Niger" data-states="">Niger</option>

<option value = "Nigeria" data-states="taraba|osun|borno|kebbi|enugu|bauchi|kaduna|abia|plateau|ondo|kano|imo|lagos|anambra|yobe|katsina|kogi|benue|akwa ibom|ogun|rivers|ekiti|bayelsa|ebonyi|gombe|nassarawa|zamfara|abyei|cross river|south kordufan|abuja|jigawa|oyo|kwara|niger|ondo|edo|adamawa">Nigeria</option>

<option value = "Niue" data-states="">Niue</option>

<option value = "Norfolk Island" data-states="">Norfolk Island</option>

<option value = "Northern Mariana Islands" data-states="">Northern Mariana Islands</option>

<option value = "North Korea" data-states="">North Korea</option>

<option value = "Norway" data-states="romsdal|vestfold|troms|vest-agder|sor-trondelag|telemark|rogaland|nordre bergenhus|finnmark|buskerud|nord-trondelag|opland|oslo|nordland|hordaland|astfold|oslo|hedmark|nedenes">Norway</option>

<option value = "Oman" data-states="">Oman</option>

<option value = "Pakistan" data-states="northern areas|kashmir|sind|punjab|n.w.f.p.|balochistan|f.a.t.a.|f.c.t.">Pakistan</option>

<option value = "Palestinian Territory" data-states="">Palestinian Territory</option>

<option value = "Panama" data-states="">Panama</option>

<option value = "Papua New Guinea" data-states="">Papua New Guinea</option>

<option value = "Paraguay" data-states="asuncion|boqueron|san pedro|misiones|neembucu|guaira|itapua|presidente hayes|cordillera|paraguari|caazapa|caaguazu|concepcion|canindeyu|alto parana|alto paraguay|amambay">Paraguay</option>

<option value = "Peru" data-states="ayacucho|san mart|tacna|cusco|callao|piura|ucayali|tumbez|amazonas|apurimac|ancash|arequipa|cajamarca|lima|moquegua|pasco|loreto|madre de dios|lambayeque|lima|junnn|libertad|huknuco|ica">Peru</option>

<option value = "Philippines" data-states="siquijor|agusan del norte|cebu|quezon|negros occidental|metropolitan manila|pangasinan|quirino|rizal|samar|south cotabato|north cotabato|maguindanao|southern leyte|sorsogon|catanduanes|leyte|la union|lanao del sur|lanao del norte|laguna|apayao|isabela|iloilo|camarines norte|tawi-tawi|sultan kudarat|camarines sur|bohol|benguet|masbate|bukidnon|agusan del sur|basilan|pampanga|eastern samar|capiz|zamboanga del sur|aurora|marinduque|cagayan|sabah|bulacan|camiguin|romblon|negros oriental|nueva ecija|mountain province|tarlac|misamis occidental|misamis oriental|occidental mindoro|oriental mindoro|ilocos norte|nueva vizcaya|paragua|davao|sarangani|davao oriental|abra|antique|cavite|aklan|albay|sulu|dinagat islands|surigao del sur|batangas|zambales|ilocos sur|zamboanga del sur|northern samar">Philippines</option>

<option value = "Pitcairn" data-states="">Pitcairn</option>

<option value = "Poland" data-states="pomorskie|warmian-masurian|lublin|swietokrzyskie|malopolskie|opole|wielkopolskie|slaskie|west pomeranian|podkarpackie|warmian-masurian|dolnoslaskie|lodz|kujawsko-pomorskie|lubusz|masovian|podlaskie">Poland</option>

<option value = "Portugal" data-states="beja|evora|portalegre|guarda|aveiro|porto|madeira|lisboa|vila real|leiria|coimbra|castelo branco|braganca|braga|santarem|viana do castelo|azores|viseu|faro">Portugal</option>

<option value = "Puerto Rico" data-states="">Puerto Rico</option>

<option value = "Qatar" data-states="al rayyan|al shamal|al khor|al wakrah|al ghuwayriyah|al gummaylah|doha|jerian al|um salal">Qatar</option>

<option value = "Reunion" data-states="">Reunion</option>

<option value = "Romania" data-states="vrancea|galatz|gorj|vaslui|valcea|suceava|cluj|bukarest|buzau|dambovita|salaj|constanta|sibiu|teleorman|timis|bihor|bacau|botosani|bistritz|alba|arges|arad|calarasi|prahova|giurgiu|brasov|braila|dolj|satu mare|olt|neamt|mures|maramures|covasna|iasi|ialomita|hunedoara|harghita">Romania</option>

<option value = "Russia" data-states="karelia|penza|lipetsk|pskov|kamchatka|kirov|belgorod|mari|bashkir|ivanovo|mordov|kursk|kurgan|kaluga|kalmyk|adygey|oirot|aga buryat|amur|altay|astrachan|vologda|simbirsk|udmurt|vladimir|ust-orda buryat|vologda|volgograd|yamal-nenets|kuzey osetya|tambov|yaroslavl&quot;|samara|irkutsk|chukot|ryazan&quot;|rostov|kazan|kaliningrad|smolensk|kabard|voronezh|mosca|maga buryatdan|leningrad|novgorod|chukchi autonomous okrug|kemerovo|tomsk|sakhalin|moskovsskaya|evrey|tula|saratov|khabarovsk|khakass|k|gorky|komi|stavropol&quot;|evenk|ingush|chuvash|dagistan|chita|kostroma|cecenia|chelyabinsk|bryansk|buryat|kuban|primorsk|tyumen&quot;|tyva|perm&quot;|yeniseisk|taymyr|novosibirsk|nenets|tver&quot;|orel|sverdlovsk|omsk|samara">Russia</option>

<option value = "Rwanda" data-states="kigali|byumba|kibungu|kibuye|butare|shangugu">Rwanda</option>

<option value = "Saint Barth&eacute;lemy" data-states="">Saint Barth&eacute;lemy</option>

<option value = "Saint Helena" data-states="">Saint Helena</option>

<option value = "Saint Kitts and Nevis" data-states="">Saint Kitts and Nevis</option>

<option value = "Saint Lucia" data-states="">Saint Lucia</option>

<option value = "Saint Martin (French part)" data-states="">Saint Martin (French part)</option>

<option value = "Saint Martin (Dutch part)" data-states="">Saint Martin (Dutch part)</option>

<option value = "Saint Pierre and Miquelon" data-states="">Saint Pierre and Miquelon</option>

<option value = "Saint Vincent and the Grenadines" data-states="">Saint Vincent and the Grenadines</option>

<option value = "San Marino" data-states="">San Marino</option>

<option value = "S&atilde;o Tom&eacute; and Pr&iacute;ncipe" data-states="">S&atilde;o Tom&eacute; and Pr&iacute;ncipe</option>

<option value = "Saudi Arabia" data-states="riad|baha|gasim|jowf|tabuk|najran|jazan|medina|ash sharqiyah|meca|hail|northern frontier|asir|jowf">Saudi Arabia</option>

<option value = "Senegal" data-states="">Senegal</option>

<option value = "Serbia" data-states="">Serbia</option>

<option value = "Seychelles" data-states="">Seychelles</option>

<option value = "Sierra Leone" data-states="">Sierra Leone</option>

<option value = "Singapore" data-states="">Singapore</option>

<option value = "Slovakia" data-states="">Slovakia</option>

<option value = "Slovenia" data-states="">Slovenia</option>

<option value = "Solomon Islands" data-states="">Solomon Islands</option>

<option value = "Somalia" data-states="">Somalia</option>

<option value = "South Africa" data-states="kwazulu-natal|mpumalanga|oos-kaap|vrystaat|noordwes|limpopo|wes-kaap|gauteng">South Africa</option>

<option value = "South Georgia/Sandwich Islands" data-states="">South Georgia/Sandwich Islands</option>

<option value = "South Korea" data-states="taegu|pusan|kyunggi|jinsen|taegu|jeonrabugdo|chusei nan-do|jeju|taiden|seul|kwangju|chusei hoku-do|kwangju|keisho nan-do">South Korea</option>

<option value = "South Sudan" data-states="">South Sudan</option>

<option value = "Spain" data-states="extremadura|castela-mancha|baleari|galiza|valencia|navarra|cav|asturie|cav|murcia|madrid|canillo|aragon|andaluzia|ceuta">Spain</option>

<option value = "Sri Lanka" data-states="">Sri Lanka</option>

<option value = "Sudan" data-states="">Sudan</option>

<option value = "Suriname" data-states="">Suriname</option>

<option value = "Svalbard and Jan Mayen" data-states="">Svalbard and Jan Mayen</option>

<option value = "Sweden" data-states="orebro|norrbotten|ostergotland|skane|dalarna|kronoberg|gotland|uppsala|blekinge|jonkoping|kalmar|vastmanland|vasternorrland|gavleborg|uppsala|vastra gotaland|halland|jamtland|varmland|vasterbotten">Sweden</option>

<option value = "Switzerland" data-states="solothurn|thurgau|obwald|son gagl|sciaffusa|schwyz|appenzell dador|luzern|neuchatel|nidwald|turitg|zug|jura|uri|ticino|wallis|grisons|glaris|genf|vad|bern|basel-city|appenzell dadens|aargau">Switzerland</option>

<option value = "Syria" data-states="">Syria</option>

<option value = "Taiwan" data-states="gaoxiong|jiayi|zhanghua|hualia|jiayi|taibei|gaoxiong shi|yunlin|taizhong shi|penghu|taidong|taoyuan|miaoli|pingdong|taizhong|tainan shi|tainan|xinzhu|xinzhu|jilong shi|ilan|chinmen">Taiwan</option>

<option value = "Tajikistan" data-states="">Tajikistan</option>

<option value = "Tanzania" data-states="lindi|rukwa|dodoma|kagera|tanga|shinyanga|ruvuma|tabora|singida|mtwara|morogoro|pemba north|mwanza|pemba south|iringa|kigoma|pwani|zanzibar west|arusha|manyara|kusini unguja|zanzibar north|dar es salaam|mara|mbeya">Tanzania</option>

<option value = "Thailand" data-states="mae hong son|phayao|phatthalung|nakhon phanom|ranong|chumphon|korh luxk|phet buri|mahachai|mae klong|nakhon pathom|ratchaburi|suphan buri|kan buri|chaiyaphum|lampang|surat thani|pathum thani|yasothon|prachin buri|amnat charoen|phuket|chiang mai|uttaradit|nan|lamphun|buri rum|surin|tak|sukothai|maha sarakham|roi et|khon kaen|chiang rai|sakon nakhon|nakhon sawan|songkhla|pattani|muang chan|trat|pad rew|krabi|chon buri|phrae|bangkok|satun|nakhon thammarat|nakon nayok|ubon ratchathani|pak nam|mukdahan|sa kaeo|trang|rayong|loei|ang thong|nonthaburi|kalasin|phichit|bisnulok|kambhengbhej|udon thani|nong khai|yala|uthai thani|phetchabun|narathiwat|khukhan|sing buri|khorat|nong bua lamphu|lop buri|saraburi|ayudhya">Thailand</option>

<option value = "Timor-Leste" data-states="">Timor-Leste</option>

<option value = "Togo" data-states="">Togo</option>

<option value = "Tokelau" data-states="">Tokelau</option>

<option value = "Tonga" data-states="">Tonga</option>

<option value = "Trinidad and Tobago" data-states="">Trinidad and Tobago</option>

<option value = "Tunisia" data-states="">Tunisia</option>

<option value = "Turkey" data-states="kastamonu|batman|bayburt|adiyaman|hatay|hakkari|konya|mardin|nigde|siirt|aksaray|sivas|tekirdag|karaman|sinop|sakarya|isparta|ordu|rize|nevsehir|corum|kayseri|kirklareli|stamboul|bitlis|balikesir|bilecik|bursa|canakkale|bolu|burdur|adalia|kinkkale|mus|malatya|mersin|gumushane|afyon|aydin|urfa|tunceli|trabzon|tokat|yozgat|van|usak|izmit|kirsehir|mugla|ankara|manisa|artvin|k. maras|amasya|agri|giresun|kutahya|eskisehir|erzurum|erzincan|elazig|edirne|diyarbakir|denizli|bingol|samsun|izmir|zinguldak|kars|adana|sirnak|gaziantep|changra">Turkey</option>

<option value = "Turkmenistan" data-states="">Turkmenistan</option>

<option value = "Turks and Caicos Islands" data-states="">Turks and Caicos Islands</option>

<option value = "Tuvalu" data-states="">Tuvalu</option>

<option value = "Uganda" data-states="">Uganda</option>

<option value = "Ukraine" data-states="">Ukraine</option>

<option value = "United Arab Emirates" data-states="neutral zone|dibay|abu dabi|chardja|qaiwan|fujayrah">United Arab Emirates</option>

<option value = "United States (US) Minor Outlying Islands" data-states="">United States (US) Minor Outlying Islands</option>

<option value = "United States (US) Virgin Islands" data-states="">United States (US) Virgin Islands</option>

<option value = "Uruguay" data-states="">Uruguay</option>

<option value = "Uzbekistan" data-states="">Uzbekistan</option>

<option value = "Vanuatu" data-states="">Vanuatu</option>

<option value = "Vatican" data-states="">Vatican</option>

<option value = "Venezuela" data-states="">Venezuela</option>

<option value = "Vietnam" data-states="long an|soc trang|qung tr|an giang|tha thien - hu|dak lak|ninh thun|cao bng|qung binh|kien giang|ho chi minh|lam dong|dien bien|tuyen quang|gia lai|binh thun|binh djnh|ba ria - vtau|thai nguyen|south east|ninh binh|bn tre|dong thap|phu th|nam dinh|red river delta|ha nam|vinh phuc|thai nguyen|dja nng|khanh hoa|qung nam|phu yen|yen bai|qung ninh|qung ngai|lng son|ha giang|thanh hoa|thai binh|son la|tay ninh|qung ninh|ha tay|dja nng|hi duong|ngh an|bc ninh|djong bc|bc lieu|lao cai|bc giang|binh phuc|ca mau|ha tinh|binh duong">Vietnam</option>

<option value = "Wallis and Futuna" data-states="">Wallis and Futuna</option>

<option value = "Western Sahara" data-states="">Western Sahara</option>

<option value = "Samoa" data-states="">Samoa</option>

<option value = "Yemen" data-states="">Yemen</option>

<option value = "Zambia" data-states="">Zambia</option>

<option value = "Zimbabwe" data-states="">Zimbabwe</option>
			</select>
		</div>

		<div class = "one_half last">
			<select id = "hdc_tax_state" name = "hdc_tax_state">
				<option rel = "hide" value = "">Select State/Province/Territory</option>
			</select>
		</div>
		<div class = "clear"></div>
		</div>
	</div>