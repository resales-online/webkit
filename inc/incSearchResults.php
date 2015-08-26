<?php

function displaySearchResults() {

	if ( isset($_GET["auto"]) ) {
		return outputResults(getResults(generateAutoSearchUrl()));
	} else {
		return outputResults(getResults(getSearchRequest()));
	}
}


function getSearchRequest() {
	
	global $resaleResultsUrl;
	global $rentalResultsUrl;
	global $searchResultType;
    global $languageArr;
    global $showChosen;
	
	createSearchResaleAPI();

	createSearchRentalAPI();

	$urlArray = explode('/',pathinfo($_SERVER['REQUEST_URI'],PATHINFO_DIRNAME ));
	$isSortAction = false;
	$pageSize = isset($_REQUEST["pageSize"]) ? $_REQUEST["pageSize"] : '';
	$pSort = isset($_REQUEST["pSort"]) ? $_REQUEST["pSort"] : '';
	$currency = isset($_REQUEST["currency"]) ? $_REQUEST["currency"] : '';

	if ( $pageSize != '' ) {
	    $_SESSION["pageSize"] = $pageSize;
	} else {
		$pageSize =	isset($_SESSION["pageSize"]) ? $_SESSION["pageSize"] : '10';
	}
	
	if ( $pSort != '' ) {
		$isSortAction = true;
	    $_SESSION["pSort"] = $pSort;
	} else {
		$pSort = isset($_SESSION["pSort"]) ? $_SESSION["pSort"] : '0';
	}

	if ( $currency != '' ) {
	    $_SESSION["currency"] = $currency;
	} else {
		$currency = isset($_SESSION["currency"]) ? $_SESSION["currency"] : 'EUR';
	}
	
	if ( isset($_SESSION["queryId"]) ) {

		$searchResultType = $_SESSION["SearchType"];
		$pageNo = isset($_REQUEST['pageNo']) ? $_REQUEST['pageNo'] : 1;
		$query =  $_SESSION["query"];
		$query->P_PageNo = $pageNo;
		$query->P_PageSize = $pageSize;
		$query->P_SortType = $pSort;
		
		if ( $isSortAction ) {
			if ( isset($_SESSION["SearchFeatures"]) ) {
				foreach($_SESSION["SearchFeatures"] as $item) {
					unset($query->$item);
				}
			}
			$_SESSION["SearchFeatures"] = array();	
		}

		$query->P_Currency = $currency;
		$resultsUrl = $_SESSION["rootUrl"].'&P_QueryId='.$_SESSION["queryId"].'&'.concatQuery($query);
		if ( isset($_REQUEST["pSort"]) ) {
		    $resultsUrl = $_SESSION["rootUrl"].'&'.concatQuery($query);
		}

		return $resultsUrl;
		
	} else {

        $query = new stdClass();
		$location 		=	isset($_POST["location"]) ? $_POST["location"] : '';
		$area 	  		=	isset($_POST["area"]) ? $_POST["area"] : '';
		$type 	  		=	isset($_POST["property-type"]) ? $_POST["property-type"] : '';
		$subType  		=	isset($_POST["subtype"]) ? $_POST["subtype"] : '';
		$beds	  		=	isset($_POST["beds"]) ? $_POST["beds"] : '';
		$baths    		=	isset($_POST["baths"]) ? $_POST["baths"] : '';
		$min     		=	isset($_POST["min"]) ? $_POST["min"] : '';
		$max      		=	isset($_POST["max"]) ? $_POST["max"] : '';
		$searchType     =	isset($_POST["searchType"]) ? $_POST["searchType"] : '';
		$rentalDateFrom =	isset($_POST["rental_date_from"]) ? $_POST["rental_date_from"] : '';
		$rentalDateTo   =	isset($_POST["rental_date_to"]) ? $_POST["rental_date_to"] : '';
		$rating         =	isset($_POST["energyRating"]) ? $_POST["energyRating"] : '';
		$refId          =	isset($_POST["s"]) ? $_POST["s"] : '';
		$startRental    =	isset($_POST["startRentalDate"]) ? $_POST["startRentalDate"] : '';
		$endRental      =	isset($_POST["endRentalDate"]) ? $_POST["endRentalDate"] : '';
		$features       =	isset($_POST["features"]) ? $_POST["features"] : '';

		if ( $startRental != '' ) {
			$_SESSION["startRentalDate"] = $startRental;
		}
		
		if ( $endRental != '' ) {
			$_SESSION["endRentalDate"] = $endRental;
		}
		
		if ( $area != '') {
			$_SESSION["Area"] = $area;
			$query->P_Area = $area;
		} else {
			$_SESSION["Area"] = 'Costa del Sol';
		}

		if ( $location != '' ) {
			$query->P_Location = implode(',', $location);
		}

		if ( $type != '' ) {
			$query->P_PropertyTypes = implode(',', $type);
		}

		if ( $beds != '' ) {
			$query->P_Beds = $beds;
		}

		if ( $baths != '' ) {
			$query->P_Baths = $baths;
		}

		if ( $min != '' ) {
			$query->P_Min = $min;
		}

		if ( $max != '' ) {
			$query->P_Max = $max;
		}

		if ( $rentalDateFrom != '' ) {
			$query->P_RentalDateFrom = $rentalDateFrom;
		}

		if ( $rentalDateTo != '' ) {
			$query->P_RentalDateTo = $rentalDateTo;
		}

		if ( $currency != '' ) {
			$query->P_Currency = $currency;
		}

		if ( $rating != '' ) {
			$query->P_EnergyRating = $rating;
		}

		if ( $refId != '' ) {
			$query->P_RefId = $refId;
		}

		if ( $searchType != '' ) {
			$_SESSION["SearchType"] = $searchType;
			$_SESSION["Type"] = "1";
		} else {
			$_SESSION["SearchType"] = 'Resale';
		}

		if ( $features != '' ) {
			$_SESSION["SearchFeatures"] = $features;
			foreach($features as $item) {
				if ( !empty($item) ) $query->$item = '1';
			}
		} else {
			$_SESSION["SearchFeatures"] = array();
		}

		$query->P_PageSize = $pageSize;
		$query->P_SortType = $pSort;
		
		if ( $searchType == 'All' || $searchType == 'Resale' ) {
			$resultsUrl = $resaleResultsUrl.'&'.concatQuery($query);
			$_SESSION["rootUrl"] = $resaleResultsUrl;
		} elseif ( $searchType == 'Rental' || $searchType == 'RentalLT' ) {
			$query->P_RentalType = 'L';
			$query->P_SortType = $pSort;
			$resultsUrl = $rentalResultsUrl.'&'.concatQuery($query);
			$_SESSION["rootUrl"] = $rentalResultsUrl;
		} else {
			$query->P_RentalType = 'S';
			$query->P_SortType = $pSort;
			$resultsUrl = $rentalResultsUrl.'&'.concatQuery($query);
			$_SESSION["rootUrl"] = $rentalResultsUrl;
		}

		$_SESSION["query"] = $query;

		return $resultsUrl;
	}
}

