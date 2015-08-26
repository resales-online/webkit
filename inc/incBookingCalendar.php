<?php
function getDateRangeFrom($date) {
	date_default_timezone_set("Europe/Madrid");
	
	$d = new DateTime($date);
    $d->modify('first day of last month');
	$lastMonthPeriod = array();
	$lastMonthPeriod[] = $d->format('Y-m-d');
	$d->modify('last day of this month');
	$lastMonthPeriod[] = $d->format('Y-m-d');
	
	$thisMonthPeriod = array();
	$d = new DateTime($date);
    $d->modify('first day of this month');
	$thisMonthPeriod[] = $d->format('Y-m-d');
	$d->modify('last day of this month');
    $thisMonthPeriod[] = $d->format('Y-m-d');
	
	$next1MonthPeriod = array();
	$d = new DateTime($date);
    $d->modify('first day of next month');
	$next1MonthPeriod[] = $d->format('Y-m-d');
	$d->modify('last day of this month');
	$next1MonthPeriod[] = $d->format('Y-m-d');
	
	$next2MonthPeriod = array();
	$d->modify('first day of next month');
	$next2MonthPeriod[] = $d->format('Y-m-d');
	$d->modify('last day of this month');
	$next2MonthPeriod[] = $d->format('Y-m-d');
	
	$result[] = $lastMonthPeriod;
	$result[] = $thisMonthPeriod;
	$result[] = $next1MonthPeriod;
	$result[] = $next2MonthPeriod;
	return $result;
}

function getRentalBooking($ref, $start, $end) {
	global $bookingCalendarUrl;
	
	createBookingCalendarAPI();
	$newUrl = $bookingCalendarUrl.'&P_RefId='.$ref.'&P_Start='.$start.'&P_End='.$end;
	$data = object2array($newUrl);
	$rangeArr = array();
	
	if(isset($data['Ranges']['Range'])) {
		$rangeArr = $data['Ranges']['Range'];
		if(!isset($rangeArr[0])) {
			$rangeArr = array(array_values($rangeArr));
		} else {
			foreach ($rangeArr as $k => $v) {
				$v = array_values($v);
				$rangeArr[$k] = $v;
			}
		}
	}
	return $rangeArr;
}

function getBookingUrlAPI() {
	global $bookingCalendarUrl;
	return $bookingCalendarUrl.'&P_RefId='.getPropertyRef();
}

function rentalBooking($ranges) {
	return getRentalBooking(getPropertyRef(), $ranges['start'], $ranges['end']);
}

function createRangeDate() {
	$ranges = array();
	if(isset($_SESSION["startRentalDate"])) {
		$ranges = getDateRangeFrom($_SESSION["startRentalDate"]);
	} else {
		$ranges = getDateRangeFrom(date('Y-m-d'));
	}
	return $ranges;
}

?>