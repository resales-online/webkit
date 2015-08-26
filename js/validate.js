function validateContactForm() {

	var checkValue ;
	
	checkValue = document.getElementById("M5").value ;

	if ( ! validateNotEmpty(checkValue) ) {
		alert('Please make sure you complete all the fields');
		return false;
	}
	else
	{
		if ( ! validateEmail(checkValue) ) {
			alert('This does not seem to be a valid email address');
			return false;
		}
	}

	checkValue = document.getElementById("M1").value ;

	if ( ! validateNotEmpty(checkValue) ) {
		alert('Please make sure you complete all the fields');
		return false;
	}

	checkValue = document.getElementById("M2").value ;

	if ( ! validateNotEmpty(checkValue) ) {
		alert('Please make sure you complete all the fields');
		return false;
	}

	checkValue = document.getElementById("M7").value ;

	if ( ! validateNotEmpty(checkValue) ) {
		alert('Please make sure you complete all the fields');
		return false;
	}
	
	return true;
}


function validateEmail( strValue ) {
	
	var objRegExp = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return objRegExp.test(strValue);
}

function validateNotEmpty( strValue ) {

	var strTemp = strValue;
	strTemp = $.trim(strTemp);
	if ( strTemp.length > 0 ) {
		return true;
	}
	return false;
}