function concatQuery($obj) {

	$str = '';
	foreach ($obj as $key => $value) {
		$str .= "$key=$value&";
	}
	return rtrim($str, '&');	
}


function getResults($apiString) {

	global $queryInfo;

	$xml = simplexml_load_file($apiString);

	if ( count($xml->Property) != 0 ) {
	    outputResultPagination($xml->QueryInfo);
	}

	$queryInfo = $xml->QueryInfo;

	if ( count($xml->Property ) == 0) {
		return NULL;
	} else { 
		return $xml->Property;
	}
}

function outputResultPagination($queryInfo) {

	global $pageNumber;
	global $queryId;
	global $propertyCount;
	global $pageCount;
	
	$pageNumber 	= (string)$queryInfo->CurrentPage;
	$queryId		= (string)$queryInfo->QueryId;
	$propertyCount 	= (string)$queryInfo->PropertyCount;
	$propertyPerPage = (string)$queryInfo->PropertiesPerPage;

	$pageCount		= intval((int)$propertyCount / (int)$propertyPerPage);
	if ( $pageCount < ( (int)$propertyCount / (int)$propertyPerPage) ) $pageCount = $pageCount + 1;

	$_SESSION["queryId"] = $queryId;

	return displayResultPagination();
}

function generateAutoSearchUrl() {

	global $resaleResultsUrl;
	global $rentalResultsUrl;
	global $searchResultType;
    global $languageArr;
    global $defaultArea;
	global $autoRewritePattern;
	
	createSearchResaleAPI();
	createSearchRentalAPI();
	
	$query = new stdClass();
	$type = '';
	$realURL = explode('.htm', basename($_SERVER['REQUEST_URI']));
	$urlArray = explode('-', $realURL[0]);
	$optionsCount = count($urlArray);
	$patternArray = explode('-', $autoRewritePattern);
	$tempPattern = array();
	
	//For paging
	$pageSize =	isset($_SESSION["pageSize"]) ? $_SESSION["pageSize"] : '10';
	$pSort = isset($_SESSION["pSort"]) ? $_SESSION["pSort"] : '0';
	$currency = isset($_SESSION["currency"]) ? $_SESSION["currency"] : 'EUR';
	$query->P_PageSize = $pageSize;
	$query->P_SortType = $pSort;

	for ($i = 0; $i < $optionsCount; $i++ ){
		$tempPattern[$i] = $patternArray[$i];
	}

	if (in_array('location',$tempPattern )){
		$location = $urlArray[array_search('location', explode('-',$autoRewritePattern))];
	}
	if (in_array('area',$tempPattern )){
		$area = $urlArray[array_search('area', explode('-',$autoRewritePattern))];
	}
	if (in_array('type',$tempPattern )){
		$type = $urlArray[array_search('type', explode('-',$autoRewritePattern))];
	}
	if (in_array('subtype',$tempPattern )){
		$subType = $urlArray[array_search('subtype', explode('-',$autoRewritePattern))];
	}
	if (in_array('priceMin',$tempPattern )){
		$min = $urlArray[array_search('priceMin', explode('-',$autoRewritePattern))];
	}
	if (in_array('priceMax',$tempPattern )){
		$max = $urlArray[array_search('priceMax', explode('-',$autoRewritePattern))];
	}
	switch ($type){
		case 'Apartment': 
			$type = '1-1';
			break;	
		case 'House': 
			$type = '2-1';
			break;	
		case 'Plot': 
			$type = '3-1';
			break;	
		case 'Commercial': 
			$type = '4-1';
			break;	
	}

	if ( isset($area) ){
		$_SESSION["Area"] = $area;
		$query->P_Area=$area;
	} else {
		$_SESSION["Area"] = $defaultArea;
	}
	
	if ( isset($location)){
		$query->P_Location = $location;
	}

	if ( isset($subType) ) {
		$_SESSION["SubType"] = $subType;
		$typeArr = explode('-', $subType);
		$_SESSION["Type"] = $typeArr[0];
		$query->P_PropertyTypes = $subType;
	} else {
		if ( isset($type) ) {
			$typeArr = explode('-', $type);
			$_SESSION["Type"] = $typeArr[0];
			$query->P_PropertyTypes = $type;
		}
		$_SESSION["SubType"] = 'All';
	}

	if ( isset($min) ) {
		$query->P_Min = $min;
	}

	if ( isset($max) ) {
		$query->P_Max = $max;
	}

	$resultsUrl = $resaleResultsUrl.'&'.concatQuery($query);
	$_SESSION["rootUrl"] = $resaleResultsUrl;
	$_SESSION["query"] = $query;

	return $resultsUrl;
}

