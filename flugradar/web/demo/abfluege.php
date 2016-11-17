<!DOCTYPE html>
<html lang="en">
	<div class="row">
		<div class="col-md-3">
			<form action="#" method="post">
				<div class="form-group">
					<label for="flughafen"><h4>Flughafen:</h4></label>
					<select class="form-control" id="flughafen" required="">
						<option>Zürich</option>
						<option>Basel</option>
						<option>Genf</option>
					</select>
				</div>
			</form>
			<div class="list-group">
				
				<?php
					/*require('FlightAware.class.php');
					
					
					$faClient = new FlightAware();*/
					//$departed = $faClient->Departed(array('airport' => 'LSZH', 'filter' => 'airline', 'howMany' => '5', 'offset' => '0'));
					$departed = json_decode(file_get_contents('test_api_flightaware/testdaten_Departed.txt'), TRUE);
					
					// Daten formatiert ausgeben
					foreach($departed["DepartedResult"]["departures"] as $arr_info)
					{
						//$flightinfo = $faClient->InFlightInfo(array('ident' => $arr_info["ident"));
						$flightinfo = json_decode(file_get_contents('test_api_flightaware/testdaten_InFlightInfo.txt'), TRUE);
						
						// Argumente für Link definieren
						$arg = "&latNow=".$flightinfo["InFlightInfoResult"]["highLatitude"]."&lngNow=".$flightinfo["InFlightInfoResult"]["highLongitude"];
						$arg .= "&city=".$arr_info["destinationCity"];
						
						echo "
						<a href='#/".$arg."' class='list-group-item'>
							<h4 class='list-group-item-heading'>".$arr_info["destinationCity"]."</h4>
							<p class='list-group-item-text'>
								Startzeit: ".date("H:i", $arr_info["actualdeparturetime"])." Uhr<br />
								Ankunftszeit: ".date("H:i", $arr_info["estimatedarrivaltime"])." Uhr<br />
								Flugzeugtyp: ".$arr_info["aircrafttype"]."<br />
								Geschwindigkeit: ".$flightinfo["InFlightInfoResult"]["groundspeed"]."<br />
								Flughöhe: ".$flightinfo["InFlightInfoResult"]["altitude"]."
							</p>
						</a>";
					}
				?>
				
			</div>
		</div>
		<div class="col-md-9">
			<h4>Details:</h4>
			<?php include 'googlemaps.php' ?>
			<?php /*include 'flickr.php'*/ ?>
		</div>
	</div>
</html>