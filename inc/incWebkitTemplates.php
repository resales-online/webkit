<?php
function getBedBathLanguage($keys, $type='bedrooms') {

	global $languageArr;
	
	$newKey = array();
	foreach($keys as $k) {
		$newKey[] = $k;
		$newKey[] = $k.'+';
	}
	$keys = $newKey;
	$languages = $languageArr['field_Headings'][$type];
	$result = array();

	foreach($keys as $k) {
		if($k == '1') {
			$result[$k] = LanguageHelper::format($languages['option_single'], array('i' => $k));
		} else if($k == '1+'){
			$result[$k] = LanguageHelper::format($languages['option_singleGreaterThan'], array('i' => substr($k, 0, -1)));
		} else {
			if(strrpos($k, '+') === false) {
				$result[$k] = LanguageHelper::format($languages['option_multiple'], array('i' => $k));
			} else {
				$result[$k] = LanguageHelper::format($languages['option_multipleGreaterThan'], array('i' => substr($k, 0, -1)));
			}
		}
	}

	return $result;
}


function getEnergyRatingLanguage($keys) {

	global $languageArr;

	$languages = $languageArr['field_Headings']['energyRating'];
	$result = array();
	$rates = array('','','','A', 'B+', 'B', 'C+', 'C', 'D+', 'D', 'E+', 'E', 'F+', 'F', 'G+', 'G');
	$index = 0;

	foreach($keys as $k) {
		if($k == '1') {
			$result[$k] = $languages['option_NotRated'];
		} else if($k == '2'){
			$result[$k] = $languages['option_Rated'];
		} else if($k == '3'){
			$result[$k] = $languageArr['field_Headings']['option_NoPref'];
		} else {
			if(strrpos($rates[$index], '+') === false) {
				$result[$k] = LanguageHelper::format($languages['option_rating'], array('i' => $rates[$index]));
			} else {
				$result[$k] = LanguageHelper::format($languages['option_ratingGreaterThan'], array('i' => substr($rates[$index], 0, -1)));
			}
		}
		$index++;
	}

	return $result;
}


function generateRange($from, $to, $range) {

	$count=0; 
	$result=array();
	$rangeArr = array(5=>2, 8=>2, 10=>5, 19=>10);

	for( $i = $from; $i <= $to; $i=$i+$range ){
		$count++;
		if(!empty($rangeArr[$count])){
			$range = $range*$rangeArr[$count];
		}
		$result[] = $i;
	}

	return $result;
}
function getSearchTypeOptions() {
	global $searchType;
	global $languageArr;
	
	$res = '';
	if ($searchType != 'Rental'){
		$res = '<option value="Resale">'.$languageArr['search_Types']['for_Sale'].'</option>';
	}
	$res .= '<option value="RentalLT">'.$languageArr['search_Types']['longTerm_Rent'].'</option>';
	$res .= '<option value="RentalST">'.$languageArr['search_Types']['shortTerm_Rent'].'</option>';
	
	return $res;
}