function outputResults($propertyResults) {

	global $resultType;
	global $resultImage;
	global $resultPrice;
	global $resultPrice2;
	global $resultLocation;
	global $resultReference;
	global $resultDescription;
	global $resultBeds;
	global $resultBaths;
	global $resultTerrace;
	global $resultPlot;
	global $urlRewrite;
	global $searchResultType;
	global $rewritePatternUrl;
	global $queryInfo;
	global $built;
	global $languageArr;
	global $featureSearchArr;
	global $resultTypeVal;
	
	$_SESSION['isFSearch'] = 0;
	$resultText = '';
	
	if ( $propertyResults !== NULL ) {		

		foreach ( $propertyResults as $property ) {

			if ( $_SESSION["SearchType"] == 'Resale' ) {
				$resultPrice = (string)$property->Price;
				$resultPrice2 = '';
			} else {
				$resultPrice = (string)$property->RentalPrice1;
				$resultPrice2 = (string)$property->RentalPrice2;
			}

			$resultType = (string)$property->Type;
			$resultTypeVal = (string)$property->TypeVal;
			$resultImage = (string)$property->MainImage;
			$resultLocation = (string)$property->Location;
			$resultReference = (string)$property->Reference;
			$resultDescription = (string)$property->Description;
			$resultBeds = (string)$property->Bedrooms;
			$resultBaths = (string)$property->Bathrooms;
			$resultTerrace = (string)$property->Terrace;
			$resultPlot = (string)$property->GardenPlot;
			$built =  (string)$property->Built;
			$area = (string)$property->Area;
			
			$arr = array();

			foreach ($property->PropertyFeatures as $feature) {
				foreach($feature->Category as $item) {
					$categoryName = (string)$item->attributes()->Name;
					$categoryType = (string)$item->attributes()->Type;
					$tmpArr = array();
					foreach($item->Feature as $data) {
						$feValue = (string)$data->Value;
						$feName = (string)$data->Name;
						$tmpArr[$feValue] = array('CategoryType'=>$categoryType, 'FeatureValue'=>$feValue, 'FeatureName'=>$feName);
					}
					$arr[$categoryName] = $tmpArr;
				}
			}

			$featureSearchArr = $arr;

			//Rewrite url with pattern config from roWebKit
			//option: beds, baths, type, location, refid, area, search_type, subtype

			$arrayTmp = array('beds'=>$resultBeds, 'baths'=>$resultBaths, 'location'=>$resultLocation, 'refid'=>$resultReference, 'search_type'=>$_SESSION["SearchType"], 'type' => $resultType, 'area'=> $area);
			$data = array();
			$patterns = explode("-", $rewritePatternUrl);

			foreach($patterns as $item) {
				if($item == 'type') {
					if($arrayTmp[$item] != '') {
					   $data[] = str_replace('-',' ',$arrayTmp[$item]);	
					}
				} else {
					if($arrayTmp[$item] != '' && !in_array($item, array('refid', 'location', 'type', 'search_type', 'area', 'subtype'))) {
						$data[] = $arrayTmp[$item].$item;
					} else {
						$data[] = $arrayTmp[$item];
					}
				}
			}

            $urlRewrite = implode('-',$data);
			$resultText .= displayResult();
		}
		
	} else {
			$resultText .= "<br /><br />".$languageArr['results_Headings']['no_Result'];
	}

	echo $resultText;
	
	if ( $propertyResults != NULL ) {
	    outputResultPagination($queryInfo);
	}
	
	createHiddenParam();
}

