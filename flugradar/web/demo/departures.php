<?php
	
	/**
	* site departures
	* 
	* @name    departures.php
	* @author  Dario Kuster
	* @version 28.11.2016
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
                    $result = $db->query($sql);

                    // *** results? ***
                    if($result->num_rows)
                    {
                        echo "<select class='form-control' id='flughafen' required onchange=\"location.href = '?site=departures&airport=' + this.value;\">";

                            // ** loop results **
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
                    LIMIT 10";

                    // *** run query ***
                    $result = $db->query($sql);

                    // *** results? ***
                    if($result->num_rows)
                    {
                        // define object / arrays
                        $flickr = new Flickr();
                        $locations = array();
                        $flightinfos = array();

                        echo "<div class='list-group'>";

                            // ** loop results **
                            while($row = $result->fetch_assoc())
                            {
                                // set active class
                                $active = ($row['id'] == $_GET['id']) ? ' active' : '';

                                // search images of aircrafttype
                                $aircrafttype_images = $flickr->searchPhotos($row['aircrafttype'].',Plane', 1);

                                // * output *
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

                                // add location
                                if(isset($_GET['id'])) {
                                    // save result for details separate
                                    if($_GET['id'] == $row['id']) {
                                        $row_details = $row;
                                        $locations[$row['id']] = array(floatval($row['latitude']), floatval($row['longitude']));
                                    }
                                }
                                else {
                                    $locations[$row['id']] = array(floatval($row['latitude']), floatval($row['longitude']));
                                }

                                // add flightinfo
                                $flightinfo = "
                                ".$flickr->getPhotos($aircrafttype_images, 'q')."
                                <div style='float:left;'>
                                    <h5>".$row['airport_city']."</h5>
                                    Startzeit: ".$row['arrivaltime']." Uhr<br />
                                    Ankunftszeit: ".$row['departuretime']." Uhr<br />
                                    Flugzeugtyp: ".$row['aircrafttype']."<br />
                                    Geschwindigkeit: ".(($row['altitude'] == '') ? "-" : $row['speed']." km/h")."<br />
                                    Flughöhe: ".(($row['altitude'] == '') ? "-" : $row['altitude']." m")."<br />
                                    Position: ".$row['latitude'].", ".$row['longitude']."
                                </div>";
                                $flightinfos[$row['id']] = str_replace(array("\r\n", "\n", "\r"), ' ', $flightinfo);
                            }

                        echo "</div>";
                    }

                    echo "
                    <a href='?site=depart_refresh&airport=".$_GET['airport']."&howMany=10'>
                        <button type='submit' class='btn btn-default'>
                            <span class='glyphicon glyphicon-refresh' aria-hidden='true'></span> Daten aktualisieren
                        </button>
                    </a>
                </div>
            </div>
		</div>
		<div class='col-md-9'>";

            if($result->num_rows)
            {
                if(isset($_GET['id']) AND $_GET['id'] > 0)
                {
                    // define object
                    $flickr = new Flickr();

                    // edit city for flickr and search images of city
                    $city = substr($row_details['airport_city'], 0, strpos($row_details['airport_city'], ' '));
                    $city_images = $flickr->searchPhotos($city.',city,attractions', 5);


                    // get impressions
                    echo "
                    <div class='panel panel-default'>
                        <div class='panel-body'>
                            <h2>".$row_details['airport_city']."</h2>
                            <h4>".$row_details['airport_description']."</h4>
                            ".$flickr->getPhotos($city_images, 'q')."
                        </div>
                    </div>
                    <div class='clearfix'></div>";
                }

                // output map
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
            
        echo "
		</div>
	</div>";

?>