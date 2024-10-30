<?php
/*
	HDCommerce Settings Main Tab
	Contains:
*/
?>

<div class="hdc_tab" id="hdc_settings_main">

	<div class = "hdc_setting_row">
		<label for="hdc_store_currency">Select your billing currency <a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>Please contact us if your currency is not listed here. We can add new currencies very easily.</span></span></a></label>
		<select id="hdc_store_currency" name="hdc_store_currency" class="hdc_select">
			<option value="hide">Shop currency</option>
			<!-- Currency | Symbol | Position (left or right of value) -->
			<option value="USD|$|l" <?php if($hdc_store_currency == "USD|$|l" ) {echo "selected";} ?>>USD - United States</option>
			<option value="CAD|$|l" <?php if($hdc_store_currency == "CAD|$|l" ) {echo "selected";} ?>>CAD - Canada</option>
			<option value="AUD|$|l" <?php if($hdc_store_currency == "AUD|$|l" ) {echo "selected";} ?>>AUD - Australian Dollar</option>
			<option value="EUR|€|l" <?php if($hdc_store_currency == "EUR|€|l" ) {echo "selected";} ?>>EUR - Euro</option>
			<option value="GBP|£|l" <?php if($hdc_store_currency == "GBP|£|l" ) {echo "selected";} ?>>GBP - Pound Sterling (Great British Pound)</option>
			<option value="NZD|$|l" <?php if($hdc_store_currency == "NZD|$|l" ) {echo "selected";} ?>>NZD - New Zealand Dollar</option>
		</select>
	</div>

			<h3>Store Information <a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>This information is used throughout HDCommerce for various needs such as shipping or recipt/order generation.</span></span></a></h3>

	<div class = "hdc_setting_row">

		<div class = "hdc_row">
			<div class="hdc_input-effect">
				<input class="hdc_input <?php if($hdc_store_name != null && $hdc_store_name != " ") {echo 'has-content';} ?>" id="hdc_store_name" name="hdc_store_name" type="text" value="<?php echo $hdc_store_name; ?>">
				<label for="hdc_store_name">Enter your store/company name</label>
				<span class="focus-border"></span>
			</div>
		</div>

		<div class = "hdc_row">
			<div class = "one_half">
				<div class="hdc_input-effect">
					<input class="hdc_input <?php if($hdc_store_address != null && $hdc_store_address != " ") {echo 'has-content';} ?>" id="hdc_store_address" name="hdc_store_address" type="text" value="<?php echo $hdc_store_address; ?>">
					<label for="hdc_store_address">Enter your store address</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "one_half last">
				<div class="hdc_input-effect">
					<input class="hdc_input <?php if($hdc_store_address2 != null && $hdc_store_address2 != " ") {echo 'has-content';} ?>" id="hdc_store_address2" name="hdc_store_address2" type="text" value="<?php echo $hdc_store_address2; ?>">
					<label for="hdc_store_address2">Enter your store address unit #</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "clear"></div>
		</div>

		<div class = "hdc_row">
			<div class = "one_half">
				<div class="hdc_input-effect">
					<input class="hdc_input <?php if($hdc_store_city != null && $hdc_store_city != " ") {echo 'has-content';} ?>" id="hdc_store_city" name="hdc_store_city" type="text" value="<?php echo $hdc_store_city; ?>">
					<label for="hdc_store_city">Enter your store city</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "one_half last">
				<div class="hdc_input-effect">
					<input class="hdc_input <?php if($hdc_store_state != null && $hdc_store_state != " ") {echo 'has-content';} ?>" id="hdc_store_state" name="hdc_store_state" type="text" value="<?php echo $hdc_store_state; ?>">
					<label for="hdc_store_state">Enter your store state/province</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "clear"></div>
		</div>

		<div class = "hdc_row">
			<div class = "one_half">
				<div class="hdc_input-effect">
					<input class="hdc_input <?php if($hdc_store_country != null && $hdc_store_country != " ") {echo 'has-content';} ?>" id="hdc_store_country" name="hdc_store_country" type="text" value="<?php echo $hdc_store_country; ?>">
					<label for="hdc_store_country">Enter your store country</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "one_half last">
				<div class="hdc_input-effect">
					<input class="hdc_input <?php if($hdc_store_zip != null && $hdc_store_zip != " ") {echo 'has-content';} ?>" id="hdc_store_zip" name="hdc_store_zip" type="text" value="<?php echo $hdc_store_zip; ?>">
					<label for="hdc_store_zip">Enter your store zip/postal code</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "clear"></div>
		</div>

		<div class = "hdc_row">
			<div class = "one_half">
				<div class="hdc_input-effect">
					<input class="hdc_input <?php if($hdc_store_phone != null && $hdc_store_phone != " ") {echo 'has-content';} ?>" id="hdc_store_phone" name="hdc_store_phone" type="text" value="<?php echo $hdc_store_phone; ?>">
					<label for="hdc_store_phone">Enter your store sales phone number</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "one_half last">
				<div class="hdc_input-effect">
					<input type = "email" class="hdc_input <?php if($hdc_store_email != null && $hdc_store_email != " ") {echo 'has-content';} ?>" id="hdc_store_email" name="hdc_store_email" type="text" value="<?php echo $hdc_store_email; ?>">
					<label for="hdc_store_email">Enter your store sales email address</label>
					<span class="focus-border"></span>
				</div>
			</div>
			<div class = "clear"></div>
		</div>


	</div>

	<div class = "hdc_setting_row">
		<div id = "hdc_select_countries">
			<label for="hdc_store_countries">Select each country you will be selling to <a class = "hdc_tooltip">?<span class="hdc_tooltip_line" style="transform: matrix(0, 0, 0, 1, 0, 0);"></span><span class="hdc_tooltip_content"><span>Only residents of the selected countries will be able to checkout.</span></span></a></label>

			<div id = "hdc_select_countries_button" class = "hdc_button hdc_reverse">
				Select Countries
			</div>

			<br/><br/>
			<div id = "hdc_select_countries_model_wrap">
				<div id = "hdc_select_countries_model_close">X</div>
				<div id = "hdc_select_countries_model">
					<h3>Please select each country you will be selling in</h3>
					<!-- TODO! Import this from a CSV -->
					<!-- list of countries -->
					<div class="hdc_select_country" data-country-code="US" data-states="Alabama|Arizona|Arkansas|California|Colorado|Connecticut|Delaware|Florida|Georgia|Idaho|Illinois|Indiana|Iowa|Kansas|Kentucky|Louisiana|Maine|Maryland|Massachusetts|Michigan|Minnesota|Mississippi|Missouri|Montana|Nebraska|Nevada|New Hampshire|New Jersey|New Mexico|New York|North Carolina|North Dakota|Ohio|Oklahoma|Oregon|Pennsylvania|Rhode Island|South Carolina|South Dakota|Tennessee|Texas|Utah|Vermont|Virginia|Washington|West Virginia|Wisconsin|Wyoming">United States</div>

					<div class="hdc_select_country" data-country-code="CA" data-states="Alberta|British Columbia|Manitoba|New Brunswick|Newfoundland and Labrador|Northwest Territories|Nova Scotia|Nunavut|Ontario|Prince Edward Island|Quebec|Saskatchewan|Yukon">Canada</div>

					<!-- new -->
					<div class="hdc_select_country" data-country-code="AF" data-states="balh|jozjan|samangan|saripol|kunar|lagman|paktya|nimroz|nuristan|logar|kapisa|cabul|hilmand|hirat|qandahar|uruzgan|parwan|parwan|uruzgan|baglan|gawr|gazni|badgis|verdak|takar|qunduz|badakhshan|paktya|zabul|bamyan|faryab|farah|khost">Afghanistan</div>

					<div class="hdc_select_country" data-country-code="AX" data-states="">&#197;land Islands</div>

					<div class="hdc_select_country" data-country-code="AL" data-states="elbasan|durres|lezhe|korce|gjirokaster|fier|durres|vlora|dibra|berat">Albania</div>

					<div class="hdc_select_country" data-country-code="DZ" data-states="tihert|stif|saida|tlemcen|tizi ouzou|skidda|sidi bel abbes|biskra|bugia|ain defla|adrar|bona|ain tamouchent|batna|vialar|tipaza|tiaret|tamenghest|souk ahras|relizane|wargla|mestghanem|bechar|tbessa|argel|msila|medea|canrobert|mascara|oran|jijel|laghouat|djelfa|guelma|blida|bouira|mila|naama|el tarf|gharday|ilizi|khenchela|boumerdes|chlef|el bayad|biskra|borjbouarirej">Algeria</div>

					<div class="hdc_select_country" data-country-code="AS" data-states="">American Samoa</div>

					<div class="hdc_select_country" data-country-code="AD" data-states="">Andorra</div>

					<div class="hdc_select_country" data-country-code="AO" data-states="">Angola</div>

					<div class="hdc_select_country" data-country-code="AI" data-states="">Anguilla</div>

					<div class="hdc_select_country" data-country-code="AQ" data-states="">Antarctica</div>

					<div class="hdc_select_country" data-country-code="AG" data-states="">Antigua and Barbuda</div>

					<div class="hdc_select_country" data-country-code="AR" data-states="la rioja|jujuy|el pampa|rio negro|salta|misiones|neuquen|feuerland|san juan|san luis|santiago del estero|tucuman|formosa|mendoza|santa fe|santa cruz|cordoba|chubut|corrientes|capital federal|chaco|catamarca">Argentina</div>

					<div class="hdc_select_country" data-country-code="AM" data-states="erevan|armavir|aragatsotn|lori|shirak|gegharkunik|kotayk">Armenia</div>

					<div class="hdc_select_country" data-country-code="AW" data-states="">Aruba</div>

					<div class="hdc_select_country" data-country-code="AU" data-states="queensland|south australia|unknown|victoria|australian capital territory|northern territory|western australia">Australia</div>

					<div class="hdc_select_country" data-country-code="AT" data-states="karnten|niederdonau|burgenland|styria|tyrol|alta austria|salzburg|vorarlberg|wien">Austria</div>

					<div class="hdc_select_country" data-country-code="AZ" data-states="shusha|kurdmir|klbcr|ismailli|imisli|goycay|goranboy|gnc|dvci|bilsuvar|salyan|agstafa|xocali|tovuz|zaqatala|zrdab|syunik|xacmaz|qbl|ucar|sdrk|trtr|sumqayit|susa|balakn|samaxi|smkir|fizuli|gdby|qax|beylqan|babk|neftcala|brd|dasksn|lerik|xocavnd|clilabad|mingcevir|qubadli|yevlax|srur|astara|xizi|agdas|li bayramli|agsu|baki|yardimli|agdam|qobustan|zngilan|quba|saatli|qusar|sabirabad">Azerbaijan</div>

					<div class="hdc_select_country" data-country-code="BS" data-states="">Bahamas</div>

					<div class="hdc_select_country" data-country-code="BH" data-states="">Bahrain</div>

					<div class="hdc_select_country" data-country-code="BD" data-states="barisal|sylhet|daca|daca|rajshahi|khulna">Bangladesh</div>

					<div class="hdc_select_country" data-country-code="BB" data-states="christ church|saint thomas|saint george|saint james|saint john|saint joseph|saint lucy|saint michael|saint peter|saint andrew|saint philip">Barbados</div>

					<div class="hdc_select_country" data-country-code="BY" data-states="mogilev|witebsk|minsk|brest|hrodna|homje">Belarus</div>

					<div class="hdc_select_country" data-country-code="BE" data-states="vlaams brabant|limburg|namur|walloon brabant|thuin|hainaut|luxembourg|liege|antwerp|hal-vilvorde|west flanders|tielt|ath|namur|virton|maaseik|huy|brussels-capital|bruselas|anvers|ostflandern">Belgium</div>

					<div class="hdc_select_country" data-country-code="PW" data-states="">Belau</div>

					<div class="hdc_select_country" data-country-code="BZ" data-states="cayo|corozal|belize|toledo|orange walk|stann creek">Belize</div>

					<div class="hdc_select_country" data-country-code="BJ" data-states="">Benin</div>

					<div class="hdc_select_country" data-country-code="BM" data-states="">Bermuda</div>

					<div class="hdc_select_country" data-country-code="BT" data-states="">Bhutan</div>

					<div class="hdc_select_country" data-country-code="BO" data-states="santa cruz|tarija|pando|potosi|la paz|oruro|cochabamba|el beni|chuquisaca">Bolivia</div>

					<div class="hdc_select_country" data-country-code="BQ" data-states="">Bonaire, Saint Eustatius and Saba</div>

					<div class="hdc_select_country" data-country-code="BA" data-states="">Bosnia and Herzegovina</div>

					<div class="hdc_select_country" data-country-code="BW" data-states="">Botswana</div>

					<div class="hdc_select_country" data-country-code="BV" data-states="">Bouvet Island</div>

					<div class="hdc_select_country" data-country-code="BR" data-states="sergipe|distrito federal|acre|alagoas|amapa|amazone|bahia|ceara|goias|espirito santo|rondonia|roraima|rio grande do norte|rio grande do sul|piaui|rio de janeiro|parana|maranhao|mato grosso do sul|paraiba|para|minas|mato grosso|tocantins|pernambouc|sao paulo|santa catarina">Brazil</div>

					<div class="hdc_select_country" data-country-code="IO" data-states="">British Indian Ocean Territory</div>

					<div class="hdc_select_country" data-country-code="VG" data-states="">British Virgin Islands</div>

					<div class="hdc_select_country" data-country-code="BN" data-states="">Brunei</div>

					<div class="hdc_select_country" data-country-code="BG" data-states="pernik|slivno|sofia|haskovo|plovdiv|ruse|lovec|varna">Bulgaria</div>

					<div class="hdc_select_country" data-country-code="BF" data-states="">Burkina Faso</div>

					<div class="hdc_select_country" data-country-code="BI" data-states="">Burundi</div>

					<div class="hdc_select_country" data-country-code="KH" data-states="">Cambodia</div>

					<div class="hdc_select_country" data-country-code="CM" data-states="">Cameroon</div>

					<div class="hdc_select_country" data-country-code="CV" data-states="">Cape Verde</div>

					<div class="hdc_select_country" data-country-code="KY" data-states="">Cayman Islands</div>

					<div class="hdc_select_country" data-country-code="CF" data-states="">Central African Republic</div>

					<div class="hdc_select_country" data-country-code="TD" data-states="">Chad</div>

					<div class="hdc_select_country" data-country-code="CL" data-states="ix|atacama|vii|coquimbo|tarapaca|rm|aisen del general carlos ibanez del campo|magallanes y antartica chilena|antofagasta|libertador|x|arica y parinacota|aconcagua">Chile</div>

					<div class="hdc_select_country" data-country-code="CN" data-states="sichuan|gansu|yunnan|zhejiang|hubei|xinjiang uygur|hebei|hunan|guangxi|hainan|tibet|chongqing|guizhou|liaoning|shanghai|guangdong|heilongjiang|shanxi|shandong|shanxi|henan|tianjin|ningxia hui|nei mongol|jilin|jiangsu|fujian|gansu|anhui|jiangxi|tianjin">China</div>

					<div class="hdc_select_country" data-country-code="CX" data-states="">Christmas Island</div>

					<div class="hdc_select_country" data-country-code="CC" data-states="zhejiang">Cocos (Keeling) Islands</div>

					<div class="hdc_select_country" data-country-code="CO" data-states="putumayo|antioquia|arauca|santander|amazonas|narino|norte de santander|atlantico|quindio|caqueta|cauca|tolima|caldas|boyaca|bolivar|bogota|meta|magdalena|vichada|vaupes|guania|guaviare|guajira|huila|choco|cesar|cordoba|risaralda|cundinamarca|sucre|casanare">Colombia</div>

					<div class="hdc_select_country" data-country-code="KM" data-states="">Comoros</div>

					<div class="hdc_select_country" data-country-code="CG" data-states="">Congo (Brazzaville)</div>

					<div class="hdc_select_country" data-country-code="CD" data-states="">Congo (Kinshasa)</div>

					<div class="hdc_select_country" data-country-code="CK" data-states="">Cook Islands</div>

					<div class="hdc_select_country" data-country-code="CR" data-states="san jose|limon|puntarenas|heredia|cartago|guanacaste|alajuela">Costa Rica</div>

					<div class="hdc_select_country" data-country-code="HR" data-states="una-sana|karlovac|koprivnicko-krievacka|split-dalmacia|varasd|zagreb|grad zagreb|ibensko-kninska|lika-senj|medimurje|brodsko-posavska|istra|primorsko-goranska|brodsko-posavska|sisak-moslavina|virovitica-podravina|bjelovar-bilagora">Croatia</div>

					<div class="hdc_select_country" data-country-code="CU" data-states="guantanamo|camaguey|cienfuegos|granma|la habana|holguin|las tunas|pinar del rio|ciudad de la habana|matanzas|isla de la juventud|villa clara|santiago de cuba|sancti spiritus">Cuba</div>

					<div class="hdc_select_country" data-country-code="CW" data-states="">Cura&ccedil;ao</div>

					<div class="hdc_select_country" data-country-code="CY" data-states="">Cyprus</div>

					<div class="hdc_select_country" data-country-code="CZ" data-states="morava|carlsbad|budweis|liberec|iglau|stredocesky|kralovehradecky|prag">Czech Republic</div>

					<div class="hdc_select_country" data-country-code="DK" data-states="hovedstaden|north|southern|central|zealand">Denmark</div>

					<div class="hdc_select_country" data-country-code="DJ" data-states="">Djibouti</div>

					<div class="hdc_select_country" data-country-code="DM" data-states="">Dominica</div>

					<div class="hdc_select_country" data-country-code="DO" data-states="espaillat|el seybo|el seybo|san pedro de macoris|barahona|santiago rodrigu|azua|samana|sanchez ramirez|dajabon|san juan|independencia|maria trinidad s|monte cristi|baoruco|pedernales|san rafael|la altagracia|la romana|hermanas|santo domingo|valverde|duarte|peravia|distrito nacional|trujillo|monte plata|monsenor nouel|la vega|puerto plata|santiago">Dominican Republic</div>

					<div class="hdc_select_country" data-country-code="EC" data-states="pichincha|los rios|loja|imbabura|guayas|pastaza|morona santiago|manabi|orellana|sucumbios|tungurahua|zamora chinchipe|el oro|esmeraldas|galapagos|azuay|bolivar|canar|carchi|chimborazo|cotopaxi|napo">Ecuador</div>

					<div class="hdc_select_country" data-country-code="EG" data-states="giza|munufia|matruh|garbia|dumyat|ismailia|sud sinai|dagahlia|sohag|behera|faium|north sinai|alexandria|kafr el sheik|canal|beni suef|asyut|aswan|suez|sharqia|al wadi at jadid|kalyubia|cairo|minya">Egypt</div>

					<div class="hdc_select_country" data-country-code="SV" data-states="">El Salvador</div>

					<div class="hdc_select_country" data-country-code="GQ" data-states="">Equatorial Guinea</div>

					<div class="hdc_select_country" data-country-code="ER" data-states="northern red sea|maekel|anseba|gash barka|debub|obock">Eritrea</div>

					<div class="hdc_select_country" data-country-code="EE" data-states="polva|rapla|parnu|laane-viru|voru|viljandi|jogeva|jarva|laane|valga|harju|ida-viru|hiiu|tartu|hiiu">Estonia</div>

					<div class="hdc_select_country" data-country-code="ET" data-states="">Ethiopia</div>

					<div class="hdc_select_country" data-country-code="FK" data-states="">Falkland Islands</div>

					<div class="hdc_select_country" data-country-code="FO" data-states="">Faroe Islands</div>

					<div class="hdc_select_country" data-country-code="FJ" data-states="rotuma|western|central|eastern|western">Fiji</div>

					<div class="hdc_select_country" data-country-code="FI" data-states="pirkanmaa|tavastia proper|central finland|lappi|oulu|eastern finland|western finland|northern ostrobothnia|paijanne tavastia|southern ostrobothnia|south karelia|southern finland|north karelia|southern savonia|satakunta|satakunta|eastern uusimaa">Finland</div>

					<div class="hdc_select_country" data-country-code="FR" data-states="upper normandy|franche-comte|pays de la loire|champagne-ardenne|centre|bretagne|burgundy|languedoc-rosellon|ile-de-france|lower normandy|auvergne|aquitaine|alsace|corse|lorena|midi-pyrenees|lemosin|picardie|poitou-charentes|nord-pas-de-calais|provence-alpes-cote-d&quot;azur|rhone-alpes">France</div>

					<div class="hdc_select_country" data-country-code="GF" data-states="">French Guiana</div>

					<div class="hdc_select_country" data-country-code="PF" data-states="">French Polynesia</div>

					<div class="hdc_select_country" data-country-code="TF" data-states="">French Southern Territories</div>

					<div class="hdc_select_country" data-country-code="GA" data-states="woleu-ntem|ogooue-maritime|moyen-ogooue|haut-ogooue|estuaire|ogooue-lolo|ogooue-ivindo|nyanga|ngounie">Gabon</div>

					<div class="hdc_select_country" data-country-code="GM" data-states="">Gambia</div>

					<div class="hdc_select_country" data-country-code="GE" data-states="guria|sokhumi|gori|qazax|akhaltsikhe|samux">Georgia</div>

					<div class="hdc_select_country" data-country-code="DE" data-states="nordrhein-westfalen|saarland|bremen|baden-wurttemberg|rheinland-pfalz|brandenburg|schleswig-holstein|hesse|hamburg|berlin|thuringia|saxony-anhalt|saxony|mecklenburg-vorpommern|bayern|bremen">Germany</div>

					<div class="hdc_select_country" data-country-code="GH" data-states="">Ghana</div>

					<div class="hdc_select_country" data-country-code="GI" data-states="">Gibraltar</div>

					<div class="hdc_select_country" data-country-code="GB" data-states="Bedfordshire|Buckinghamshire|Cambridgeshire|Cheshire|Cleveland|Cornwall|Cumbria|Derbyshire|Devon|Dorset|Durham|East Sussex|Essex|Gloucestershire|Greater London|Greater Manchester|Hampshire|Hertfordshire|Kent|Lancashire|Leicestershire|Lincolnshire|Merseyside|Norfolk|North Yorkshire|Northamptonshire|Northumberland|Nottinghamshire|Oxfordshire|Shropshire|Somerset|South Yorkshire|Staffordshire|Suffolk|Surrey|Tyne and Wear|Warwickshire|West Berkshire|West Midlands|West Sussex|West Yorkshire|Wiltshire|Worcestershire">England</div>

					<div class="hdc_select_country" data-country-code="GB" data-states="Antrim|Armagh|Down|Fermanagh|Derry and Londonderry|Tyrone">Northern Ireland</div>

					<div class="hdc_select_country" data-country-code="GB" data-states="Aberdeen City|Aberdeenshire|Angus|Argyll and Bute|City of Edinburgh|Clackmannanshire|Dumfries and Galloway|Dundee City|East Ayrshire|East Dunbartonshire|East Lothian|East Renfrewshire|Eilean Siar|Falkirk|Fife|Glasgow City|Highland|Inverclyde|Midlothian|Moray|North Ayrshire|North Lanarkshire|Orkney Islands|Perth and Kinross|Renfrewshire|Scottish Borders|Shetland Islands|South Ayrshire|South Lanarkshire|Stirling|West Dunbartonshire|West Lothian">Scotland</div>

					<div class="hdc_select_country" data-country-code="GB" data-states="Flintshire|Glamorgan|Merionethshire|Monmouthshire|Montgomeryshire|Pembrokeshire|Radnorshire|Anglesey|Breconshire|Caernarvonshire|Cardiganshire|Carmarthenshire|Denbighshire">Wales</div>

					<div class="hdc_select_country" data-country-code="GR" data-states="thessaly|peloponnese|attiki|ionioi nisoi|sterea ellada|greece west|notio aigaio|macedonia west|crete|peloponnese|kirjali|aegean north|ayion oros|macedonia central|epirus">Greece</div>

					<div class="hdc_select_country" data-country-code="GL" data-states="qaasuitsup|national park|qeqqata|kujalleq|sermersooq|pituffik">Greenland</div>

					<div class="hdc_select_country" data-country-code="GD" data-states="">Grenada</div>

					<div class="hdc_select_country" data-country-code="GP" data-states="">Guadeloupe</div>

					<div class="hdc_select_country" data-country-code="GU" data-states="">Guam</div>

					<div class="hdc_select_country" data-country-code="GT" data-states="">Guatemala</div>

					<div class="hdc_select_country" data-country-code="GG" data-states="">Guernsey</div>

					<div class="hdc_select_country" data-country-code="GN" data-states="">Guinea</div>

					<div class="hdc_select_country" data-country-code="GW" data-states="">Guinea-Bissau</div>

					<div class="hdc_select_country" data-country-code="GY" data-states="">Guyana</div>

					<div class="hdc_select_country" data-country-code="HT" data-states="">Haiti</div>

					<div class="hdc_select_country" data-country-code="HM" data-states="">Heard Island and McDonald Islands</div>

					<div class="hdc_select_country" data-country-code="HN" data-states="">Honduras</div>

					<div class="hdc_select_country" data-country-code="HK" data-states="">Hong Kong</div>

					<div class="hdc_select_country" data-country-code="HU" data-states="pest|csongrad|fejer|veszprem|vas|tolna|jasz-nagykun-szolnok|bekes|zala|bacs-kiskun|somogy|nograd|baranya|komarom|borsod-abauj-zemplen|hajdu-bihar|heves|gyor-sopron|budapest|szabolcs-szatmar-bereg">Hungary</div>

					<div class="hdc_select_country" data-country-code="IS" data-states="akrahreppur|eyja- og miklaholtshreppur|vesturland|akureyri|south|vestfiroir|northland west|sveitarfelagio hornafjorour|akranes|eastland|biskupstungnahreppur|kelduneshreppur|hunabing vestra|vesturbyggo|myrdalshreppur|isafjaroarbar|austur-herao|arneshreppur|halshreppur|vestfiroir|west|capital|suournes">Iceland</div>

					<div class="hdc_select_country" data-country-code="IN" data-states="meghalaya|chhattisgarh|arunachal pradesh|mizoram|dadra e nagar haveli|bihar|madhya pradesh|uttar pradesh|maisur|maharashtra|uttarakhand|kerala|jammu and kashmir|kerala|haryana|himachal pradesh|andhra pradesh|manipur|punjab|lakkadi|orissa|nagaland|tripura|rajputana|andaman et nicobar|sikkim|bangla|chandigarh|delhi|dadra e nagar haveli|goa|assam|vananchal">India</div>

					<div class="hdc_select_country" data-country-code="ID" data-states="malut|benkulen|bali|aceh|jateng|jambi|jawa|sulteng|sultra|sumbar|sumut|ntb|sulsel|riou|ntt|jatim|jawa|kalbar|kalsel|kalteng|kaltim|lampung|sulsel|yapen|yapen|riou|gorontalo|babel|sumsel|banten|jabar|sulut">Indonesia</div>

					<div class="hdc_select_country" data-country-code="IR" data-states="kerman|esfahan|tehran|semnan|lorestan|bushire|zanjan|golestan|qazvin|qom|markazi|bakhtaran|ilam|saheli|kurdistan|mazandaran|ardabil|khuzestan|kohgil|baluchestan va sistan|fars|west azarbaijan|bakhtiari|south khorasan|yazd|north khorasan|razavi khorasan|hamedan|gilan">Iran</div>

					<div class="hdc_select_country" data-country-code="IQ" data-states="kerbela|tamin|diyala|erbil|kut|d&quot;hok|maysan|mosul|as-sulaymaniyah|diwaniyah|salaheddin|hilla|ramadi|al-muthanna|basra|dhi-qar|bagdad|an-najaf">Iraq</div>

					<div class="hdc_select_country" data-country-code="IE" data-states="cavan|sligo|tipperary|carlow|mayo|meath|cork|kings|westmeath|louth|longford|wicklow|wexford|leix|leitrim|waterford|limerick|kerry|galway|kilkenny|kildare|roscommon|clare|monaghan|donegal|dublin">Ireland</div>

					<div class="hdc_select_country" data-country-code="IM" data-states="">Isle of Man</div>

					<div class="hdc_select_country" data-country-code="IL" data-states="al-quds|hefa|tel aviv|al-quds|hadarom|hamerkaz|hazafon">Israel</div>

					<div class="hdc_select_country" data-country-code="IT" data-states="marche|molise|abruzzo|apulia|sardinia|tuscany|emilia romagna|trentino alto adige|umbria|veneto|lombardy|lazio|lacio|sicily|lazio|val d&quot;aoste|basilicata|calabria|friuli-venecia julia">Italy</div>

					<div class="hdc_select_country" data-country-code="CI" data-states="">Ivory Coast</div>

					<div class="hdc_select_country" data-country-code="JM" data-states="manchester|saint ann|saint andrew|saint thomas|trelawny|westmoreland|kingston|saint catherine|hanover|saint james|saint elizabeth|portland|clarendon|saint mary">Jamaica</div>

					<div class="hdc_select_country" data-country-code="JP" data-states="yamanasi|yamaguti|yamagata|wakayama|toyama|tottori|edo|niigata|nara|gunma|hirosima|ezo|hiogo|ibaraki|isikawa|totigi|tokusima|fukui|sizuoka|saitama|siga|osaka|saga|oita|okayama|iwate|kagawa|nara|kanagawa|mie|hukusima|aomori|akita|aiti|fukuoka|hukui|ehime|tiba|kumamoto|koti|gifu|kioto|miyazaki|miyagi|nagano">Japan</div>

					<div class="hdc_select_country" data-country-code="JE" data-states="">Jersey</div>

					<div class="hdc_select_country" data-country-code="JO" data-states="kerak|tafela|zarqa|mafraq|madaba|jarash|aqaba|ajlun|ama|irbid|maan|belga">Jordan</div>

					<div class="hdc_select_country" data-country-code="KZ" data-states="">Kazakhstan</div>

					<div class="hdc_select_country" data-country-code="KE" data-states="central|coast|eastern|nairobi|north-eastern|nyanza|rift valley|western">Kenya</div>

					<div class="hdc_select_country" data-country-code="KI" data-states="">Kiribati</div>

					<div class="hdc_select_country" data-country-code="KW" data-states="al kuwayt|hawalli|al ahmadi|jahra|farwaniya">Kuwait</div>

					<div class="hdc_select_country" data-country-code="KG" data-states="">Kyrgyzstan</div>

					<div class="hdc_select_country" data-country-code="LA" data-states="bokeo|khammuan|viangchan|xekong|fong sali|salavan|namtha|loang prabang|xam nua|khong|xayabury|udomxay|atpu|svannakhet|borikane|xiengkhuang">Laos</div>

					<div class="hdc_select_country" data-country-code="LV" data-states="wolmar|valga|windau|saldus|riga|tukkum|talsi|mitau|gulbene|dobele|dvinsk|wenden|bauska|balvi|aluksne|zemgale|limbai|preili|latgale|riga|ludza|madona|ogre|jekabpils|kraslava|kurzeme|libau">Latvia</div>

					<div class="hdc_select_country" data-country-code="LB" data-states="liban-nord|bekaa|liban-nord|mont-liban|mont-liban|bayrut|an nabatiyah|sayda">Lebanon</div>

					<div class="hdc_select_country" data-country-code="LS" data-states="">Lesotho</div>

					<div class="hdc_select_country" data-country-code="LR" data-states="bong|nimba|river gee|gbapolu|lofa|grandkru|margibi|montserrado|bomi|grand cape mount|maryland|sinoe|grand bassa|nimba|rivercess|grandgedeh">Liberia</div>

					<div class="hdc_select_country" data-country-code="LY" data-states="al jfara|al-ghufra|al-kufra|beida|al-hizam al-ahdar|taghura&quot; wa-n-nawahi al-arba|surt|zlitan|ajdabiya|homs|yafran|tobruk|shati|gat|zavia|homs|nigat al khums|gadamis|mizda|bengasi|al-qubba|murzuq|misratah|sawfajjin|sabha">Libya</div>

					<div class="hdc_select_country" data-country-code="LI" data-states="">Liechtenstein</div>

					<div class="hdc_select_country" data-country-code="LT" data-states="">Lithuania</div>

					<div class="hdc_select_country" data-country-code="LU" data-states="">Luxembourg</div>

					<div class="hdc_select_country" data-country-code="MO" data-states="">Macao S.A.R., China</div>

					<div class="hdc_select_country" data-country-code="MK" data-states="sopiste|saraj|kavadartsi|centar|vevcani|tip|southwestern|southeastern|gazi baba|konce|ohrid|ilinden|jegunovce|vranetica|polog|eastern|kruevo|krivogatani|phecevo|kratovo|veles|butel|brvenica|southeastern|bogovinje|novatsi|caka|novo selo|kocani|gostivar|southwestern|bogdanci|prilep|probistip|radovis|kriva palanka|resen|vardar|vinitsa|kumanovo|demir hisar|centar upa|delcevo|gnjilane|debar|skopje|demir kapija|dolneni|pelagonia|zajas|brod|elino|zelenikovo|karbinci|karpo|mavrovo and rostusa|kicevo|n.a.|lipkovo|negotino|berovo|bitola|tetovo|vasilevo|valandovo|skopje|ohrid|cair|studenicani|southeastern|oslomej|tearce|sveti nikole">Macedonia</div>

					<div class="hdc_select_country" data-country-code="MG" data-states="fianarantsoa|majunga|antseranana|toleary|tamatave|tananarive">Madagascar</div>

					<div class="hdc_select_country" data-country-code="MW" data-states="">Malawi</div>

					<div class="hdc_select_country" data-country-code="MY" data-states="perlis|penang|kedah|kelantan|johor|pahang|perak|melaka|negri sembilan|sabah|selangor|selangor|sarawak|trengganu|selangor">Malaysia</div>

					<div class="hdc_select_country" data-country-code="MV" data-states="">Maldives</div>

					<div class="hdc_select_country" data-country-code="ML" data-states="">Mali</div>

					<div class="hdc_select_country" data-country-code="MT" data-states="">Malta</div>

					<div class="hdc_select_country" data-country-code="MH" data-states="">Marshall Islands</div>

					<div class="hdc_select_country" data-country-code="MQ" data-states="">Martinique</div>

					<div class="hdc_select_country" data-country-code="MR" data-states="">Mauritania</div>

					<div class="hdc_select_country" data-country-code="MU" data-states="">Mauritius</div>

					<div class="hdc_select_country" data-country-code="YT" data-states="">Mayotte</div>

					<div class="hdc_select_country" data-country-code="MX" data-states="durango|guanajuato|guerrero|hidalgo|jalisco|mexico|michoacan|morelos|nayarit|nuevo leon|tabasco|quintana roo|veracruz|yucatan|queretaro|baja california sur|baja california|aguascalientes|coahuila|chihuahua|chiapas|yucatan|puebla|oaxaca|distrito federal|colima|sinaloa|san luis potosi|sonora|tlaxcala|tamaulipas">Mexico</div>

					<div class="hdc_select_country" data-country-code="FM" data-states="">Micronesia</div>

					<div class="hdc_select_country" data-country-code="MD" data-states="">Moldova</div>

					<div class="hdc_select_country" data-country-code="MC" data-states="">Monaco</div>

					<div class="hdc_select_country" data-country-code="MN" data-states="">Mongolia</div>

					<div class="hdc_select_country" data-country-code="ME" data-states="roaje|avnik|kotor|bar">Montenegro</div>

					<div class="hdc_select_country" data-country-code="MS" data-states="">Montserrat</div>

					<div class="hdc_select_country" data-country-code="MA" data-states="marrakech - tensift - al haouz|oriental|grand casablanca|souss - massa - draa|tanger - tetouan|taza - al hoceima - taounate|fes - boulemane|meknes - tafilalet|tadla - azilal|laayoune - boujdour - sakia el hamra|guelmim - es-semara|ed dakhla|doukkala - abda|gharb - chrarda - beni hssen|rabat - sale - zemmour - zaer|chaouia - ouardigha">Morocco</div>

					<div class="hdc_select_country" data-country-code="MZ" data-states="">Mozambique</div>

					<div class="hdc_select_country" data-country-code="MM" data-states="">Myanmar</div>

					<div class="hdc_select_country" data-country-code="NA" data-states="erongo|oshana|karas|khomas|kunene|caprivi|kavango|ohangwena|omaheke|oshikoto|hardap">Namibia</div>

					<div class="hdc_select_country" data-country-code="NR" data-states="">Nauru</div>

					<div class="hdc_select_country" data-country-code="NP" data-states="janakpur|lumbini|mahakali|karnali|eastern|gorkha|rapti|banke|achham|bhaktapur|narayani|mechi|dhaulagiri|sagarmatha">Nepal</div>

					<div class="hdc_select_country" data-country-code="NL" data-states="flevoland|frise|geldern|drenthe|nort|north holland|groninga|limburg|overijssel|utrecht|zuid-holland|eeklo">Netherlands</div>

					<div class="hdc_select_country" data-country-code="NC" data-states="">New Caledonia</div>

					<div class="hdc_select_country" data-country-code="NZ" data-states="nelson|canterbury|bay of plenty|auckland|west coast|northland|otago|marlborough|hawkes bay|wanganui-mawanatu|gisborne|nelson|southland|taranaki">New Zealand</div>

					<div class="hdc_select_country" data-country-code="NI" data-states="managua|carazo|nicaragua|atlantico norte|matagalpa|chinandega|masaya|boaco|nueva segovia|jinotega|granada|esteli|chontales|atlantico sur|madriz|leon">Nicaragua</div>

					<div class="hdc_select_country" data-country-code="NE" data-states="">Niger</div>

					<div class="hdc_select_country" data-country-code="NG" data-states="taraba|osun|borno|kebbi|enugu|bauchi|kaduna|abia|plateau|ondo|kano|imo|lagos|anambra|yobe|katsina|kogi|benue|akwa ibom|ogun|rivers|ekiti|bayelsa|ebonyi|gombe|nassarawa|zamfara|abyei|cross river|south kordufan|abuja|jigawa|oyo|kwara|niger|ondo|edo|adamawa">Nigeria</div>

					<div class="hdc_select_country" data-country-code="NU" data-states="">Niue</div>

					<div class="hdc_select_country" data-country-code="NF" data-states="">Norfolk Island</div>

					<div class="hdc_select_country" data-country-code="MP" data-states="">Northern Mariana Islands</div>

					<div class="hdc_select_country" data-country-code="KP" data-states="">North Korea</div>

					<div class="hdc_select_country" data-country-code="NO" data-states="romsdal|vestfold|troms|vest-agder|sor-trondelag|telemark|rogaland|nordre bergenhus|finnmark|buskerud|nord-trondelag|opland|oslo|nordland|hordaland|astfold|oslo|hedmark|nedenes">Norway</div>

					<div class="hdc_select_country" data-country-code="OM" data-states="">Oman</div>

					<div class="hdc_select_country" data-country-code="PK" data-states="northern areas|kashmir|sind|punjab|n.w.f.p.|balochistan|f.a.t.a.|f.c.t.">Pakistan</div>

					<div class="hdc_select_country" data-country-code="PS" data-states="">Palestinian Territory</div>

					<div class="hdc_select_country" data-country-code="PA" data-states="">Panama</div>

					<div class="hdc_select_country" data-country-code="PG" data-states="">Papua New Guinea</div>

					<div class="hdc_select_country" data-country-code="PY" data-states="asuncion|boqueron|san pedro|misiones|neembucu|guaira|itapua|presidente hayes|cordillera|paraguari|caazapa|caaguazu|concepcion|canindeyu|alto parana|alto paraguay|amambay">Paraguay</div>

					<div class="hdc_select_country" data-country-code="PE" data-states="ayacucho|san mart|tacna|cusco|callao|piura|ucayali|tumbez|amazonas|apurimac|ancash|arequipa|cajamarca|lima|moquegua|pasco|loreto|madre de dios|lambayeque|lima|junnn|libertad|huknuco|ica">Peru</div>

					<div class="hdc_select_country" data-country-code="PH" data-states="siquijor|agusan del norte|cebu|quezon|negros occidental|metropolitan manila|pangasinan|quirino|rizal|samar|south cotabato|north cotabato|maguindanao|southern leyte|sorsogon|catanduanes|leyte|la union|lanao del sur|lanao del norte|laguna|apayao|isabela|iloilo|camarines norte|tawi-tawi|sultan kudarat|camarines sur|bohol|benguet|masbate|bukidnon|agusan del sur|basilan|pampanga|eastern samar|capiz|zamboanga del sur|aurora|marinduque|cagayan|sabah|bulacan|camiguin|romblon|negros oriental|nueva ecija|mountain province|tarlac|misamis occidental|misamis oriental|occidental mindoro|oriental mindoro|ilocos norte|nueva vizcaya|paragua|davao|sarangani|davao oriental|abra|antique|cavite|aklan|albay|sulu|dinagat islands|surigao del sur|batangas|zambales|ilocos sur|zamboanga del sur|northern samar">Philippines</div>

					<div class="hdc_select_country" data-country-code="PN" data-states="">Pitcairn</div>

					<div class="hdc_select_country" data-country-code="PL" data-states="pomorskie|warmian-masurian|lublin|swietokrzyskie|malopolskie|opole|wielkopolskie|slaskie|west pomeranian|podkarpackie|warmian-masurian|dolnoslaskie|lodz|kujawsko-pomorskie|lubusz|masovian|podlaskie">Poland</div>

					<div class="hdc_select_country" data-country-code="PT" data-states="beja|evora|portalegre|guarda|aveiro|porto|madeira|lisboa|vila real|leiria|coimbra|castelo branco|braganca|braga|santarem|viana do castelo|azores|viseu|faro">Portugal</div>

					<div class="hdc_select_country" data-country-code="PR" data-states="">Puerto Rico</div>

					<div class="hdc_select_country" data-country-code="QA" data-states="al rayyan|al shamal|al khor|al wakrah|al ghuwayriyah|al gummaylah|doha|jerian al|um salal">Qatar</div>

					<div class="hdc_select_country" data-country-code="RE" data-states="">Reunion</div>

					<div class="hdc_select_country" data-country-code="RO" data-states="vrancea|galatz|gorj|vaslui|valcea|suceava|cluj|bukarest|buzau|dambovita|salaj|constanta|sibiu|teleorman|timis|bihor|bacau|botosani|bistritz|alba|arges|arad|calarasi|prahova|giurgiu|brasov|braila|dolj|satu mare|olt|neamt|mures|maramures|covasna|iasi|ialomita|hunedoara|harghita">Romania</div>

					<div class="hdc_select_country" data-country-code="RU" data-states="karelia|penza|lipetsk|pskov|kamchatka|kirov|belgorod|mari|bashkir|ivanovo|mordov|kursk|kurgan|kaluga|kalmyk|adygey|oirot|aga buryat|amur|altay|astrachan|vologda|simbirsk|udmurt|vladimir|ust-orda buryat|vologda|volgograd|yamal-nenets|kuzey osetya|tambov|yaroslavl&quot;|samara|irkutsk|chukot|ryazan&quot;|rostov|kazan|kaliningrad|smolensk|kabard|voronezh|mosca|maga buryatdan|leningrad|novgorod|chukchi autonomous okrug|kemerovo|tomsk|sakhalin|moskovsskaya|evrey|tula|saratov|khabarovsk|khakass|k|gorky|komi|stavropol&quot;|evenk|ingush|chuvash|dagistan|chita|kostroma|cecenia|chelyabinsk|bryansk|buryat|kuban|primorsk|tyumen&quot;|tyva|perm&quot;|yeniseisk|taymyr|novosibirsk|nenets|tver&quot;|orel|sverdlovsk|omsk|samara">Russia</div>

					<div class="hdc_select_country" data-country-code="RW" data-states="kigali|byumba|kibungu|kibuye|butare|shangugu">Rwanda</div>

					<div class="hdc_select_country" data-country-code="BL" data-states="">Saint Barth&eacute;lemy</div>

					<div class="hdc_select_country" data-country-code="SH" data-states="">Saint Helena</div>

					<div class="hdc_select_country" data-country-code="KN" data-states="">Saint Kitts and Nevis</div>

					<div class="hdc_select_country" data-country-code="LC" data-states="">Saint Lucia</div>

					<div class="hdc_select_country" data-country-code="MF" data-states="">Saint Martin (French part)</div>

					<div class="hdc_select_country" data-country-code="SX" data-states="">Saint Martin (Dutch part)</div>

					<div class="hdc_select_country" data-country-code="PM" data-states="">Saint Pierre and Miquelon</div>

					<div class="hdc_select_country" data-country-code="VC" data-states="">Saint Vincent and the Grenadines</div>

					<div class="hdc_select_country" data-country-code="SM" data-states="">San Marino</div>

					<div class="hdc_select_country" data-country-code="ST" data-states="">S&atilde;o Tom&eacute; and Pr&iacute;ncipe</div>

					<div class="hdc_select_country" data-country-code="SA" data-states="riad|baha|gasim|jowf|tabuk|najran|jazan|medina|ash sharqiyah|meca|hail|northern frontier|asir|jowf">Saudi Arabia</div>

					<div class="hdc_select_country" data-country-code="SN" data-states="">Senegal</div>

					<div class="hdc_select_country" data-country-code="RS" data-states="">Serbia</div>

					<div class="hdc_select_country" data-country-code="SC" data-states="">Seychelles</div>

					<div class="hdc_select_country" data-country-code="SL" data-states="">Sierra Leone</div>

					<div class="hdc_select_country" data-country-code="SG" data-states="">Singapore</div>

					<div class="hdc_select_country" data-country-code="SK" data-states="">Slovakia</div>

					<div class="hdc_select_country" data-country-code="SI" data-states="">Slovenia</div>

					<div class="hdc_select_country" data-country-code="SB" data-states="">Solomon Islands</div>

					<div class="hdc_select_country" data-country-code="SO" data-states="">Somalia</div>

					<div class="hdc_select_country" data-country-code="ZA" data-states="kwazulu-natal|mpumalanga|oos-kaap|vrystaat|noordwes|limpopo|wes-kaap|gauteng">South Africa</div>

					<div class="hdc_select_country" data-country-code="GS" data-states="">South Georgia/Sandwich Islands</div>

					<div class="hdc_select_country" data-country-code="KR" data-states="taegu|pusan|kyunggi|jinsen|taegu|jeonrabugdo|chusei nan-do|jeju|taiden|seul|kwangju|chusei hoku-do|kwangju|keisho nan-do">South Korea</div>

					<div class="hdc_select_country" data-country-code="SS" data-states="">South Sudan</div>

					<div class="hdc_select_country" data-country-code="ES" data-states="extremadura|castela-mancha|baleari|galiza|valencia|navarra|cav|asturie|cav|murcia|madrid|canillo|aragon|andaluzia|ceuta">Spain</div>

					<div class="hdc_select_country" data-country-code="LK" data-states="">Sri Lanka</div>

					<div class="hdc_select_country" data-country-code="SD" data-states="">Sudan</div>

					<div class="hdc_select_country" data-country-code="SR" data-states="">Suriname</div>

					<div class="hdc_select_country" data-country-code="SJ" data-states="">Svalbard and Jan Mayen</div>

					<div class="hdc_select_country" data-country-code="SE" data-states="orebro|norrbotten|ostergotland|skane|dalarna|kronoberg|gotland|uppsala|blekinge|jonkoping|kalmar|vastmanland|vasternorrland|gavleborg|uppsala|vastra gotaland|halland|jamtland|varmland|vasterbotten">Sweden</div>

					<div class="hdc_select_country" data-country-code="CH" data-states="solothurn|thurgau|obwald|son gagl|sciaffusa|schwyz|appenzell dador|luzern|neuchatel|nidwald|turitg|zug|jura|uri|ticino|wallis|grisons|glaris|genf|vad|bern|basel-city|appenzell dadens|aargau">Switzerland</div>

					<div class="hdc_select_country" data-country-code="SY" data-states="">Syria</div>

					<div class="hdc_select_country" data-country-code="TW" data-states="gaoxiong|jiayi|zhanghua|hualia|jiayi|taibei|gaoxiong shi|yunlin|taizhong shi|penghu|taidong|taoyuan|miaoli|pingdong|taizhong|tainan shi|tainan|xinzhu|xinzhu|jilong shi|ilan|chinmen">Taiwan</div>

					<div class="hdc_select_country" data-country-code="TJ" data-states="">Tajikistan</div>

					<div class="hdc_select_country" data-country-code="TZ" data-states="lindi|rukwa|dodoma|kagera|tanga|shinyanga|ruvuma|tabora|singida|mtwara|morogoro|pemba north|mwanza|pemba south|iringa|kigoma|pwani|zanzibar west|arusha|manyara|kusini unguja|zanzibar north|dar es salaam|mara|mbeya">Tanzania</div>

					<div class="hdc_select_country" data-country-code="TH" data-states="mae hong son|phayao|phatthalung|nakhon phanom|ranong|chumphon|korh luxk|phet buri|mahachai|mae klong|nakhon pathom|ratchaburi|suphan buri|kan buri|chaiyaphum|lampang|surat thani|pathum thani|yasothon|prachin buri|amnat charoen|phuket|chiang mai|uttaradit|nan|lamphun|buri rum|surin|tak|sukothai|maha sarakham|roi et|khon kaen|chiang rai|sakon nakhon|nakhon sawan|songkhla|pattani|muang chan|trat|pad rew|krabi|chon buri|phrae|bangkok|satun|nakhon thammarat|nakon nayok|ubon ratchathani|pak nam|mukdahan|sa kaeo|trang|rayong|loei|ang thong|nonthaburi|kalasin|phichit|bisnulok|kambhengbhej|udon thani|nong khai|yala|uthai thani|phetchabun|narathiwat|khukhan|sing buri|khorat|nong bua lamphu|lop buri|saraburi|ayudhya">Thailand</div>

					<div class="hdc_select_country" data-country-code="TL" data-states="">Timor-Leste</div>

					<div class="hdc_select_country" data-country-code="TG" data-states="">Togo</div>

					<div class="hdc_select_country" data-country-code="TK" data-states="">Tokelau</div>

					<div class="hdc_select_country" data-country-code="TO" data-states="">Tonga</div>

					<div class="hdc_select_country" data-country-code="TT" data-states="">Trinidad and Tobago</div>

					<div class="hdc_select_country" data-country-code="TN" data-states="">Tunisia</div>

					<div class="hdc_select_country" data-country-code="TR" data-states="kastamonu|batman|bayburt|adiyaman|hatay|hakkari|konya|mardin|nigde|siirt|aksaray|sivas|tekirdag|karaman|sinop|sakarya|isparta|ordu|rize|nevsehir|corum|kayseri|kirklareli|stamboul|bitlis|balikesir|bilecik|bursa|canakkale|bolu|burdur|adalia|kinkkale|mus|malatya|mersin|gumushane|afyon|aydin|urfa|tunceli|trabzon|tokat|yozgat|van|usak|izmit|kirsehir|mugla|ankara|manisa|artvin|k. maras|amasya|agri|giresun|kutahya|eskisehir|erzurum|erzincan|elazig|edirne|diyarbakir|denizli|bingol|samsun|izmir|zinguldak|kars|adana|sirnak|gaziantep|changra">Turkey</div>

					<div class="hdc_select_country" data-country-code="TM" data-states="">Turkmenistan</div>

					<div class="hdc_select_country" data-country-code="TC" data-states="">Turks and Caicos Islands</div>

					<div class="hdc_select_country" data-country-code="TV" data-states="">Tuvalu</div>

					<div class="hdc_select_country" data-country-code="UG" data-states="">Uganda</div>

					<div class="hdc_select_country" data-country-code="UA" data-states="">Ukraine</div>

					<div class="hdc_select_country" data-country-code="AE" data-states="neutral zone|dibay|abu dabi|chardja|qaiwan|fujayrah">United Arab Emirates</div>

					<div class="hdc_select_country" data-country-code="UM" data-states="">United States (US) Minor Outlying Islands</div>

					<div class="hdc_select_country" data-country-code="VI" data-states="">United States (US) Virgin Islands</div>

					<div class="hdc_select_country" data-country-code="UY" data-states="">Uruguay</div>

					<div class="hdc_select_country" data-country-code="UZ" data-states="">Uzbekistan</div>

					<div class="hdc_select_country" data-country-code="VU" data-states="">Vanuatu</div>

					<div class="hdc_select_country" data-country-code="VA" data-states="">Vatican</div>

					<div class="hdc_select_country" data-country-code="VE" data-states="">Venezuela</div>

					<div class="hdc_select_country" data-country-code="VN" data-states="long an|soc trang|qung tr|an giang|tha thien - hu|dak lak|ninh thun|cao bng|qung binh|kien giang|ho chi minh|lam dong|dien bien|tuyen quang|gia lai|binh thun|binh djnh|ba ria - vtau|thai nguyen|south east|ninh binh|bn tre|dong thap|phu th|nam dinh|red river delta|ha nam|vinh phuc|thai nguyen|dja nng|khanh hoa|qung nam|phu yen|yen bai|qung ninh|qung ngai|lng son|ha giang|thanh hoa|thai binh|son la|tay ninh|qung ninh|ha tay|dja nng|hi duong|ngh an|bc ninh|djong bc|bc lieu|lao cai|bc giang|binh phuc|ca mau|ha tinh|binh duong">Vietnam</div>

					<div class="hdc_select_country" data-country-code="WF" data-states="">Wallis and Futuna</div>

					<div class="hdc_select_country" data-country-code="EH" data-states="">Western Sahara</div>

					<div class="hdc_select_country" data-country-code="WS" data-states="">Samoa</div>

					<div class="hdc_select_country" data-country-code="YE" data-states="">Yemen</div>

					<div class="hdc_select_country" data-country-code="ZM" data-states="">Zambia</div>

					<div class="hdc_select_country" data-country-code="ZW" data-states="">Zimbabwe</div>


				</div>
			</div>

			<?php
				if($hdc_store_selling_countries != "" && $hdc_store_selling_countries != null){
					$hdc_store_selling_countries = str_replace("\\", "", $hdc_store_selling_countries);
				}

				if($hdc_store_selling_countries != "" && $hdc_store_selling_countries != null){
					$hdc_store_selling_countries = json_decode($hdc_store_selling_countries, true);
					foreach($hdc_store_selling_countries as $value) {
						echo '<div data-country-code = "'.$value["code"].'" data-states = "'.$value["states"].'" class = "hdc_selected_country">'.$value["country"].'</div>';
					}
				}
			?>
		</div>

		<div class = "clear"></div>
	</div>

</div>