function createMetaData() {

	$searchType	=	isset($_REQUEST["searchTypeValue"]) ? $_REQUEST["searchTypeValue"] : '';
	$location 	=	isset($_REQUEST["locationValue"]) ? $_REQUEST["locationValue"] : '';
	$area 	  	=	isset($_REQUEST["areaValue"]) ? $_REQUEST["areaValue"] : '';
	$type 	  	=	isset($_REQUEST["propertyTypeValue"]) ? $_REQUEST["propertyTypeValue"] : '';
	$subType  	=	isset($_REQUEST["subTypeValue"]) ? $_REQUEST["subTypeValue"] : '';
	$beds	  	=	isset($_REQUEST["bedValue"]) ? $_REQUEST["bedValue"] : '';
	$baths    	=	isset($_REQUEST["bathValue"]) ? $_REQUEST["bathValue"] : '';
	
	$metaData = '';
	
	if ( $searchType != '' ) {
		$metaData = $searchType.',';
	}
	
	if ( $area != '' ) {
		$metaData .= $area.','; 
	}
	
	if ( $location != '' ) {
		$metaData .= $location.',';
	}
	
	if ( $subType != 'SubType' ) {
		$metaData .= $subType.',';
	} else {
		if ( $type != '' ) {
			$metaData .= $type.',';
		}
	}
	
	if ( $beds != '' ) {
		$metaData .= $beds.',';
	}
	
	if ( $baths != '' ) {
		$metaData .= $baths.',';
	}

	if ( $searchType == '' ) {
		$metaData = $_SESSION["metaData"];
	} else {
		$_SESSION["metaData"] = $metaData;
	}

	return $metaData;
}

