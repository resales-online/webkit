<?php
function displayFeatureResults() {

	validateAuthorisationKeys();

	global $featureUrl;

	return outputFeatureResults(getFeatureResults(getFeatureUrl()));
}

function getFeatureUrl() {

	global $fBed;
	global $fBath;
	global $featureUrl;
	
	createFeaturedPropertiesAPI();

	$params = '';
	if ( $fBed != '' ) {
		$params .= '&P_Beds='.$fBed;
	}
	if ( $fBath != '' ) {
		$params .= '&P_Baths='.$fBath;
	}

	return $featureUrl.$params;
}

function getFeatureResults($apiString) {

	$data = object2array($apiString);
	if ( isset($data['Property']) ) {
		return $data['Property'];
	}
	return NULL;
}

function outputFeatureResults($propertyResults) {

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
	global $urlRewrite;
	global $searchResultType;
	global $rewritePatternUrl;
	global $queryInfo;
	global $built;
	global $languageArr;
	global $fRow;
	global $fCol;
	global $fDefaultArea;
	global $fShowFullWidth;
	global $colClass;
	
	$_SESSION['isFSearch'] = 1;
	$tmpText = '';
	$count = 0;

	$colClass = 'col-xs-12 col-sm-6 col-md-2';
	if ((12 / $fCol) > 0) $colClass = 'col-xs-12 col-sm-6 col-md-' . (12 / $fCol);

	if ( $propertyResults !== NULL ) {

		for ( $i=0; $i<$fRow; $i++ ) {

			$tmpText .= '<div class="row">';

			for( $j=0; $j<$fCol; $j++ ) {

				if ( $count < count($propertyResults) ) {
					$property = $propertyResults[$count];
					$count++;

					if ( $_SESSION["SearchType"] == 'Resale' ) {
						$resultPrice = $property['Price'];
						$resultPrice2 = '';
					} else {
						$resultPrice = $property['RentalPrice1'];
						$resultPrice2 = $property['RentalPrice2'];
					}

					$resultType = $property['Type'];
					$resultImage = $property['MainImage'];
					$resultLocation = $property['Location'];
					$resultReference = $property['Reference'];
					$resultDescription = $property['Description'];
					$resultBeds = $property['Bedrooms'];
					$resultBaths = $property['Bathrooms'];
					$resultTerrace = $property['Terrace'];
					$built = $property['Built'];
							
					//Rewrite url with pattern config from webkitConfig
					//option: beds, baths, type, location, refid, area, search_type, subtype
					$sTypes = array('Resale'=>$languageArr['search_Types']['for_Sale'], 'RentalLT'=>$languageArr['search_Types']['longTerm_Rent'],'RentalST'=>$languageArr['search_Types']['shortTerm_Rent']);
					$sType = $sTypes['Resale'];
					if ( isset($_SESSION["SearchType"]) ) {
						$sType= $_SESSION["SearchType"];
					}
					$pType = 'All';
					if ( isset($_SESSION["Type"]) ) {
						$pType = $_SESSION["Type"];
					}
					$arrayTmp = array('beds'=>$resultBeds, 'baths'=>$resultBaths, 'type'=> $pType, 'location'=>$resultLocation, 'refid'=>$resultReference, 'search_type'=>$sType, 'area'=>$fDefaultArea, 'subtype'=>'All');
					$data = array();
					$patterns = explode("-", $rewritePatternUrl);
					foreach($patterns as $item) {
						if ( $item == 'type' ) {
							if($arrayTmp[$item] != '') {
							   $data[] = str_replace('-',' ',$arrayTmp[$item]);	
							}
						} else {
							if ( $arrayTmp[$item] != '' && !in_array($item, array('refid', 'location', 'type', 'search_type', 'area', 'subtype')) ) {
								$data[] = $arrayTmp[$item].$item;
							} else {
								$data[] = $arrayTmp[$item];
							}
						}
					}
					$data[] = 'Feature';
					$urlRewrite = implode('-',$data);
					$align = '';
					$tmpText .= featureResult();
				} else {
					$tmpText .= '<div>&nbsp;</div>';
				}
				
			}
			$tmpText .= '</div>';
			
		}
		
	} else {
	    $tmpText .= "<div class='row'><div class='col-md-12'>".$languageArr['results_Headings']['no_Result'].'</div></div>';
	}
	
	$resultText = TemplateHelper::render('templates/featuredProperty.html', array(
    	'Feature.TableWidth' => ($fShowFullWidth ? 'width="100%"' : ''),
		'Feature.ListItem' => $tmpText
	));

	echo $resultText;
}