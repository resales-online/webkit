<?php

$tmpDir = explode('/', dirname($_SERVER['PHP_SELF']));

if ( strpos($tmpDir[1],'.php') === false ) {
	$dir = '/'.$tmpDir[1];
} else {
	$dir = '';
}

//Config variables
//Initiate Result Variables
$resultType = '';
$resultImage = '';
$resultPrice = '';
$resultPrice2 = '';
$resultLocation = '';
$resultReference = '';
$resultDescription = '';
$resultBeds = '';
$resultBaths = '';
$resultTerrace = '';
$resultPlot = '';
$resultSearchType = '';
$resultTypeVal = '';

//Initiate property detail Variable
$detailHeaderText = '';
$detailImage1 = '';
$detailReference = '';
$detailPrice = '';
$detailPrice2 = '';
$detailLocation = '';
$detailType = '';
$detailROLType = '';
$detailROLSubType = '';
$detailBeds = '';
$detailBaths = '';
$detailPlot = '';
$detailArea = '';
$detailTerrace = '';
$detailDescription = '';
$resultOutput = '';
$photoArray;
$featuresArray;

$pageNumber;
$queryId;
$propertyCount;
$pageCount;
$currentArea;

$typesXML;
$typesJSON;
$locationsXML;
$locationsJSON;
$languageArr;
$featureSearchArr;
?>