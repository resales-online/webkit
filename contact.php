<?php
include 'webkitConfig.php';

set_time_limit(0);

$_SESSION["M1"] = $_REQUEST["M1"];
$_SESSION["M2"] = $_REQUEST["M2"];
$_SESSION["M5"] = $_REQUEST["M5"];

$params = array(
        'p1' => $_REQUEST["p1"],
		'p2' => $_REQUEST["p2"],
		'M1' => $_REQUEST["M1"],
		'M2' => $_REQUEST["M2"],
		'M5' => $_REQUEST["M5"],
		'M6' => $_REQUEST["M6"],
		'M7' => $_REQUEST["M7"],
		'RsId' => $_REQUEST["RsId"],
		'W1' => $_REQUEST["W1"],
		'W2' => $_REQUEST["W2"],
		'W3' => $_REQUEST["W3"],
		'W4' => $_REQUEST["W4"],
		'W6' => $_REQUEST["W6"],
		'W7' => $_REQUEST["W7"],
		'W10' => $_REQUEST["W10"],
		'Lang' => $_REQUEST["Lang"]
    );

$res = curl_get(RegisterLeadWebkitAPI, $params);

echo $res;

function curl_get($url, array $get = NULL, array $options = array()) 
{    
    $defaults = array( 
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
        CURLOPT_HEADER => 0, 
        CURLOPT_RETURNTRANSFER => TRUE, 
        CURLOPT_TIMEOUT => 500 
    ); 
    
    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if ( ! $result = curl_exec($ch)) { 
        trigger_error(curl_error($ch)); 
    } 
    curl_close($ch); 

    return $result; 
}  
?>