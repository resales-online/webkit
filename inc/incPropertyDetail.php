<?php

function displayPropertyDetail() {
	return mapPropertyDetail(getPropertyDetail(getPropertyRef()),2);
}

function displayMetaTags(){
	return mapPropertyDetail(getPropertyDetail(getPropertyRef()),1);
}

function getPropertyRef() {

	global $rewritePatternUrl;

	$realURL = explode('.htm', basename($_SERVER['REQUEST_URI']));
	$urlArray = explode('-', $realURL[0]);
	$propertyRef = $urlArray[array_search('refid', explode('-',$rewritePatternUrl))];

	return $propertyRef;
}

function getSearchTypeInt() {

	global $fSearchType;

	if ( !isAccessFromFeatureSearch() ) {
		$searchTypes = array('Resale' => 1, 'RentalLT' => 3, 'RentalST' => 2);
		$searchType = 'Resale';
		if ( isset($_SESSION["SearchType"]) ) {
			$searchType = $_SESSION["SearchType"];
		}
		return $searchTypes[$searchType];
	} else {
		$searchTypes = array('Resale' => 1, 'Long Term Rental' => 2, 'Short Term Rental' => 3);
		return $searchTypes[$fSearchType];
	}
}

function getQueryIdByRefId($refId) {

	global $resaleResultsUrl;
	global $rentalResultsUrl;
	
	if ( isFeatureSearch() ) {
		$tmpUrl = $resaleResultsUrl.'&P_RefId='.$refId;
	} else {		
		$searchType = 'Resale';
		if ( isset($_SESSION["SearchType"]) ) {
			$searchType = $_SESSION["SearchType"];
		}
		switch ($searchType){
			case 'Resale':
				$tmpUrl = $resaleResultsUrl.'&P_RefId='.$refId;
				break;
			default:
				$tmpUrl = $rentalResultsUrl.'&P_RefId='.$refId;
		}
	}

	$data = object2array($tmpUrl);

	return $data['QueryInfo']['QueryId'];
}

function getPropertyDetail($apiString) {

	global $propertyDetailUrl;
	
	createPropertyDetailAPI();

	$tempPropertyDetailUrl = $propertyDetailUrl.'&searchType='.getSearchTypeInt().'&P_RefId='.$apiString;

	$xml = simplexml_load_file($tempPropertyDetailUrl);

	$tempPropertyDetailUrl = '';
		
	return $xml->Property;
}

function mapPropertyDetail($propertyDetail, $contentType) {	

	global 	$detailHeaderText;
	global 	$detailImage1;
	global 	$detailReference;
	global 	$detailPrice;
	global 	$detailPrice2;
	global 	$detailLocation;
	global 	$detailType;
	global  $detailROLType;
	global  $detailROLSubType;
	global 	$detailBeds;
	global 	$detailBaths;
	global 	$detailPlot;
	global 	$detailArea;
	global 	$detailTerrace;
	global  $detailEnergy;
	global 	$detailDescription;
	global 	$photoArray;
	global 	$featuresArray;
	global  $builds;
	global  $dimension;
	global  $agencyRef;
	global $languageArr;
	global $resultOutput;
	global $fSearchType;
	
	$resultText = '';
	$metaData = '';	

	switch ($contentType) {

		case 1 :
			if ( $propertyDetail !== NULL ) {
				$metaData = '';			
				$metaData .= (string)$propertyDetail->Type.',';
				$metaData .= (string)$propertyDetail->Area.',';
				$metaData .= (string)$propertyDetail->Location.',';
				$metaData .= (string)$propertyDetail->Reference.',';
				$metaData .= (string)$propertyDetail->Bedrooms.$languageArr['field_Headings']['bedrooms']['short_Header'].',';
				$metaData .= (string)$propertyDetail->Bathrooms.$languageArr['field_Headings']['bathrooms']['short_Header'].',';
				$resultOutput = $metaData;
			}
			break;

		case 2:
			if ( $propertyDetail !== NULL  ){			
					$detailType = (string)$propertyDetail->Type;
					$detailROLType = (string)$propertyDetail->ROLType;
					$detailROLSubType = (string)$propertyDetail->ROLSubType;
					$detailImage1 = (string)$propertyDetail->Pictures->Picture->PictureURL;
					$searchType = 'Resale';

					if ( isAccessFromFeatureSearch() ) {
						$searchType = $fSearchType;
					} else {
						if ( isset($_SESSION["SearchType"]) ) {
							$searchType = $_SESSION["SearchType"];
						}
					}
					
					if ( $searchType == 'Resale' ) {
						$detailPrice = (string)$propertyDetail->Price;
						$detailPrice2 = '';
					} else {
						$detailPrice = (string)$propertyDetail->RentalPrice1;
						$detailPrice2 = (string)$propertyDetail->RentalPrice2;
					}

					$detailArea = (string)$propertyDetail->Area;
					$detailLocation = (string)$propertyDetail->Location;
					$detailReference = (string)$propertyDetail->Reference;
					$detailDescription = (string)$propertyDetail->Description;
					$detailBeds = (string)$propertyDetail->Bedrooms;
					$detailBaths = (string)$propertyDetail->Bathrooms;
					$detailTerrace = (string)$propertyDetail->Terrace;
					$detailPlot = (string)$propertyDetail->GardenPlot;

					if ( isset($propertyDetail->EnergyRating->Value) && (string)$propertyDetail->EnergyRating->Value != '' ) {
						$detailEnergy  = (string)$propertyDetail->EnergyRating->Value.' '.(string)$propertyDetail->EnergyRating->Rated;
					} else {
						$detailEnergy  = '';
					}
					
					$detailHeaderText = str_replace('{i}',$detailBeds, $languageArr['detail_Headings']['detail_Title']);
					$detailHeaderText = str_replace('{t}',$detailType, $detailHeaderText);
					$detailHeaderText = str_replace('{l}',$detailLocation, $detailHeaderText);
					
					$builds = (string)$propertyDetail->Built;
					$dimension = (string)$propertyDetail->Dimensions;
					$agencyRef = (string)$propertyDetail->AgencyRef;
					$photoArray = getPhotos($propertyDetail->Pictures);
					$featuresArray = getFeatures($propertyDetail->PropertyFeatures);
					
					$resultText .= drawPropertyDetail();
				
			} else {
					$resultText .= "<br /><br />".$languageArr['results_Headings']['no_Result'];
			}

			$resultOutput = $resultText;
			break;
	}

	return $resultOutput;
}

