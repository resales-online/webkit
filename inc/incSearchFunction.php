<?php
function getLocations() {
	
	global $locationUrl;
	global $locationsXML;
	global $locationsJSON;
	
	createLocationAPI();

	$locationsXML = simplexml_load_file($locationUrl);
	$locationsJSON = json_encode($locationsXML,JSON_HEX_APOS);
	
	foreach ($locationsXML->children() as $areas) {
		foreach ($areas->children() as $area) {
		}
	}

	return $area;
}


function getAreas() {
	
	global $locationsXML;

	foreach ($locationsXML->Areas as $areas) {
	}

	return $areas;
}


function getPropertyTypes() {
	
	global $pTypesUrl;
	global $typesXML;
	global $typesJSON;
	
	createSearchPropertyTypeAPI();

	$typesXML = simplexml_load_file($pTypesUrl);
	$typesJSON = json_encode($typesXML,JSON_HEX_APOS);

	foreach ($typesXML->children() as $propertyTypes) {
	}
   
	return $propertyTypes;
}


function getAllPropertyTypes() {

	global $pTypesUrl;
	
	createSearchPropertyTypeAPI();

	return object2array($pTypesUrl);
}


function getFeaturesData() {

	global $featureDataUrl;
	
	createSearchFeatureAPI();

	$data = object2array($featureDataUrl);

	return $data;
}


function formatFeatureOption($data) {

	global $language;
	global $showFeature;
	global $featuresLanguage;

	$optionText = '';
	$currentCategoryName = '';

	$featureArrs = array_map("trim", explode(",", $showFeature));

	foreach($data as $k => $v) {
		foreach($v as $item) {
			if ( in_array($item['ConfigCategoryName'], $featureArrs) ) {
				if ( $item['CategoryName'] != $currentCategoryName ) {
					if ( $currentCategoryName != '' ) $optionText .= '</optgroup>';
					$currentCategoryName = $item['CategoryName'];
					$optionText .= '<optgroup label="'.$currentCategoryName.'">';
				}
				$optionText .= '<option class="chosen-item" value="'.$item['OptionName'].'">'.$item['FeatureName'].'</option>';
			}
		}
	}

	if ( $currentCategoryName != '' ) $optionText .= '</optgroup>';

	return $optionText;
}


function formatSearchTypeGroupOption($data) {

	$removeArr = getRemoveSearchTypes($data);
	$resultText = '';

	foreach($data['PropertyTypes']['PropertyType'] as $item) {
		$arrs = explode('-', $item['OptionValue']);
		if ( !in_array($arrs[0], $removeArr) ) {
			$resultText .= '<option class="category chosen-item" value="'.$item['OptionValue'].'">'.$item['Type'].'</option>';
			foreach($item['SubType'] as $k=>$v) {
				$resultText .= '<option class="item chosen-item" value="'.$v['OptionValue'].'">'.$v['Type'].'</option>';
			}
		}
	}

	return $resultText;
}


function getRemoveSearchTypes($data) {

	global $language;
	global $removeTypes;
	global $searchTypesLanguage;
	
	$result = array();

	foreach($data['PropertyTypes']['PropertyType'] as $item) {
		foreach($removeTypes as $value) {
			if ( $searchTypesLanguage[$value][$language] == $item['Type'] ) {
				$tmpArr = explode('-', $item['OptionValue']);
				$result[] = $tmpArr[0];
				break;
			}
		}
	}

	return $result;
}


function formatSelectOptions($selectArray) {

	global $defaultArea;
    global $removeLocations;
	
	$optionText = '';
	$i = 0;
	
	if ( isset($_SESSION['queryId']) )
		unset($_SESSION['queryId']);
		
	if ( is_object($selectArray) ) {

		if ( $selectArray->AreaName == $defaultArea ) {

			foreach ($selectArray->children() as $locations) {
				foreach ($locations->children() as $option) {
					if ( !in_array($option, $removeLocations) ) {
					    $optionText .= '<option class="chosen-item" value="'.$option.'">'.$option.'</option>';
					}
				}
			}
		} else {
			foreach ($selectArray->children() as $option) {
				if ( $option->getName() == 'PropertyType' ) {
					$optionText .= '<option class="chosen-item" value="'.$option->OptionValue.'">'.$option->Type.'</option>';
				}
			}
		}
	} else {

		foreach ($selectArray as $option){
			$i += 1;
			$moreThan = (count($selectArray) == $i ? '+' : '' );
			$optionText .= '<option value="'.$option.'">'.number_format ( $option, 0, ".", "." ).$moreThan;'</option>';
		}
	}

	return $optionText;
}


function formatAreaSelectOptions($areaArray) {

	global $defaultArea;

	$optionText = '';
	
	foreach($areaArray->Area as $area) {
		if ( $area->children()->getName() == 'AreaName' ) {
			$optionText .= '<option value="'.$area->AreaName.'" '.($area->AreaName == $defaultArea ? 'selected' : '' ).'>'.$area->AreaName.'</option>';
		}
	}

	return $optionText;
}


function formatOptions($dataArr, $isMap=true) {

	$optionText = '';

	foreach ($dataArr as $k=>$v) {
	    $optionText .= '	<option value="'.($isMap ? $k : $v).'">'.$v.'</option>';
	}

	return $optionText;
}


switch ($searchType) {
	
	case "All" || "Resale":
		$resultSearchType = '/ForSale/';
		break;
	case "Rental" || "RentalLT":
		$resultSearchType = '/LongTerm/';
		break;
	default:
		$resultSearchType = '/ShortTerm/';
}


function autoSearchLink($linkText, $location, $type, $searchType = '/ForSale/', $area = '',$subType = '', $min = '', $max = '') {

	validateAuthorisationKeys();

	global $resultsPage;
	global $autoRewritePattern;
	global $defaultArea;

	if ( $area == '' ) {
		$area = $defaultArea;
	}

	$arrayTmp = array( 'location'=>$location, 'area'=>$area, 'type'=> $type, 'subtype'=>$subType,'priceMin' => $min, 'priceMax' => $min );
	$data = array();
	$patterns = explode("-", $autoRewritePattern);
	
	foreach($patterns as $item) {
		if ( $item == 'type' ) {
			if ( $arrayTmp[$item] != '' ) {
			   $data[] = str_replace('-',' ',$arrayTmp[$item]);	
			}
		} else {
			 if ( $arrayTmp[$item] != '' ) {
				$data[] = $arrayTmp[$item];
			}
		}
	}

	$urlRewrite = implode('-',$data);

	if ( $searchType != '' ) {
		$tmpArr = array('/ForSale/'=>'Resale', '/LongTerm/'=>'RentalLT', '/ShortTerm/'=>'RentalST');
		$_SESSION["SearchType"] = $tmpArr[$searchType];
	} else {
		$_SESSION["SearchType"] = 'Resale';
	}

	return '<a href="'.$_SERVER['REQUEST_URI'].$resultsPage.$searchType.$urlRewrite.'.htm?auto=true">'.$linkText.'</a>';
}

?>
