<!DOCTYPE html>
<html lang="en">
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label for="flughafen"><h4>Abflug-Flughafen:</h4></label>
				
				<?php
				
					// *** Abfrage definieren ***
					$sql = "
					SELECT
						`icao_code`,
						`description`,
						`latitude`,
						`longitude`
					FROM `airports`
					WHERE `latitude` IS NOT NULL AND `longitude` IS NOT NULL
					ORDER BY `description`";
					
					// *** Abfrage ausführen ***
					$result = $db->query($sql);
					
					// *** ergebnis? ***
					if($result->num_rows)
					{
						echo "<select class='form-control' id='flughafen' required onchange=\"location.href = '?site=abfluege&airport=' + this.value;\">";
						
							// ** ergebnis-datensätze durchlaufen **
							while($row = $result->fetch_assoc())
							{
								if($_GET['airport'] == $row['icao_code'])
								{
									echo "<option value='".$row['icao_code']."' selected>".$row['description']."</option>";
									$latOrigin = $row['latitude'];
									$lngOrigin = $row['longitude'];
								}
								else
								{
									echo "<option value='".$row['icao_code']."'>".$row['description']."</option>";
								}
							}
						
						echo "</select>";
					}
				
				?>
				
			</div>
			
			<?php
				echo "<h4>Flüge:</h4>";
				
				// *** Abfrage definieren ***
				$sql = "
				SELECT
					`last_departures`.`id`,
					`last_departures`.`flight_ident`,
					`last_departures`.`origin`,
					`last_departures`.`destination`,
					`airports`.`city` AS `destination_city`,
					DATE_FORMAT(`last_departures`.`arrivaltime`, '%H:%i') AS `arrivaltime`,
					DATE_FORMAT(`last_departures`.`departuretime`, '%H:%i') AS `departuretime`,
					`last_departures`.`aircrafttype`,
					`last_departures`.`speed`,
					`last_departures`.`altitude`,
					`last_departures`.`latitude`,
					`last_departures`.`longitude`
				FROM `last_departures`
					LEFT JOIN `airports`
						ON `last_departures`.`destination` = `airports`.`icao_code`
				WHERE `last_departures`.`origin` = '".$_GET['airport']."'
				ORDER BY `last_departures`.`id` DESC
				LIMIT 10";
				
				// *** Abfrage ausführen ***
				$result = $db->query($sql);
				
				// *** ergebnis? ***
				if($result->num_rows)
				{
					// Variablen definieren
					$flickr = new Flickr();
					$locations = array();
					$flightinfos = array();
					
					echo "<div class='list-group'>";
						
						// ** ergebnis-datensätze durchlaufen **
						while($row = $result->fetch_assoc())
						{
							// * ausgeben *
							echo "
							<a href='?site=abfluege&airport=".$_GET['airport']."&id=".$row['id']."' class='list-group-item'>
								<h4 class='list-group-item-heading'>".$row["destination_city"]."</h4>
								<p class='list-group-item-text'>
									Startzeit: ".$row["departuretime"]." Uhr<br />
									Ankunftszeit: ".$row["arrivaltime"]." Uhr<br />
								</p>
							</a>";
							
							// add locations
							array_push(
								$locations,
								array(intval($row['id']), floatval($row['latitude']), floatval($row['longitude']))
							);
							
							// add flightinfo
							$images = $flickr->searchPhotos($row['aircrafttype'].',Plane', 1);
							$flightinfo = "
							<div class='text-nowrap'>
								".$flickr->getPhotos($images, 'q')."
								Startzeit: ".$row['arrivaltime']." Uhr<br />
								Ankunftszeit: ".$row['departuretime']." Uhr<br />
								Flugzeugtyp: ".$row['aircrafttype']."<br />
								Geschwindigkeit: ".(($row['altitude'] == '') ? "-" : $row['speed']." km/h")."<br />
								Flughöhe: ".(($row['altitude'] == '') ? "-" : $row['altitude']." m")."<br />
								Position: ".$row['latitude'].", ".$row['longitude']."
							</div>";
							
							array_push(
								$flightinfos,
								array(intval($row['id']), str_replace(array("\r\n", "\n", "\r"), ' ', $flightinfo))
							);
							
							
							if(isset($_GET['id']) AND $_GET['id'] == $row['id'])
							{
								$row_sel = $row;
							}
						}
					
					echo "</div>";
				}
				
				echo "<button type='submit' class='btn btn-default'>Daten aktualisieren</button>";
			?>
			
		</div>
		<div class="col-md-9">
			
			<?php
				if($result->num_rows)
				{
					if(isset($_GET['id']) AND $_GET['id'] > 0)
					{
						// Variablen definieren
						$flickr = new Flickr();
						$locations = array();
						$flightinfos = array();
						
						
						// get impressions
						echo "
						<h2>".$row_sel['destination_city']."</h2>
						<h4>Eindrücke</h4>";
						$images = $flickr->searchPhotos($row_sel['destination_city'].',city,attractions', 5);
						echo $flickr->getPhotos($images, 'q');
						echo "<div class='clearfix'></div><br />";
						
						// add locations
						array_push(
							$locations,
							array(intval($row_sel['id']), floatval($row_sel['latitude']), floatval($row_sel['longitude']))
						);
						
						// add flightinfo
						$images = $flickr->searchPhotos($row_sel['aircrafttype'].',Plane', 1);
						$flightinfo = "
						<div class='text-nowrap'>
							".$flickr->getPhotos($images, 'q')."
							Startzeit: ".$row_sel['arrivaltime']." Uhr<br />
							Ankunftszeit: ".$row_sel['departuretime']." Uhr<br />
							Flugzeugtyp: ".$row_sel['aircrafttype']."<br />
							Geschwindigkeit: ".(($row_sel['altitude'] == '') ? "-" : $row_sel['speed']." km/h")."<br />
							Flughöhe: ".(($row_sel['altitude'] == '') ? "-" : $row_sel['altitude']." m")."<br />
							Position: ".$row_sel['latitude'].", ".$row_sel['longitude']."
						</div>";
						
						array_push(
							$flightinfos,
							array(intval($row_sel['id']), str_replace(array("\r\n", "\n", "\r"), ' ', $flightinfo))
						);
					}
					
					echo "<h4>Karte:</h4>";
					include 'googlemaps.php';
				}
				else
				{
					echo "
					<div class='alert alert-danger' role='alert'>
						<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
						<span class='sr-only'>Hinweis:</span>
						<b>Keine Abflüge gefunden</b><br />
						Bitte betätigen sie den Knop 'Daten aktualisieren'
					</div>";
				}
				
			?>
			
		</div>
	</div>
</html>