function displaySearchForm() {

	global $bedsBaths;
	global $resultsPage;
	global $showAreas;
	global $showSubtypes;
	global $locationsJSON;
	global $typesJSON;
	global $searchType;
	global $resultSearchType;
	global $currencies;
	global $energyRatings;
	
	global $showCurrency;
	global $showBeds;
	global $showBaths;
	global $showRating;
	global $languageArr;
	global $datePickerLanguages;
	global $language;
	global $showFeature;
	global $featuresLanguage;
	global $showChosen;
	
	$currencies = array('EUR', 'GBP', 'USD');
	$energyRatings = array();
	$rateKeys = array();
	for($i=1; $i<=16;$i++) {
		$rateKeys[] = $i;
	}
	$energyRatings = getEnergyRatingLanguage($rateKeys);
	
	$bedsBaths = array(1,2,3,4,5,6,7,8,9,10);
	
	$bedsData = getBedBathLanguage(array('1','2','3','4','5','6','7','8','9'));
	
	$bathsData = getBedBathLanguage(array('1','2','3','4','5','6','7','8'), 'bathrooms');
	
	$currencyIcon = array('EUR'=> '&euro;', 'GBP' => '&pound;', 'USD'=>'$');
	
	$priceRange = generateRange(10000,10000000,5000);
	$priceRangeRentalLT = generateRange(450,5000,50);
	$priceRangeRentalST = generateRange(300,5000,50);
	
	$locationsList = formatSelectOptions(getLocations());
	
	$featureCategories = groupCategory();
	$featureList = '';
	$featureArrs = array_map("trim", explode(",", $showFeature));
	$currentCategoryName = '';
	$tmpText = '';

	foreach($featureCategories['groupCategoryData'] as $k => $v) {
		foreach($v as $item) {
			if ( in_array($item['ConfigCategoryName'], $featureArrs) ) {
				if ( $item['CategoryName'] != $currentCategoryName ) {
					if ( $currentCategoryName != '' ) {
						$featureList .= TemplateHelper::render('templates/featureListItem.html', array(
							'Search.Feature.More.Title' => $currentCategoryName,
							'Search.Feature.More.Items' => $tmpText
						));
					}
					$currentCategoryName = $item['CategoryName'];
					$tmpText = '';
				}
				$tmpText .= '<div class="col-xs-6"><input class="feature-more text-nowrap" type="checkbox" value="'.$item['OptionName'].'" />&nbsp;'.$item['FeatureName'].'</div>';
			}
		}
	}

	if ( $tmpText != '' ) {
		$featureList .= TemplateHelper::render('templates/featureListItem.html', array(
							'Search.Feature.More.Title' => $currentCategoryName,
							'Search.Feature.More.Items' => $tmpText
						));
	}

	$searchForm = TemplateHelper::render('templates/searchForm.html', array(
		'Search.Form.Action' =>$resultsPage,
		'Search.PropertyId' => $languageArr['field_Headings']['prop_Id'],
		'Search.SearchType.ForSale' => $languageArr['search_Types']['for_Sale'],
		'Search.SearchType.LongTerm' => $languageArr['search_Types']['longTerm_Rent'],
		'Search.SearchType.ShortTerm' => $languageArr['search_Types']['shortTerm_Rent'],
		'Search.Rental.From' => $languageArr['field_Headings']['rental_From'],
		'Search.Rental.To' => $languageArr['field_Headings']['rental_To'],
		'Search.Area.Options' => formatAreaSelectOptions(getAreas()),
		'Search.Location.Placeholder' => $languageArr['field_Headings']['select_Locations'],
		'Search.Location.NoRef' => $languageArr['field_Headings']['option_NoPref'],
		'Search.Location.Options' => $locationsList,
		'Search.PropertyType.Placeholder' => $languageArr['field_Headings']['select_Types'],
		'Search.PropertyType.NoRef' => $languageArr['field_Headings']['option_NoPref'],
		'Search.PropertyType.Options' => formatSearchTypeGroupOption(getAllPropertyTypes()),
		'Search.Feature.Placeholder' => $languageArr['field_Headings']['select_Features'],
		'Search.Feature.NoRef' => $languageArr['field_Headings']['option_NoPref'],
		'Search.Feature.Options' => formatFeatureOption($featureCategories['groupCategoryData']),
		'Search.Feature.More.List' => $featureList,
		'Search.Beds.NoRef' => $languageArr['field_Headings']['bedrooms']['header'],
		'Search.Beds.Options' => formatOptions($bedsData),
		'Search.Baths.NoRef' => $languageArr['field_Headings']['bathrooms']['header'],
		'Search.Baths.Options' => formatOptions($bathsData),
		'Search.MinPrice.Title' => $languageArr['field_Headings']['price_Min'],
		'Search.MinPrice.Placeholder' => $languageArr['field_Headings']['price_Min'],
		'Search.MaxPrice.Title' => $languageArr['field_Headings']['price_Max'],
		'Search.MaxPrice.Placeholder' => $languageArr['field_Headings']['price_Max'],
		'Search.Currency.NoRef' => $languageArr['field_Headings']['currency'],
		'Search.Currency.Options' => formatOptions($currencies, false),
		'Search.EnergyRating.NoRef' => $languageArr['field_Headings']['energyRating']['header'],
		'Search.EnergyRating.Options' => formatOptions($energyRatings),
		'Search.Button.Submit' => $languageArr['field_Headings']['search_Button'],
		'Search.Location.JSON' => $locationsJSON,
		'Search.Types.JSON' => $typesJSON,
		'Search.Price.RangeData' => json_encode($priceRange),
		'Search.Price.RangeRentalLongTermData' => json_encode($priceRangeRentalLT),
		'Search.Price.RangeRentalShortTermData' => json_encode($priceRangeRentalST),
		'Search.TypeData' => $searchType,
		'Search.ResultPage' => $resultsPage,
		'Search.Currency.Old' => isset($_SESSION["currency"]) ? $_SESSION["currency"] : 'EUR',
		'Search.LanguageCode' => $datePickerLanguages[$language],
		'Search.ShowChosen' => $showChosen,
		'Search.Type.Options' => getSearchTypeOptions()
	), array(
		'showSearchType' => ( $searchType == 'All' || $searchType == 'Rental' ),
		'showAreas' => $showAreas,
		'showBeds' => $showBeds,
		'showBaths' => $showBaths,
		'showCurrency' => $showCurrency,
		'showRating' => $showRating,
		'showChosen' => $showChosen
	));

	echo  $searchForm;
}


