<?php
if ( !isIIS() ) {
	$resultsPage = str_replace('.php','', $resultsPage);
	$detailsPage = str_replace('.php','', $detailsPage);	
}

function validateAuthorisationKeys() {

	global $contactId;

	if ( HashKey == '' || $contactId == '' ) {
		echo "Please define the Authorisation parameters in webkitConfig.php";
		exit(0);
			
	}
	if ( strlen(HashKey) != 40 ) {
		echo "You seem to have a typo in the HashKey - please check the parameter and try again";
		exit(0);
	}
}

function isIIS() {

    $sSoftware = strtolower( $_SERVER["SERVER_SOFTWARE"] );
    if ( strpos($sSoftware, "microsoft-iis") !== false ) {
        return true;
	} else {
        return false;
	}
}

function formatPrice($price, $currency) {
	return $currency.number_format($price, 0, '.', ',');
}

function formatArea($area, $extend) {
	return $area.$extend;
}

function object2array($xmlFile) {

	$xml = simplexml_load_file($xmlFile); 
    $data = @json_decode(@json_encode($xml),1);

	return $data;
}

function validateRefId() {

	global $rewritePatternUrl;

	if ( strpos($rewritePatternUrl, 'refid') === false ) {
    	echo 'Your url rewrite pattern config is not correct - refid is required';
		exit(0);
	}
}

validateRefId();

function setMultiSelectForMobileDevices() {

	// set multiple select to classic mode for tablets and mobiles - 'chosen' style does not work well on these devices

	global $showChosen;
	
	if ( $showChosen ) {
	
		$device = '';
		
		if ( stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {
			$device = "ipad";
		} else if ( stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone') ) {
			$device = "iphone";
		} else if ( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) {
			$device = "blackberry";
		} else if ( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) {
			$device = "android";
		}
		
		if ( $device != '' ) $showChosen = false;
	}
}

setMultiSelectForMobileDevices();

function createAPIURL($url, $params) {

	$paramStr = implode('&', array_map(function ($v, $k) { return $k.'='.$v; }, $params, array_keys($params)));
	return $url.'?'.$paramStr;
}

function createLocationAPI() {

	global $locationUrl;
	global $contactId;
	global $country;
	global $Areas;
	global $pOwn;
	
	$locationUrl = createAPIURL(
					SearchLocationAPI, 
					array(
						'p1' => $contactId,
						'p2' => HashKey,
						'P_Country' => $country,
						'P_Area' => $Areas,
						'P_SortType' => 1, //alphabetic
						'P_Own' => $pOwn
					)
	);
}

function createSearchPropertyTypeAPI() {

	global $pTypesUrl;
	global $contactId;
	global $country;
	global $language;
	
	$pTypesUrl 	= createAPIURL(
					SearchPropertyTypeAPI, 
					array(
						'p1' => $contactId,
						'p2' => HashKey,
						'P_Country' => $country,
						'Lang' => $language
					)
	);
}

function createSearchFeatureAPI() {

	global $featureDataUrl;
	global $contactId;
	global $country;
	global $language;
	
	$featureDataUrl = createAPIURL(
					SearchFeatureAPI, 
					array(
						'p1' => $contactId,
						'p2' => HashKey,
						'P_Country' => $country,
						'Lang' => $language
					)
	);
}

function createSearchResaleAPI() {

	global $resaleResultsUrl;
	global $contactId;
	global $country;
	global $language;
	global $pPreferred;
	global $pOwn;
	
	$resaleResultsUrl = createAPIURL(
					SearchResaleAPI, 
					array(
						'p1' => $contactId,
						'p2' => HashKey,
						'P_Country' => $country,
						'Lang' => $language,
						'P_Preferred' => $pPreferred,
						'P_Own' => $pOwn
					)
	);
}

function createSearchRentalAPI() {

	global $rentalResultsUrl;
	global $contactId;
	global $country;
	global $language;
	global $pPreferred;
	global $pOwn;
	
	$rentalResultsUrl = createAPIURL(
					SearchRentalAPI, 
					array(
						'p1' => $contactId,
						'p2' => HashKey,
						'P_Country' => $country,
						'Lang' => $language,
						'P_Preferred' => $pPreferred,
						'P_Own' => $pOwn
					)
	);
}

function createPropertyDetailAPI() {

	global $propertyDetailUrl;
	global $contactId;
	global $language;
	
	$propertyDetailUrl = createAPIURL(
					PropertyDetailAPI, 
					array(
						'p1' => $contactId,
						'p2' => HashKey,
						'Lang' => $language
					)
	);
}

function createSearchLanguageAPI() {

	global $languageUrl;
	global $language;
	
	$languageUrl = createAPIURL(
					SearchLanguageAPI, 
					array(
						'language' => $language
					)
	);
}

function createFeaturedPropertiesAPI() {

	global $featureUrl;
	global $contactId;
	global $country;
	global $Areas;
	global $fDefaultArea;
	global $searchType;
	global $fPropertyType;
	global $fEnergyRating;
	global $fPNum;
	global $fSortType;
	global $language;

	$currency = isset($_SESSION["currency"]) ? $_SESSION["currency"] : 'EUR';

	$featureUrl = createAPIURL(
					FeaturedPropertiesAPI, 
					array(
						'p1' => $contactId,
						'p2' => HashKey,
						'P_Country' => $country,
						'P_Area' => $fDefaultArea,
						'P_SearchType' => $searchType,
						'P_PropertyTypes' => $fPropertyType,
						'P_Currency' => $currency,
						'P_EnergyRating' => $fEnergyRating,
						'P_Num' => $fPNum,
						'P_SortType' => $fSortType,
						'Lang' => $language
					)
	);
}

function createBookingCalendarAPI() {

	global $bookingCalendarUrl;
	global $contactId;
	
	$bookingCalendarUrl = createAPIURL(
					BookingCalendarAPI, 
					array(
						'p1' => $contactId,
						'p2' => HashKey
					)
	);
}

class TemplateHelper
{
	public static function render($templateFile, $data, $variable=array())
	{	
		foreach($variable as $k => $v) {
			if ($v != true){
				$v = 0;	
			}
			eval("$". $k . "=".$v.";");
		}

		$myContent = '';
		$logicOutput = '';
		$logicString = '';

		$temp = true;
		$templateData = file_get_contents($templateFile);
		$templateData = preg_replace( "/\r|\n/", "", $templateData );
		
		$pattern = '~{%(.*?)%}~';
		$content = '~{%}(.*?){%}~';
		preg_match_all($pattern, $templateData, $logic);
		preg_match_all($content, $templateData, $contentMatches);
		
		$count = 0;
		$loop1 = 0;
		$loop2 = 0;

		foreach($logic[1] as $item){
		
			$logicOutput .= $item;
			$logicString .= $logic[0][$count];
			//echo trim($contentMatches[1][$loop2]).'<br/>';
			if (strpos($item,'{')){
				$logicOutput .=  '$myContent.= "'.trim($contentMatches[1][$loop2]).'";';
				$logicString .= $contentMatches[1][$loop2];
				$loop2++;
			}
			if ($loop1 == 1){
				eval($logicOutput);
				$templateData = str_replace($logicString, $myContent , $templateData);

				$myContent = '';
				$logicOutput = '';
				$logicString = '';
				$loop1 = 0;
				$count++;	

			}else{
				$count++;	
				$loop1++;	
			}
		}
		
		if (is_array($data)) {
			foreach ($data as $key => $value) {  
				$search = '{' . $key . '}';
				$templateData = str_replace($search, $value, $templateData);
			}
		}
	
	return $templateData;
	}
}
?>