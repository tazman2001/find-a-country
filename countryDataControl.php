<?php

class countryDataControl extends countryDataAccess {

	

	function __construct($data = null) {

		parent::__construct();

		if(!is_null($data)) {
			$this->data = $data;
		}

		if(!isset($this->source)) {
			$this->source = null;
		}
    }

	function set() {

		//check for source of data if api then it doesn't exist in db and write it.
		//replace addslashes if more time
		if($this->data['source'] == 'Api') {

			$this->countryCode = $this->data['cCode'];

			$countrySql = 'INSERT IGNORE INTO countries (country_code,
												   	  	 name,
												  		 capital,
												  		 region,
												  		 flag_image,
												  		 dialing_code) 
		                   VALUES(\''.$this->countryCode.'\',
		                          \''.addslashes(utf8_decode($this->data['Country'])).'\',
		                          \''.$this->data['cCity'].'\',
		                          \''.$this->data['region'].'\',
		                          \''.$this->data['flag_image'].'\',
		                          \''.$this->data['dialing_code'].'\')';

		    $this->insert($countrySql);

		    $currData = $this->formatJsonData($this->data['Currency'],'code');

			$currencySql = "INSERT IGNORE INTO currency_codes (currency_code,
												  			   name,
												  			   symbol) 
		                   VALUES ".implode(",",$currData['main']);

			$this->insert($currencySql);


			$contCurrSql = "INSERT IGNORE INTO countries_currencies(country_code,
																	currency_code)
							VALUES ".implode(",",$currData['sub']);

			$this->insert($contCurrSql);


		    $langData = $this->formatJsonData($this->data['Lang'],'code');

