<!DOCTYPE html>
<html lang="en">
	<div class="row">
		<div class="col-md-3">
			<form action="#" method="post">
				<div class="form-group">
					<label for="flughafen"><h4>Abflug-Flughafen:</h4></label>
					<select class="form-control" id="flughafen" required="">
						<option>Zürich</option>
						<option>Basel</option>
						<option>Genf</option>
					</select>
				</div>
			</form>
			<h4>Flüge:</h4>
			<div class="list-group">
				
				<?php
					/*require('classes/FlightAware.class.php');
					
					
					$faClient = new FlightAware();*/
					//$departed = $faClient->Departed(array('airport' => 'LSZH', 'filter' => 'airline', 'howMany' => '10', 'offset' => '0'));
					$departed = json_decode(file_get_contents('testdaten/testdaten_Departed.txt'), TRUE);
					
					$locations = array();
					
					// Daten formatiert ausgeben
					foreach($departed["DepartedResult"]["departures"] as $arr_info)
					{
						//$flightinfo = $faClient->InFlightInfo(array('ident' => $arr_info["ident"));
						$flightinfo = json_decode(file_get_contents('testdaten/testdaten_InFlightInfo_'.$arr_info["ident"].'.txt'), TRUE);
						
						echo "
						<a href='?site=abfluege&id=5' class='list-group-item'>
							<h4 class='list-group-item-heading'>".$arr_info["destinationCity"]."</h4>
							<p class='list-group-item-text'>
								Startzeit: ".date("H:i", $arr_info["actualdeparturetime"])." Uhr<br />
								Ankunftszeit: ".date("H:i", $arr_info["estimatedarrivaltime"])." Uhr<br />
								Flugzeugtyp: ".$arr_info["aircrafttype"]."<br />
								Geschwindigkeit: ".$flightinfo["InFlightInfoResult"]["groundspeed"]."<br />
								Flughöhe: ".$flightinfo["InFlightInfoResult"]["altitude"]."
							</p>
						</a>";
						
						// add locations
						array_push($locations, array($flightinfo["InFlightInfoResult"]["latitude"], $flightinfo["InFlightInfoResult"]["longitude"]));
					}
				?>
				
			</div>
		</div>
		<div class="col-md-9">
			<h4>Details:</h4>
			
			<?php
				if($_GET['id'] > 0)
				{
					// MYSQL-ABFRAGE
					$locations = array(
						array(40.774930, 205.419416)
					);
					
					echo "
					<div class='row'>
						<div class='col-sm-4'>
							<h5>Flugzeugtyp: ".$arr_info["aircrafttype"]."</h5>
						</div>
						<div class='col-sm-4'>
							<h5>Geschwindigkeit: ".$flightinfo["InFlightInfoResult"]["groundspeed"]."</h5>
						</div>
						<div class='col-sm-4'>
							<h5>Flughöhe: ".$flightinfo["InFlightInfoResult"]["altitude"]."</h5>
						</div>
					</div>";
				}
				
				include 'googlemaps.php';
				include 'flickr.php';
			?>
			
		</div>
	</div>
</html>