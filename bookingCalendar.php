<?php
include 'webkitConfig.php';

$startDate = isset($_REQUEST["P_Start"]) ?$_REQUEST["P_Start"]:'';
$endDate = isset($_REQUEST["P_End"]) ?$_REQUEST["P_End"]:'';
$ref = isset($_REQUEST["P_RefId"]) ?$_REQUEST["P_RefId"]:'';

$bookingData = getRentalBooking($ref, $startDate, $endDate);
echo json_encode($bookingData);
?>