			$langSql = "INSERT IGNORE INTO languages (language_code,
												  		  name) 
		                   VALUES ".implode(",",$langData['main']);

			$this->insert($langSql);

			$contLangSql = "INSERT IGNORE INTO countries_languages(country_code,
																   language_code)
							VALUES ".implode(",",$langData['sub']);

			$this->insert($contLangSql);

			$timezData = explode(",",$this->data['timezone']);

			$timeZ = array();
			foreach($timezData as $timeZone)
			{
				$timeZ[] = "('".$this->countryCode."','".$timeZone."')";
			}

			$contTimeSql = "INSERT IGNORE INTO countries_timezones(country_code,
																   timezone)
							VALUES ".implode(",",$timeZ);

			$this->insert($contTimeSql);
		}

	}

	function formatJsonData($jsonData,$subItem) {

		//format json data and add '[' ']' incase of multiple sets of values.
		$jsonData = urldecode($jsonData);

		$jsonData = '['.$jsonData.']';


		$data = json_decode($jsonData,true);

		foreach($data as $itemNo => $itemData)
		{
			$subData = array();
			$subData[] = $this->countryCode;
			$mainData = array();

			foreach($itemData as $itemName => $itemValue)
			{
				if($itemName == $subItem) {
					$subData[] = $itemValue;
				}

				$mainData[] = $itemValue;
			}

			$returnData['sub'][] = "('".implode("','",$subData)."')";
			$returnData['main'][] = "('".implode("','",$mainData)."')";

		}

		return($returnData);
	}


	function getApiResults($url) {

        //restrict fields being used to reduce access time.
		$url.= '?fields=name;alpha3Code;callingCodes;capital;currencies;flag;region;timezones;languages';

		$result = $this->callApi($url);

		$items = json_decode($result);

		if(!isset($items->status)) {
			if(!is_array($items)) {
				$temp = array();
				$temp[] = $items;
				$items = $temp;
			}

			$countryData = $this->formatApiData($items);

			return($countryData);
		} else {
			return("not found");
		}
	}


	function showResults($items) {

        if(!isset($items->status)) {
			foreach($items as $itemcc => $item)
			{
				$ccodes = array();
				$languages = array();

				foreach($item['currency'] as $codeNo => $currencyItem) 
				{
					$jsonCcode = array();
					$jsonCcode['code'] = $currencyItem['currency_code'];
					$jsonCcode['name'] = $currencyItem['name'];

					if(isset($currencyItem['symbol'])) {
						$jsonCcode['symbol'] = $currencyItem['symbol'];
					}

					$ccodes[] = json_encode($jsonCcode);
				}

				foreach($item['languages'] as $langNo => $language) 
				{
					$jsonLang = array();
					$jsonLang['code'] = $language['language_code'];
					$jsonLang['name'] = $language['language_name'];

					$languages[] = json_encode($jsonLang);
				}

		        echo "<div class='countryDetails' onclick=\"getCountry(this);\" data-name =\"".$item['name']."\" 
		        																data-cCode =\"".$itemcc."\"
		                                                                       	data-dialCode =\"".$item['dialing_code']."\" 
		                                                                       	data-capital =\"".$item['capital']."\" 
		                                                                       	data-cucode ='".urlencode(implode(",",$ccodes))."' 
		                                                                       	data-elemId = \"".$this->data['elemId']."\"
		                                                                       	data-flag = \"".$item['flag_image']."\"
		                                                                       	data-region = \"".$item['region']."\"
		                                                                       	data-timezones = \"".implode(",",$item['timezones'])."\"
		                                                                       	data-source = \"".$this->source."\"
		                                                                       	data-lang ='".urlencode(implode(",",$languages))."'>";

		        echo "<div class='countryTitle'>Country Name:".$item['name']."</div>";
		        echo "<div class='dialCode'>dialing Code:".$item['dialing_code']."</div>";
		        echo "</div>";
			}
		} else {
			echo "Nothing Found";
		} 
		echo "</div>";
	}


	function show() {
		echo "<html>
			  <link rel=\"stylesheet\" href=\"style.css\">
			<body>
				<div id='mainArea' class='mainArea showArea'>
					<h1>".$this->data['Country']."</h1>
					<div class='flag'><img src=\"".$this->data['flag_image']."\" alt=\"Flag\">
					<div><label>Capital:</label>".$this->data['cCity']."</div>
				
					<div><label>dialing Code:</label>".$this->data['dialing_code']."</div>
					<div><label>region:</label>".$this->data['region']."</div>
					<div><label>country code:</label>".$this->data['cCode']."</div>
					<div class='timezone'><label>timeZones:</label></div><div>";

					$timezones = explode(",",$this->data['timezone']);

					foreach($timezones as $timeZone)
					{
						echo $timeZone."<BR>";
					}

					echo "</div>
					<div><label>Currency:</label>";

					$json = json_decode('['.urldecode($this->data['Currency']).']',true);

					foreach($json as $no => $data) 
					{
						echo "<div>".$data['name']."(".$data['symbol'].")</div>";
					}

					echo "</div>


					<div><label>Languages:</label>";

					$json = json_decode('['.urldecode($this->data['Lang']).']',true);

					foreach($json as $no => $data) 
					{
						echo "<div>".$data['name']."</div>";
					}

					echo "</div>

			  	</div>
			</body>
		</html>";
		
	}

	function showForm() {
		echo "
		<html>
			<link rel=\"stylesheet\" href=\"style.css\">

	        <script type='text/javascript' src='script.js'></script>

			<body>
				<div id='mainArea' class='mainArea'>
					<h1>
						Country Search
						<span>
							Find the country you need.
						</span>
					</h1>

					<form class ='mainForm' id='mainForm' onsubmit='countryControl.php' method='post'>
					<input type = 'hidden' id='flag_image' name='flag_image'>
					<input type = 'hidden' id='dialing_code' name='dialing_code'>
					<input type = 'hidden' id='timezone' name='timezone'>
					<input type = 'hidden' id='region' name='region'>
					<input type = 'hidden' id='source' name='source'>

						<div>
							<label>
								Country Name: <input id='cName' name='Country' class='cName' type = 'text' onkeyup=\"showHint(this)\">
							</label>
						</div>

						<div class='compcNameSuggest hide' id='compcNameSuggest'><p>Suggestions: <span id='txtcNameHint'></span></p></div>

						<div>
							<label>
								Country Code: <input id='cCode' name='cCode' class='cCode' type = 'text' onkeyup=\"showHint(this)\">
							</label>
						</div>

						<div class='compcCodeSuggest hide' id='compcCodeSuggest'><p>Suggestions: <span id='txtcCodeHint'></span></p></div>


						<div>
							<label>
								Capital City: <input id='cCity' name='cCity' class='cCity' type = 'text' onkeyup=\"showHint(this)\">
							</label>
						</div>
						
						<div class='compcCitySuggest hide' id='compcCitySuggest'><p>Suggestions: <span id='txtcCityHint'></span></p></div>

						<div>
							<label>
								Currency Code: <input id='cuCode' name='Currency' class='cuCode' type = 'text' onkeyup=\"showHint(this)\">
							</label>
						</div>
				
						<div class='compCurrencySuggest hide' id='compcuCodeSuggest'><p>Suggestions: <span id='txtcuCodeHint'></span></p></div>

						<div>
							<label>
								Language: <input id='Lang' name='Lang' class='Lang' type = 'text' onkeyup=\"showHint(this)\">
							</label>
						</div>

						<div class='compLangSuggest hide' id='compLangSuggest'><p>Suggestions: <span id='txtLangHint'></span></p></div>
		        
		        		<div id='countrySubmit' class='hide'>
		        			<input type = 'submit'>
		        		</div>
					</form>
				</div>
			</body>
		</html>";
	}

	function findCountryByName() {
	
		$result = $this->checkDbCountryName();

		if($result === null || sizeof($result) < 2) {

			if(isset($this->data['searchValue'])) {
				$url = 'https://restcountries.eu/rest/v2/name/'.$this->data['searchValue'];

				$result = $this->getApiResults($url);
	        } 
    	}

    	$this->showResults($result);
	}

	function findCountryByCountryCode() {
	
		$result = $this->checkDbCountryCode();

		if($result === null || sizeof($result) < 2) {
			if(strlen($this->data['searchValue']) >= 2) {
				$url = 'https://restcountries.eu/rest/v2/alpha/'.$this->data['searchValue'];
            	$result = $this->getApiResults($url);
        	}
        } 

        $this->showResults($result);
	}

	function findCountryByCapital() {
	
			$result = $this->checkDbCountryCapital();

		if($result === null || sizeof($result) < 2) {
			if(isset($this->data['searchValue'])) {
				$url = 'https://restcountries.eu/rest/v2/capital/'.$this->data['searchValue'];
            	$result = $this->getApiResults($url);
        	}
        } 
        $this->showResults($result);
	}

	function findCountryByLanguage() {
	
	    $result = $this->checkDbCountryLanguage();

		if($result === null || sizeof($result) < 2) {
			if(strlen($this->data['searchValue']) > 1) {

				$url = 'https://restcountries.eu/rest/v2/lang/'.$this->data['searchValue'];

            	$result = $this->getApiResults($url);
            }
        } 

        if($result != "not found") {
        	$this->showResults($result);
    	}
	}

	function findCountryByCurrency() {

		$result = $this->checkDbCountryCurrency();

		if($result === null || sizeof($result) < 2) {
            if(strlen($this->data['searchValue']) < 3) {
            	echo "You must type 3 characters";
            } else {
			 $url = 'https://restcountries.eu/rest/v2/currency/'.$this->data['searchValue'];

			 $result = $this->getApiResults($url);
			}
        } 
        $this->showResults($result);
	}

	function checkDbCountryCapital() {

		$sql = "SELECT 	co.name,
						co.country_code,
						co.region,
						co.capital,
						co.flag_image,
						co.dialing_code,
						ct.timezone,
						cco.currency_code,
						cco.name as currency_name,
						la.language_code,
						la.name as language_name
						 FROM countries co 
				JOIN countries_currencies cc ON cc.country_code = co.country_code 
				JOIN currency_codes cco ON cco.currency_code = cc.currency_code 
				JOIN countries_languages cl ON cl.country_code = co.country_code 
				JOIN languages la ON la.language_code = cl.language_code 
				JOIN countries_timezones ct ON ct.country_code = co.country_code
				WHERE co.capital like '%".$this->data['searchValue']."%'
				ORDER BY co.country_code";

		$result = $this->select($sql);

		$countryData = $this->formatDbData($result);

		return($countryData);
	}

		function checkDbCountryLanguage() {

		$sql = "SELECT 	co.name,
						co.country_code,
						co.region,
						co.capital,
						co.flag_image,
						co.dialing_code,
						ct.timezone,
						cco.currency_code,
						cco.name as currency_name,
						la.language_code,
						la.name as language_name
						 FROM countries co 
				JOIN countries_currencies cc ON cc.country_code = co.country_code 
				JOIN currency_codes cco ON cco.currency_code = cc.currency_code 
				JOIN countries_languages cl ON cl.country_code = co.country_code 
				JOIN languages la ON la.language_code = cl.language_code 
				JOIN countries_timezones ct ON ct.country_code = co.country_code
				WHERE la.language_code like '%".$this->data['searchValue']."%'
				ORDER BY co.country_code";

		$result = $this->select($sql);

		$countryData = $this->formatDbData($result);

		return($countryData);
	}

		function checkDbCountryCurrency() {

		$sql = "SELECT 	co.name,
						co.country_code,
						co.region,
						co.capital,
						co.flag_image,
						co.dialing_code,
						ct.timezone,
						cco.currency_code,
						cco.name as currency_name,
						la.language_code,
						la.name as language_name
						 FROM countries co 
				JOIN countries_currencies cc ON cc.country_code = co.country_code 
				JOIN currency_codes cco ON cco.currency_code = cc.currency_code 
				JOIN countries_languages cl ON cl.country_code = co.country_code 
				JOIN languages la ON la.language_code = cl.language_code 
				JOIN countries_timezones ct ON ct.country_code = co.country_code
				WHERE cco.currency_code like '%".$this->data['searchValue']."%'
				ORDER BY co.country_code";

		$result = $this->select($sql);

		$countryData = $this->formatDbData($result);

		return($countryData);
	}


	function checkDbCountryCode() {

		$sql = "SELECT 	co.name,
						co.country_code,
						co.region,
						co.capital,
						co.flag_image,
						co.dialing_code,
						ct.timezone,
						cco.currency_code,
						cco.name as currency_name,
						la.language_code,
						la.name as language_name
						 FROM countries co 
				JOIN countries_currencies cc ON cc.country_code = co.country_code 
				JOIN currency_codes cco ON cco.currency_code = cc.currency_code 
				JOIN countries_languages cl ON cl.country_code = co.country_code 
				JOIN languages la ON la.language_code = cl.language_code 
				JOIN countries_timezones ct ON ct.country_code = co.country_code
				WHERE co.country_code like '%".$this->data['searchValue']."%'
				ORDER BY co.country_code";

		$result = $this->select($sql);

		$countryData = $this->formatDbData($result);

		return($countryData);
	}


	function checkDbCountryName() {

		$sql = "SELECT 	co.name,
						co.country_code,
						co.region,
						co.capital,
						co.flag_image,
						co.dialing_code,
						ct.timezone,
						cco.currency_code,
						cco.name as currency_name,
						la.language_code,
						la.name as language_name
						 FROM countries co 
				JOIN countries_currencies cc ON cc.country_code = co.country_code 
				JOIN currency_codes cco ON cco.currency_code = cc.currency_code 
				JOIN countries_languages cl ON cl.country_code = co.country_code 
				JOIN languages la ON la.language_code = cl.language_code 
				JOIN countries_timezones ct ON ct.country_code = co.country_code
				WHERE co.name like '%".$this->data['searchValue']."%'
				ORDER BY co.country_code";

		$result = $this->select($sql);

		$countryData = $this->formatDbData($result);

		return($countryData);
	}

	function formatDbData($result) {

		$this->source = 'Db';

		$countryData = array();

		$counter = 0;
		$oldcc = "";
		$oldCco = "";
		$oldLang = "";
		foreach($result as $restultNo => $data) 
		{
			if($oldcc = $data['country_code']) {
				$counter++;
			} else {
				$counter = 0;
			}


			$countryData[$data['country_code']]['region'] = $data['region'];
			$countryData[$data['country_code']]['name'] = $data['name'];
			$countryData[$data['country_code']]['capital'] = $data['capital'];
			$countryData[$data['country_code']]['flag_image'] = $data['flag_image'];
			$countryData[$data['country_code']]['dialing_code'] = $data['dialing_code'];
			$countryData[$data['country_code']]['timezones'][] = $data['timezone'];

			if($counter > 0 && $data['currency_code'] != $oldCco) {
				$countryData[$data['country_code']]['currency'][$counter]['currency_code'] = $data['currency_code'];
				$countryData[$data['country_code']]['currency'][$counter]['name'] = $data['currency_name'];
			}

			if($counter > 0 && $data['language_code'] != $oldLang) {
				$countryData[$data['country_code']]['languages'][$counter]['language_code'] = $data['language_code'];
				$countryData[$data['country_code']]['languages'][$counter]['language_name'] = $data['language_name'];
			}

			$oldcc = $data['country_code'];
			$oldCco = $data['currency_code'];
			$oldLang = $data['language_code'];
		}

		return($countryData);
	}

	function formatApiData($result) {

		$this->source = 'Api';
		$countryData = array();

		$counter = 0;
		$oldcc = "";
		$oldCco = "";
		$oldLang = "";

		foreach($result as $data) 
		{
			$countryData[$data->alpha3Code]['region'] = $data->region;
			$countryData[$data->alpha3Code]['name'] = $data->name;
			$countryData[$data->alpha3Code]['capital'] = $data->capital;
			$countryData[$data->alpha3Code]['flag_image'] = $data->flag;
			$countryData[$data->alpha3Code]['dialing_code'] = implode(",",$data->callingCodes);
			$countryData[$data->alpha3Code]['timezones'][] = implode(",",$data->timezones);

			$counter = 0;
			foreach($data->currencies as $codeNo => $currency)
			{
				// some dodgy data in dollars under america

				if(isset($currency->code) && isset($currency->name)) {
					$countryData[$data->alpha3Code]['currency'][$counter]['currency_code'] = $currency->code;
					$countryData[$data->alpha3Code]['currency'][$counter]['name'] = $currency->name;

					if(isset($currency->symbol)) {
						$countryData[$data->alpha3Code]['currency'][$counter]['symbol'] = $currency->symbol;
					}
					$counter++;
				}
			}

			$counter = 0;
			foreach($data->languages as $langNo => $language)
			{
				//iso639_1 is not always set

				if(isset($language->iso639_1)) {
					$countryData[$data->alpha3Code]['languages'][$counter]['language_code'] = $language->iso639_1;
				} else {
					$countryData[$data->alpha3Code]['languages'][$counter]['language_code'] = $language->iso639_2;
				}

					$countryData[$data->alpha3Code]['languages'][$counter]['language_name'] = $language->name;
				

					$counter++;
			}
		}
		return($countryData);
	}
}