function displayResultPagination() {

	global $pageNumber;
	global $queryId;
	global $propertyCount;
	global $pageCount;
	global $resultsPage;
	global $searchResultType;
	global $languageArr;
	global $dir;
	
	$resultsPage .= $searchResultType;
	
	$searchPagination = TemplateHelper::render('templates/resultsPagination.html', array(
		'Paging.Search.Action' => $dir.'/searchResults.php',
		'Paging.PageOf' => LanguageHelper::format($languageArr['results_Headings']['pagination']['page_Count'], array('i' => $pageNumber, 'x' => $pageCount)),
		'Paging.First.Link' => ($pageNumber > 1 ? 'href="'.'?pageNo='. 1 .'"' : ' class="wbPagButtonDisable"'),
		'Paging.First.Text' => $languageArr['results_Headings']['pagination']['first'],
		'Paging.Prev.Link' => ($pageNumber > 1 ? 'href="'.'?pageNo='.($pageNumber - 1).'"' : 'class="wbPagButtonDisable"' ),
		'Paging.Prev.Text' => $languageArr['results_Headings']['pagination']['prev'],
		'Paging.Next.Link' => ($pageCount == $pageNumber ? 'class="wbPagButtonDisable"' : 'href="'.'?pageNo='.($pageNumber + 1).'"'),
		'Paging.Next.Text' => $languageArr['results_Headings']['pagination']['next'],
		'Paging.Last.Link' => ($pageCount == $pageNumber ? 'class="wbPagButtonDisable"' : 'href="'.'?pageNo='.$pageCount.'"'),
		'Paging.Last.Text' => $languageArr['results_Headings']['pagination']['last'],
		'Paging.ResultCount' => $languageArr['results_Headings']['resultCount'],
		'Paging.ShortHeader' => $languageArr['results_Headings']['sort']['header'],
		'Paging.SortOption' => formatOptions(
									array(
										'' => $languageArr['results_Headings']['sort']['options_Features'],
										'0' => $languageArr['results_Headings']['sort']['options_PriceAsc'], 
										'1' => $languageArr['results_Headings']['sort']['options_PriceDesc'], 
										'2' => $languageArr['results_Headings']['sort']['options_Location']
									)
							   )
	));

	echo $searchPagination;
}


function createUrl($root, $params, $separator, $extend) {

	$url = $root.$separator.implode($separator, $params).$extend;

    return str_replace('//', '/', $url);
}


