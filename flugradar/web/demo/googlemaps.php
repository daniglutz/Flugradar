<!DOCTYPE html>
<html lang="en">
	
	<div id="map" style="width:100%; min-height:500px; opacity:0.8;"></div>

	<script>
		function myMap() {
			// origin positions
			var latStart = <?php echo $latOrigin ?>;
			var lngStart = <?php echo $lngOrigin ?>;
			
			// locations
			var locations = <?php echo json_encode($locations) ?>;
			
			// flightinfos
			var flightinfos = <?php echo json_encode($flightinfos) ?>;
			
			// markers and infowindows
			var markers = [];
			var infowindows = [];
			
			// generate map
			var map = new google.maps.Map(document.getElementById('map'), {
				mapTypeId: google.maps.MapTypeId.TERRAIN
			});
			
			var bounds = new google.maps.LatLngBounds();
			bounds.extend(new google.maps.LatLng(latStart, lngStart));
			
			var planeSymbol	= {
				path: 'M22.1,15.1c0,0-1.4-1.3-3-3l0-1.9c0-0.2-0.2-0.4-0.4-0.4l-0.5,0c-0.2,0-0.4,0.2-0.4,0.4l0,0.7c-0.5-0.5-1.1-1.1-1.6-1.6l0-1.5c0-0.2-0.2-0.4-0.4-0.4l-0.4,0c-0.2,0-0.4,0.2-0.4,0.4l0,0.3c-1-0.9-1.8-1.7-2-1.9c-0.3-0.2-0.5-0.3-0.6-0.4l-0.3-3.8c0-0.2-0.3-0.9-1.1-0.9c-0.8,0-1.1,0.8-1.1,0.9L9.7,6.1C9.5,6.2,9.4,6.3,9.2,6.4c-0.3,0.2-1,0.9-2,1.9l0-0.3c0-0.2-0.2-0.4-0.4-0.4l-0.4,0C6.2,7.5,6,7.7,6,7.9l0,1.5c-0.5,0.5-1.1,1-1.6,1.6l0-0.7c0-0.2-0.2-0.4-0.4-0.4l-0.5,0c-0.2,0-0.4,0.2-0.4,0.4l0,1.9c-1.7,1.6-3,3-3,3c0,0.1,0,1.2,0,1.2s0.2,0.4,0.5,0.4s4.6-4.4,7.8-4.7c0.7,0,1.1-0.1,1.4,0l0.3,5.8l-2.5,2.2c0,0-0.2,1.1,0,1.1c0.2,0.1,0.6,0,0.7-0.2c0.1-0.2,0.6-0.2,1.4-0.4c0.2,0,0.4-0.1,0.5-0.2c0.1,0.2,0.2,0.4,0.7,0.4c0.5,0,0.6-0.2,0.7-0.4c0.1,0.1,0.3,0.1,0.5,0.2c0.8,0.2,1.3,0.2,1.4,0.4c0.1,0.2,0.6,0.3,0.7,0.2c0.2-0.1,0-1.1,0-1.1l-2.5-2.2l0.3-5.7c0.3-0.3,0.7-0.1,1.6-0.1c3.3,0.3,7.6,4.7,7.8,4.7c0.3,0,0.5-0.4,0.5-0.4S22,15.3,22.1,15.1z',
				fillColor: '#000',
				fillOpacity: 1.5,
				scale: 1.5,
				anchor: new google.maps.Point(11, 20),
				strokeWeight: 0,			};
			
			for(var i = 0; i < locations.length; i++) {
				// add path
				var flightPlanCoordinates = [
					{lat: latStart, lng: lngStart},
					{lat: locations[i][1], lng: locations[i][2]}
				];
				
				// add line
				var flightPath = new google.maps.Polyline({
					path: flightPlanCoordinates,
					icons: [{icon: planeSymbol, offset: '100%'}],
					geodesic: true,
					strokeColor: '#FF0000',
					strokeOpacity: 0.8,
					strokeWeight: 5
				});
				
				// add link on line
				var id = locations[i][0];
				flightPath.addListener('click', function(id) {
					return function() {
						window.location.href = '?site=abfluege&airport=<?php echo $_GET['airport'] ?>&id=' + id;
					}
				}(id));
				
				// add infowindow
				infowindows[i] = new google.maps.InfoWindow({
					content: flightinfos[i][1]
				});
				markers[i] = new google.maps.Marker({
					position: {lat: locations[i][1], lng: locations[i][2]},
					map: map/*,
					icon: planeSymbol*/
				});
				markers[i].addListener('click', function(i) {
					return function() {
						infowindows[i].open(map, markers[i]);
					}
				}(i));
				
				bounds.extend(new google.maps.LatLng(locations[i][1], locations[i][2]));
				flightPath.setMap(map);
			}
			
			map.fitBounds(bounds);
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-3YQvHBxB9n0OquHJpp_z8LBi3t9iCzs&callback=myMap"></script>
	
</html>