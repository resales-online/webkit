<?php
header('Cache-Control: no cache'); //no cache
session_cache_limiter('must-revalidate');
session_start();	


date_default_timezone_set("Europe/Madrid");


/* ================ Authorise Agency ===========================================*/

define("HashKey", "");
$contactId 		= '0';


/*================= Default Search Settings ====================================*/

$language		= 1;
$country		= 'Spain'; 
$defaultArea		= 'Costa del Sol';
$Areas  		= 'Costa del Sol, Alicante (Costa Blanca)';
$searchType		= 'All'; //  Resale,Rental,RentalST,RentalLT, All
$pOwn 			= 0; //0 = No (default), 1 = Yes
$pPreferred 		= 0; //0 = No (default), 1 = Yes

$resultsPage	= 'searchResults.php';
$detailsPage	= 'resultDetail.php';
$bookingPage    = 'bookingCalendar.php';

$removeLocations = array(); //array("La Línea", "La Alcaidesa");
$removeTypes    = array("Commercial"); //array("Apartment", "House", "Plot", "Commercial")


/*================= Default Featured Property Settings ====================================*/

$fLocation      =  '';
$fDefaultArea	= 'Costa del Sol';
$fPropertyType  = '2-1';
$fSearchType    = 'Resale';
$fBed           = '';
$fBath          = '';
$fSortType      = 0;
$fPNum          = 10;//Max properties  return default is 1
$fEnergyRating  = 3;
$fRow           = 1;
$fCol           = 3;
$fWidth         = 400;
$fShowFullWidth = true;


/*================= Fields to display Search Form ===============================*/

$showAreas		= true; //true = Show area option, false = Hide area option
$showSubtypes	= true; //true = Show Sub Type option, false = Hide sub type option
$showCurrency 	= true; //true = Show currency  option, false = Hide currency option
$showBeds 		= true; //true = Show bedroom option, false = Hide bedroom option
$showBaths 		= true; //true = Show bathroom option, false = Hide bathroom option
$showRating 	= true; //true = Show Energy Rating option, false = Hide Energy Rating option
$showChosen     = true; //true = show style chosen for multiple select, false = classic multiple select


/*=======================================================================*/


/*================= Detail Page Definition ===============================*/

$showMap		= false;
$showBookingCalendar  = true;


/*========== Automatically Update Language XML Files ======================*/

$auto_update_language = true;


/*================ URL REWRITE PATTERN ==============================================
					must include refid
*/
$rewritePatternUrl = 'refid-search_type-type-beds-baths-location-area'; 
//Setting params:  beds, baths, location, refid, area, search_type


/*=================================================================================*/


/*================ AUTO LINKS REWRITE PATTERN ==============================================
*/
$autoRewritePattern = 'location-area-type-subtype-priceMin-priceMax';
//Setting params:  location, area, type, subtype, priceMin, priceMax


/*=================================================================================*/


/*================ Features To Display On Results Page ============================*/

$showFeature = 'Setting, Condition, Features, Pool, Climate Control, Views'; 
//Setting params: Setting, Condition, Pool, Climate Control, Views, Features, Furniture, Kitchen, Garden, Security, Parking, Utilities, Category


/*=================================================================================*/


/*================= API URLS ====================================================*/

define("SearchLocationAPI", "http://webkit.resales-online.com/weblink/xml/V4-2/SearchLocationsXML.asp");
define("SearchPropertyTypeAPI", "http://webkit.resales-online.com/weblink/xml/V4-2/SearchPropertyTypesXML.asp");
define("SearchFeatureAPI", "http://webkit.resales-online.com/weblink/xml/V4-2/SearchFeaturesXML.asp");
define("SearchResaleAPI", "http://webkit.resales-online.com/weblink/xml/V4-2/SearchResaleXML.asp");
define("SearchRentalAPI", "http://webkit.resales-online.com/weblink/xml/V4-2/SearchRentalXML.asp");
define("PropertyDetailAPI", "http://webkit.resales-online.com/weblink/xml/V4-2/PropertyDetailsXML.asp");
define("SearchLanguageAPI", "http://webkit.resales-online.com/weblink/xml/v4-2/SearchLanguagesxml.asp");
define("RegisterLeadWebkitAPI", "http://webkit.resales-online.com/weblink/xml/V4-2/RegisterLeadWebkit.asp");
define("FeaturedPropertiesAPI", "http://webkit.resales-online.com/weblink/xml/V4-2/FeaturedPropertiesXML.asp");
define("BookingCalendarAPI", "http://webkit.resales-online.com/weblink/xml/V4-2/BookingCalendarXML.asp");

/*===============================================================================*/


/*================ Include File Locations ===================================*/

require_once 'inc/incVariableDefinitions.php';
require_once 'inc/incCommon.php';
require_once 'inc/incSearchFunction.php';
require_once 'inc/incSearchResults.php';
require_once 'inc/incPropertyDetail.php';
require_once 'inc/incWebkitTemplates.php';
require_once 'inc/incLanguage.php';
require_once 'inc/incFeatureFunction.php';
require_once 'inc/incBookingCalendar.php';


/*==========================================================================*/
?>