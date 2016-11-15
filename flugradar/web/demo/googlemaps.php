<!DOCTYPE html>
<html lang="en">
	
	<div id="map" style="width:100%; height:500px; opacity:0.8;"></div>

	<script>
		function myMap() {
			var latStart = 47.458304; // ZH
			var lngStart = 8.554510; // ZH
			var latNow = 46.67435;
			var lngNow = 8.72811;
			
			var map = new google.maps.Map(document.getElementById('map'), {
				mapTypeId: google.maps.MapTypeId.TERRAIN
			});

			var flightPlanCoordinates = [
				{lat: latStart, lng: lngStart},
				{lat: latNow, lng: lngNow}
			];
			
			var lineSymbol = {
				path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
			};
			
			var flightPath = new google.maps.Polyline({
				path: flightPlanCoordinates,
				icons: [{icon: lineSymbol, offset: '100%'}],
				geodesic: true,
				strokeColor: '#FF0000',
				strokeOpacity: 0.8,
				strokeWeight: 5
			});
			
			var bounds = new google.maps.LatLngBounds();
			bounds.extend(new google.maps.LatLng(latStart, lngStart));
			bounds.extend(new google.maps.LatLng(latNow, lngNow));
			map.fitBounds(bounds);
			map.panToBounds(bounds);
			
			flightPath.setMap(map);
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-3YQvHBxB9n0OquHJpp_z8LBi3t9iCzs&callback=myMap"></script>
	
</html>