function getFeatures($features) {
	
	global $showFeature;
	global $featuresLanguage;
	global $language;

	$categoryCount = 0;
	$featureList = '';
	$featurePatternArr = array_map("trim", explode(",", $showFeature));
	$featureArrs = array();

	foreach($featurePatternArr as $item) {
		$featureArrs[] = $featuresLanguage[$item][$language];
	}
	
	foreach ( $features->Category as $categories ) {

		$nextFeature = $categories->attributes();

		if ( in_array($nextFeature, $featureArrs) ) {

			$tmpText = '';
			$tmpBreak = '';
			$categoryCount = $categoryCount + 1;

			if ( $categoryCount == 3 ) {
				$categoryCount = 0;
				$tmpBreak = '<div style="clear:both; height:1px;"></div>';
			}

			foreach ($categories->Value as $feature) {
				$tmpText .= '<li><i class="fa fa-check-square"></i>&nbsp;'.$feature.'</li>';
			}
			
			$featureList .= TemplateHelper::render('templates/featureDetailItem.html', array(
								'Detail.FeatureItem.Header' => $categories->attributes(),
								'Detail.FeatureItem.List' => $tmpText,
								'Detail.FeatureItem.Break' => $tmpBreak
			));
		}
	}

	return $featureList;
}

function getPhotos($pictures) {

	$photoList = '';
	foreach ( $pictures->Picture as $picture ) {
		$photoList .= '<li><img src="'.$picture->PictureURL.'" style="width:80px;" /></li>';
	}

	return $photoList;
}

function contactForm() {

	global $languageArr;
	global $country;
	global $detailArea;
	global $detailReference;
	global $detailLocation;
	global $detailType;
	global $detailBeds;
	global $detailBaths;
	global $contactId;
	global $hashKey;
	global $language;
	global $registerLeadUrl;

	$result = TemplateHelper::render('templates/contactForm.html', array(
		'Property.ContactForm.Action' => RegisterLeadWebkitAPI,
		'Property.ContactForm.Title' => $languageArr['contact_Form']['header'],
		'Property.ContactForm.FirstName' => $languageArr['contact_Form']['firstName'],
		'Property.ContactForm.M1' => isset($_SESSION['M1']) ? $_SESSION['M1']:'',
		'Property.ContactForm.LastName' => $languageArr['contact_Form']['lastName'],
		'Property.ContactForm.M2' => isset($_SESSION['M2']) ? $_SESSION['M2']:'',
		'Property.ContactForm.Email' => $languageArr['contact_Form']['email'],
		'Property.ContactForm.M5' => isset($_SESSION['M5']) ? $_SESSION['M5']:'',
		'Property.ContactForm.Message' => $languageArr['contact_Form']['message'],
		'Property.ContactForm.Submit' => $languageArr['contact_Form']['submit'],
		'Property.ContactForm.DetailReference' => $detailReference,
		'Property.ContactForm.DetailCountry' => $country,
		'Property.ContactForm.DetailArea' => $detailArea,
		'Property.ContactForm.DetailLocation' => $detailLocation,
		'Property.ContactForm.DetailType' => $detailType,
		'Property.ContactForm.DetailBeds' => $detailBeds,
		'Property.ContactForm.DetailBaths' => $detailBaths,
		'Property.ContactForm.SearchType' => getSearchTypeInt(),
		'Property.ContactForm.ContactId' => $contactId,
		'Property.ContactForm.HashKey' => HashKey,
		'Property.ContactForm.Language' => $language,
		'Property.ContactForm.ErrFirstName' => $languageArr['contact_Form']['valid_firstName'],
		'Property.ContactForm.ErrLastName' => $languageArr['contact_Form']['valid_lastName'],
		'Property.ContactForm.ErrEmail' => $languageArr['contact_Form']['valid_email']
	));

    return $result;        
}

function isFeatureSearch() {

	if ( isAccessFromFeatureSearch() ) {
		return true;
	} else {
		return (!isset($_SESSION["Type"]) ? true : false);
	}
}

function isAccessFromFeatureSearch() {

	$realURL = explode('.htm', basename($_SERVER['REQUEST_URI']));
	$urlArray = explode('-', $realURL[0]);
	$lastItem = $urlArray[sizeof($urlArray) - 1];

	return $lastItem == 'Feature';
}
?>