function displayResult() {
	
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
	global $detailsPage;
	global $urlRewrite;
	global $resultSearchType;
	global $currencyIcon;
	global $built;
	global $dir;
	global $languageArr;
	global $featureSearchArr;
	global $resultTypeVal;
	
	$currencyIcon = array('EUR'=> '&euro;', 'GBP' => '&pound;', 'USD'=>'$');
	
	$price = '';

	switch ($_SESSION["SearchType"]) {
		
		case "Resale" :
			if ( $resultPrice != '' ) {
				$price .= formatPrice($resultPrice, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
			}
			break;

		case "RentalLT" :
			if ( $resultPrice != '' ) {
				$price .= formatPrice($resultPrice, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
				$price .= ' /'.$languageArr['detail_Headings']['month'];
			}
			break;

		default :
			if ( $resultPrice != '' ) {
				$price .= formatPrice($resultPrice, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
			}

			if ( $resultPrice2 != '' && $resultPrice2 != $resultPrice ) {
				if ( $price != '' ) $price .= ' - ';
				$price .= formatPrice($resultPrice2, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
			}

			if ( $price != '' ) $price .= ' /'.$languageArr['detail_Headings']['week'];
	}

	$title = $price.' - '.$resultLocation;
	$title .= ' - '.$resultReference;

	$resultText = TemplateHelper::render('templates/resultsList.html', array(
				'Result.DetailUrl' => createUrl($dir.'/', array($detailsPage, $urlRewrite), '/', '.htm'),
				'Result.Image' => $resultImage,
				'Result.Title' => $title,
				'Result.Description' => $resultDescription,
				'Result.Beds.Label' => $languageArr['field_Headings']['bedrooms']['short_Header'],
				'Result.Beds.Value' => $resultBeds,
				'Result.Baths.Label' => $languageArr['field_Headings']['bathrooms']['short_Header'],
				'Result.Baths.Value' => $resultBaths,
				'Result.BuildSize.Label' => $languageArr['field_Headings']['built_Size'],
				'Result.BuildSize.Value' => formatArea($built, "m&sup2;"),
				'Result.Terrace.Label' => $languageArr['field_Headings']['terrace_Size'],
				'Result.Terrace.Value' => formatArea($resultTerrace,"m&sup2;"),
				'Result.Plot.Label' => $languageArr['field_Headings']['plot_Size'],
				'Result.Plot.Value' => formatArea($resultPlot,"m&sup2;"),
				'Result.MatchedFeature' => drawMatchFeature(),
			),
			array('resultTerrace' => $resultTerrace, 'isApartment' => ($resultTypeVal == 'Apartment'), 'isResidential'=> (($resultTypeVal != 'Plot') && ($resultTypeVal != 'Commercial')))
	);		
	
	return $resultText;
}


function drawMatchFeature() {

	global $featureSearchArr;

	$resultText = '<div class="featureMatched">';
	$matchedFeatures = '';

	if ( isset($_SESSION["SearchFeatures"]) ) {

		$groupFeatureArr = orderByCategory($_SESSION["SearchFeatures"]);
		$matchedFeatures = '';
/*
	echo "<pre>";
	print_r($groupFeatureArr);
	echo "</pre>";
	exit(0);
*/
		foreach($groupFeatureArr as $key=>$value) {
			foreach($value as $k=>$v) {
				$tmpArr = $v;
				$tmpArr['Show'] = '0';
				if ( isset($featureSearchArr[$key]) ) {
					if ( array_key_exists($k, $featureSearchArr[$key]) ) {
						$tmpArr['Show'] = '1';
					}
				}
				$value[$k] = $tmpArr;
			}
			$groupFeatureArr[$key] = $value;
		}
		
		foreach($groupFeatureArr as $key=>$value) {
			$tmpArr = array();
			foreach($value as $k=>$v) {
				$tmpArr[] = '<span class="'.($v['Show'] == '0' ? 'wbResFeatureValueStrikethrough' : 'wbResFeatureValue').'">'.$v['FeatureName'].'</span>';
			}
			$matchedFeatures .= TemplateHelper::render('templates/matchedFeatures.html', array(
    			'Search.MatchedFeatures.Label' => $v['CategoryName'].':&nbsp;',
				'Search.MatchedFeatures.List' => implode(', ', $tmpArr),
			));
		}
	}

	$resultText .= $matchedFeatures;
	$resultText .= '</div>';

	return $resultText;
}


function featureResult() {
	
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
	global $detailsPage;
	global $urlRewrite;
	global $resultSearchType;
	global $currencyIcon;
	global $built;
	global $dir;
	global $languageArr;
	global $fWidth;
	global $colClass;

	$currencyIcon = array('EUR'=> '&euro;', 'GBP' => '&pound;', 'USD'=>'$');
	$height = ($fWidth/3)*2;
	$price = '';

	switch ($_SESSION["SearchType"]) {
		
		case "Resale" :
			if ( $resultPrice != '' ) {
				$price .= formatPrice($resultPrice, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
			}
			break;

		case "RentalLT" :
			if ( $resultPrice != '' ) {
				$price .= formatPrice($resultPrice, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
				$price .= ' /'.$languageArr['detail_Headings']['month'];
			}
			break;

		default :
			if ( $resultPrice != '' ) {
				$price .= formatPrice($resultPrice, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
			}

			if ( $resultPrice2 != '' && $resultPrice2 != $resultPrice ) {
				if ( $price != '' ) $price .= ' - ';
				$price .= formatPrice($resultPrice2, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
			}

			if ( $price != '' ) $price .= ' /'.$languageArr['detail_Headings']['week'];
	}

	$featureLink = createUrl($_SERVER['REQUEST_URI'], array($detailsPage, $urlRewrite), '/', '.htm');

	$searchResult = TemplateHelper::render('templates/featuredPropertyItem.html', array(
		'FeaturedItem.Link' => $featureLink,
		'FeaturedItem.TableWidth' => $fWidth,
		'FeaturedItem.ImageWidth' => $fWidth,
		'FeaturedItem.ImageHeight' => $height,
		'FeaturedItem.ImageSource' => $resultImage,
		'FeaturedItem.Title' => $resultLocation,
		'FeaturedItem.Type' => $resultType,
		'FeaturedItem.RefId' => $resultReference,
		'FeaturedItem.BedHeader' => $languageArr['field_Headings']['bedrooms']['short_Header'],
		'FeaturedItem.BedValue' => $resultBeds,
		'FeaturedItem.BathHeader' => $languageArr['field_Headings']['bathrooms']['short_Header'],
		'FeaturedItem.BathValue' => $resultBaths,
		'FeaturedItem.Price' => $price,
		'FeaturedItem.ColClass' => $colClass
	));

	return $searchResult;
}


function drawPropertyDetail() {

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
	global  $currencyIcon;
	global  $dir;
	global  $languageArr;
	global  $showMap;
	global  $bookingPage;
	global  $showBookingCalendar;
	global  $datePickerLanguages;
	global  $language;

	$currencyIcon = array('EUR'=> '&euro;', 'GBP' => '&pound;', 'USD'=>'$');
	$bookingDataJSon = '';
	$bookingLink = '';
	$showPlot = false;
	$showGarden = false;
	$searchLink = $dir;
	if ( $searchLink == '' ) $searchLink = '/';

	if ( ( $detailROLType == 'Plot' || $detailROLType == 'Commercial' ) && ( $detailPlot != '' ) && ( $detailPlot != '0' ) ) {
		$showPlot = true;
	} else {
		if ( ( $detailROLType == 'House' ) && ( $detailROLSubType != 'Terraced Townhouse' ) && ( $detailPlot != '' ) && ( $detailPlot != '0' ) ) {
			$showPlot = true;
		}
	}

	if ( !$showPlot ) {
		if ( ( $detailROLType == 'Apartment' ) && ( $detailROLSubType == 'Ground Floor' ) && ( $detailPlot != '' ) && ( $detailPlot != '0' ) ) {
			$showGarden = true;
		} else {
			if ( ( $detailROLType == 'House' ) && ( $detailROLSubType == 'Terraced Townhouse' ) && ( $detailPlot != '' ) && ( $detailPlot != '0' ) ) {
				$showGarden = true;
			}
		}
	}

	if ( isset($_SESSION["SearchType"]) && in_array($_SESSION["SearchType"], array('RentalLT', 'RentalST')) && $showBookingCalendar ) {
		$rag = createRangeDate();
		$bookingData = rentalBooking(array('start'=>$rag[0][0], 'end'=>$rag[sizeof($rag) - 1][1]));
		$bookingDataJSon = json_encode($bookingData);
		$bookingLink = createUrl($dir.'/', array($bookingPage), '', '')."?P_RefId=".getPropertyRef();
	}

	$price = '';

	switch ($_SESSION["SearchType"]) {
		
		case "RentalST" :
			if ( $detailPrice != '' ) {
				$price .= formatPrice($detailPrice, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
			}

			if ( $detailPrice2 != '' && $detailPrice2 != $detailPrice ) {
				if ( $price != '' ) $price .= ' - ';
				$price .= formatPrice($detailPrice2, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
			}

			if ( $price != '' ) $price .= ' /'.$languageArr['detail_Headings']['week'];
			break;

		case "RentalLT" :
			if ( $detailPrice != '' ) {
				$price .= formatPrice($detailPrice, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
				$price .= ' /'.$languageArr['detail_Headings']['month'];
			}
			break;

		default :
			if ( $detailPrice != '' ) {
				$price .= formatPrice($detailPrice, (isset($_SESSION["currency"]) ? $currencyIcon[$_SESSION["currency"]] : '&euro;'));
			}
	}

	$propertyDetail = TemplateHelper::render('templates/propertyDetail.html', array(
		'Property.Price' => $price,
		'Property.LocationRef' => ' - '.$detailReference,
		'Property.NewSearchUrl' => $searchLink,
		'Property.NewSearchText' => $languageArr['results_Headings']['new_Search'],
		'Property.LastResult.Link' => $_SERVER['HTTP_REFERER'],
		'Property.LastResult.Text' => $languageArr['detail_Headings']['last_Result'],
		'Property.HeaderText' => $detailHeaderText,
		'Property.PhotoText' => $languageArr['detail_Headings']['photos'],
		'Property.Map.Title' => $languageArr['detail_Headings']['map'],
		'Property.DetailImages' => $detailImage1,
		'Property.PhotoList' => $photoArray,
		'Property.DetailLocation' => $detailLocation,
		'Property.StartRentalDate' => (isset($_SESSION["startRentalDate"]) ? $_SESSION["startRentalDate"] : ''),
		'Property.EndRentalDate' => (isset($_SESSION["endRentalDate"]) ? $_SESSION["endRentalDate"] : ''),
		'Property.LanguageCode' => $datePickerLanguages[$language],
		'Property.Booking.Data' => $bookingDataJSon,
		'Property.Booking.Url' => $bookingLink,
		'Property.Booking.TextHover' => $languageArr['detail_Headings']['not_Available'],
		'Property.Booking.LinkText' => $languageArr['detail_Headings']['calendar'],
		'Property.Description' => preg_replace("/\r\n|\r|\n/",'<br />',$detailDescription),
		'Property.DetailText' => $languageArr['detail_Headings']['property_Details'],
		'Property.DetailBed' => $languageArr['field_Headings']['bedrooms']['header'].": ".$detailBeds,
		'Property.DetailBath' => $languageArr['field_Headings']['bathrooms']['header'].": ".$detailBaths,
		'Property.DetailPlot' => $languageArr['field_Headings']['plot_Size'].": ".formatArea($detailPlot,"m&sup2;"),
		'Property.DetailBuild' => $languageArr['field_Headings']['built_Size'].": ".formatArea($builds,"m&sup2;"),
		'Property.DetailTerrace' => $languageArr['field_Headings']['terrace_Size'].": ".formatArea($detailTerrace,"m&sup2;"),
		'Property.DetailGarden' => $languageArr['field_Headings']['garden'].": ".formatArea($detailPlot,"m&sup2;"),
		'Property.DetailEnergy' => $languageArr['field_Headings']['energyRating']['header'].": ".$detailEnergy,
		'Property.Features' => $featuresArray,
		),
		array(
			'showLastResultLink' => !isFeatureSearch(),
			'showMap' => $showMap,
			'showBookingCalendar' => isset($_SESSION["SearchType"]) && in_array($_SESSION["SearchType"], array('RentalLT', 'RentalST')) && $showBookingCalendar,
			'showPlot' => $showPlot,
			'showBuild' => $builds != '',
			'showTerrace' => $detailTerrace != '' && $detailTerrace != '0',
			'showGarden' => $showGarden,
			'showEnergy' => $detailEnergy != ''
		)
	);

	return $propertyDetail;
}
?>