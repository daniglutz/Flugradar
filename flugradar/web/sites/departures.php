<?php
	
	/**
	* site departures
	* 
	* @name    sites/departures.php
	* @author  Dario Kuster
	* @version 06.01.2017
	*/
    
    
    echo "
	<div class='row'>
		<div class='col-md-3'>
            <div class='panel panel-default'>
                <div class='panel-body'>
                    <label for='flughafen'><h4>Abflug-Flughafen:</h4></label>";

                    // *** define query ***
                    $sql = "
                    SELECT
                        `icao_code`,
                        `description`,
                        `latitude`,
                        `longitude`
                    FROM `airports`
                    WHERE `latitude` IS NOT NULL AND `longitude` IS NOT NULL
                    ORDER BY `description`";

                    // *** run query ***
                    $resultA = $db->query($sql);

                    // *** results? ***
                    if($resultA->num_rows) {
                        echo "<select class='form-control' id='flughafen' required onchange=\"location.href = '?site=departures&airport=' + this.value;\">";

                            // ** loop results **
                            while($row = $resultA->fetch_assoc()) {
                                // * output dropdown for airports *
                                if($_GET['airport'] == $row['icao_code']) {
                                    echo "<option value='".$row['icao_code']."' selected>".$row['description']."</option>";
                                    $locationOrigin = array(floatval($row['latitude']), floatval($row['longitude']));
                                } else {
                                    echo "<option value='".$row['icao_code']."'>".$row['description']."</option>";
                                }
                            }

                        echo "</select>";
                    }


                    echo "<h4>Flüge:</h4>";

                    // *** define query ***
                    $sql = "
                    SELECT
                        `last_departures`.`id`,
                        `last_departures`.`flight_ident`,
                        `last_departures`.`origin`,
                        `last_departures`.`destination`,
                        `airports`.`city` AS `airport_city`,
                        `airports`.`description` AS `airport_description`,
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
                    LIMIT ".$_SESSION['numberEntries'];

                    // *** run query ***
                    $resultD = $db->query($sql);

                    // *** results? ***
                    if($resultD->num_rows) {
                        // define object / arrays
                        $flickr = new Flickr();
                        $locations = array();
                        $flightinfos = array();

                        echo "<div class='list-group'>";

                            // ** loop results **
                            while($row = $resultD->fetch_assoc()) {
                                // set active class
                                $active = (isset($_GET['id']) AND $_GET['id'] == $row['id']) ? ' active' : '';

                                // search images of aircrafttype
                                $aircrafttypeImages = $flickr->searchPhotos($row['aircrafttype'].',Plane', 1);

                                // * output departures *
                                echo "
                                <a href='?site=departures&airport=".$_GET['airport']."&id=".$row['id']."' class='list-group-item".$active."'>
                                    <h4 class='list-group-item-heading'>
                                        ".$row["airport_city"]."
                                    </h4>
                                    <p class='list-group-item-text'>
                                        <span class='glyphicon glyphicon-export' aria-hidden='true'></span> Startzeit: ".$row["departuretime"]." Uhr<br />
                                        <span class='glyphicon glyphicon-import' aria-hidden='true'></span> Ankunftszeit: ".$row["arrivaltime"]." Uhr<br />
                                    </p>
                                </a>";

                                // add location/s for map
                                if(isset($_GET['id'])) {
                                    // save result for details separate
                                    if($_GET['id'] == $row['id']) {
                                        $rowDetails = $row;
                                        $locations[$row['id']] = array(floatval($row['latitude']), floatval($row['longitude']));
                                    }
                                }
                                else {
                                    $locations[$row['id']] = array(floatval($row['latitude']), floatval($row['longitude']));
                                }

                                // add flightinfo for map
                                $flightinfo = "
                                ".$flickr->getPhotos($aircrafttypeImages, 'q')."
                                <div style='float:left;'>
                                    <h5>".$row['airport_city']."</h5>
                                    Startzeit: ".$row['arrivaltime']." Uhr<br />
                                    Ankunftszeit: ".$row['departuretime']." Uhr<br />
                                    Flugzeugtyp: ".$row['aircrafttype']."<br />
                                    Geschwindigkeit: ".(($row['altitude'] == '') ? "-" : $row['speed']." km/h")."<br />
                                    Flughöhe: ".(($row['altitude'] == '') ? "-" : $row['altitude']." m")."<br />
                                    Position: ".$row['latitude'].", ".$row['longitude']."<br />
                                    <a href='?site=departures&airport=".$_GET['airport']."&id=".$row['id']."'>›› Details</a>
                                </div>";
                                $flightinfos[$row['id']] = str_replace(array("\r\n", "\n", "\r"), ' ', $flightinfo);
                            }

                        echo "</div>";
                    }

                    echo "
                    <a href='?site=depart_refresh&airport=".$_GET['airport']."&howMany=".$_SESSION['numberEntries']."'>
                        <button type='submit' class='btn btn-default'>
                            <span class='glyphicon glyphicon-refresh' aria-hidden='true'></span> Daten aktualisieren
                        </button>
                    </a>
                </div>
            </div>
		</div>
		<div class='col-md-9'>";

            if($resultD->num_rows) {
                if(isset($_GET['id']) AND $_GET['id'] > 0) {
                    // define object
                    $flickr = new Flickr();

                    // edit city for flickr and search images of city
                    $city = substr($rowDetails['airport_city'], 0, strpos($rowDetails['airport_city'], ' '));
                    $cityImages = $flickr->searchPhotos($city.',city,attractions', 5);


                    // * output impressions *
                    echo "
                    <div class='panel panel-default'>
                        <div class='panel-body'>
                            <h2>".$rowDetails['airport_city']."</h2>
                            <h4>".$rowDetails['airport_description']."</h4>
                            ".$flickr->getPhotos($cityImages, 'q')."
                        </div>
                    </div>
                    <div class='clearfix'></div>";
                }
                
                // * output map *
                include 'googlemaps.php';
            }
            else {
                // * output message *
                echo getMessage("Hinweis:", "Keine Abflüge gefunden", "Bitte betätigen sie den Knopf 'Daten aktualisieren'");
            }
            
        echo "
		</div>
	</div>";

?>