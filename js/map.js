var geocoder;
var map;

function initialize() {

	geocoder = new google.maps.Geocoder();
	var mapOptions = {
		zoom: 15
	}
	map = new google.maps.Map(document.getElementById("mapHolder"), mapOptions);
}

function fire() {

	var address = document.getElementById("mapLocation").value + ', ' + document.getElementById("W2").value + ', ' + document.getElementById("W1").value;

	geocoder.geocode( { 'address': address }, function(results, status) {
		if ( status == google.maps.GeocoderStatus.OK ) {
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
			map: map,
			position: results[0].geometry.location
			});
	
			if ( typeof(map) != 'undefined' ) {
				var center = map.getCenter();
				google.maps.event.trigger(map, "resize");
				map.setCenter(center);
			}
		} else {
			alert("Geocode was not successful for the following reason: " + status);
		}
	});
}