function createHiddenParam() {

	if ( isset($_SESSION["pageSize"]) ) {
		$pageS = $_SESSION["pageSize"];
	} else {
	    $pageS = '10';
	}

	if ( isset($_SESSION["pSort"]) ) {
	    $pageSort = $_SESSION["pSort"];
    } else {
	    $pageSort = '0';
	}
	
	echo "<input type='hidden' id='pageSizeOld' value='".$pageS."' />";

	if ( isset($_SESSION["SearchFeatures"]) && sizeof($_SESSION["SearchFeatures"]) != 0 ) {
		$pageSort = '';
	}
	
	echo "<input type='hidden' id='pSortOld' value='".$pageSort."' />";
	if ( isset($_SESSION["metaData"]) ) {
		echo "<input type='hidden' id='metaData' value='".$_SESSION["metaData"]."' />";
	}
			  
	echo "<input type='hidden' id='searchTypeValue' name='searchTypeValue' value='".$_SESSION["SearchType"]."' />";

	if ( isset($_SESSION["query"]) ) {
		$query = $_SESSION["query"];
		echo "<input type='hidden' id='areaValue' name='areaValue' value='".$query->P_Area."' />";
		echo "<input type='hidden' id='locationValue' name='locationValue' value='".(isset($query->P_Location) ? $query->P_Location : '')."' />";
		echo "<input type='hidden' id='bedValue' name='bedValue' value='".(isset($query->P_Beds) ? $query->P_Beds : '')."' />";
		echo "<input type='hidden' id='bathValue' name='bathValue' value='".(isset($query->P_Baths) ? $query->P_Baths : '')."' />";
	}
}

//For search feature in main form
function groupCategory($type='1') {

	$data = getFeaturesData();
	$result = array();
	$result2 = array();

	foreach($data['FeaturesData']['Category'] as $item) {

		if ( $item['Cat_Type'] == $type ) {

			if ( array_key_exists($item['Name'], $result) ) {
				$tmpArr = $result[$item['Name']];
				$tmpArr[] = array('CategoryType'=>$item['Cat_Type'], 'ConfigCategoryName'=>$item['ConfigName'], 'CategoryName'=>$item['Name'], 'FeatureValue'=>$item['Feature']['@attributes']['value'], 'FeatureName'=>$item['Feature']['Name'], 'OptionName'=>$item['Cat_Type'].$item['ConfigName'].$item['Feature']['@attributes']['value'].'_2');
			} else {
				$tmpArr = array();
				$tmpArr[] = array('CategoryType'=>$item['Cat_Type'], 'ConfigCategoryName'=>$item['ConfigName'], 'CategoryName'=>$item['Name'], 'FeatureValue'=>$item['Feature']['@attributes']['value'], 'FeatureName'=>$item['Feature']['Name'], 'OptionName'=>$item['Cat_Type'].$item['ConfigName'].$item['Feature']['@attributes']['value'].'_2');
			}

			$result[$item['Name']] = $tmpArr;
			
			$result1[$item['Cat_Type'].$item['ConfigName'].$item['Feature']['@attributes']['value']] = array('ConfigCategoryName'=>$item['ConfigName'], 'CategoryName'=>$item['Name'], 'CategoryType'=>$item['Cat_Type'],'FeatureValue'=>$item['Feature']['@attributes']['value'], 'FeatureName'=>$item['Feature']['Name']);
		}
	}

	return array('groupCategoryData'=>$result, 'swapCategoryData'=>$result1);
}

function orderByCategory($featureArray) {

	$datas = groupCategory('1');
	$fullFeatures = $datas['swapCategoryData'];
	$result = array();

	foreach($featureArray as $item) {

		$tmpArr = explode('_', $item);
		$category = getCategoryFromName($tmpArr[0]);
		$nextFeature = $fullFeatures[$tmpArr[0]];

		if ( array_key_exists($category, $result) ) {
				$tmpArr = $result[$category];
				$tmpArr[$nextFeature['FeatureValue']] = array('CategoryName'=>$nextFeature['CategoryName'], 'CategoryType'=>$nextFeature['CategoryType'], 'FeatureValue'=>$nextFeature['FeatureValue'],'FeatureName'=>$nextFeature['FeatureName']);
				$result[$category] = $tmpArr;
		} else {
				$tmpArr = array();
				$tmpArr[$nextFeature['FeatureValue']] = array('CategoryName'=>$nextFeature['CategoryName'], 'CategoryType'=>$nextFeature['CategoryType'], 'FeatureValue'=>$nextFeature['FeatureValue'],'FeatureName'=>$nextFeature['FeatureName']);
				$result[$category] = $tmpArr;
		}
	}

	return $result;
}

function getCategoryFromName($data) {

	return preg_replace('/[0-9_]+/', '', $data);
}