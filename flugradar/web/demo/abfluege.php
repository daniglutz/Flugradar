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
					<button type="submit" class="btn btn-default">Daten aktualisieren</button>
				</div>
			</form>
			<h4>Flüge:</h4>
			<div class="list-group">
				
				<?php
					// *** Abfrage definieren ***
					$sql = "
					SELECT
						`id`,
						`flight_ident`,
						`origin`,
						`destination`,
						DATE_FORMAT(`arrivaltime`, '%H:%i') AS `arrivaltime`,
						DATE_FORMAT(`departuretime`, '%H:%i') AS `departuretime`,
						`aircrafttype`,
						`speed`,
						`altitude`,
						`latitude`,
						`longitude`
					FROM `last_departures`
					WHERE `origin` = 'LSZH'
					ORDER BY `id` DESC
					LIMIT 10";
					
					// *** Abfrage ausführen ***
					$result = $db->query($sql);
					
					// *** ergebnis? ***
					if($result->num_rows)
					{
						$locations = array();
						$cities = "";
						
						// ** ergebnis-datensätze durchlaufen **
						while($row = $result->fetch_assoc())
						{
							// * ausgeben *
							echo "
							<a href='?site=abfluege&id=".$row['id']."' class='list-group-item'>
								<h4 class='list-group-item-heading'>".$row["destination"]."</h4>
								<p class='list-group-item-text'>
									Startzeit: ".$row["arrivaltime"]." Uhr<br />
									Ankunftszeit: ".$row["departuretime"]." Uhr<br />
								</p>
							</a>";
							
							// add locations
							array_push(
								$locations,
								array(intval($row['id']), floatval($row['latitude']), floatval($row['longitude']))
							);
							
							// add city
							//$cities .= $row['destination'].",";
							$cities .= "Amsterdam,";
						}
					}
				?>
				
			</div>
		</div>
		<div class="col-md-9">
			
			<?php
				if($_GET['id'] > 0)
				{
					// *** Abfrage definieren ***
					$sql = "
					SELECT
						`id`,
						`flight_ident`,
						`origin`,
						`destination`,
						DATE_FORMAT(`arrivaltime`, '%H:%i') AS `arrivaltime`,
						DATE_FORMAT(`departuretime`, '%H:%i') AS `departuretime`,
						`aircrafttype`,
						`speed`,
						`altitude`,
						`latitude`,
						`longitude`
					FROM `last_departures`
					WHERE `id` = ".$_GET['id']."
					LIMIT 1";
					
					// *** Abfrage ausführen ***
					$result = $db->query($sql);
					
					// *** ergebnis? ***
					if($result->num_rows)
					{
						// Ergebnis speichern
						$row = $result->fetch_assoc();
						
						// Variablen definieren
						$locations = array();
						$cities = "";
						
						
						echo "<h2>".$row['destination']."</h2>";

						$tag = $row['aircrafttype'].',Plane';
						$perPage = 1;
						include 'flickr.php';
						
						echo "
						<h5>Startzeit: ".$row['arrivaltime']." Uhr</h5>
						<h5>Ankunftszeit: ".$row['departuretime']." Uhr</h5>
						<h5>Flugzeugtyp: ".$row['aircrafttype']."</h5>
						<h5>Geschwindigkeit: ".$row['speed']." km/h</h5>
						<h5>Flughöhe: ".$row['altitude']." m</h5>
						<h5>Position: ".$row['latitude'].", ".$row['longitude']."</h5>
						<br />";
						
						// add locations
						array_push(
							$locations,
							array(intval($row['id']), floatval($row['latitude']), floatval($row['longitude']))
						);
						
						// add city
						//$cities = $row['destination'].",";
						$cities = "Amsterdam,";
					}
				}
				
				echo "<h4>Karte:</h4>";
				include 'googlemaps.php';
				
				$tag = $cities.',City,landscape';
				$perPage = 5;
				echo "<br /><h4>Eindrücke:</h4>";
				include 'flickr.php';
			?>
			
		</div>
	</div>
</html>