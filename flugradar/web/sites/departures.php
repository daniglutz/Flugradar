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

                    // *** get aiports ***
                    $airports = $db->getAirports();

                    // *** results? ***
                    if(isset($airports)) {
                        echo "<select class='form-control' id='flughafen' required onchange=\"location.href = '?site=departures&airport=' + this.value;\">";

                            // ** loop airports **
                            foreach ($airports as $airport) {
                                // * output dropdown *
                                if($_GET['airport'] == $airport->getIcaoCode()) {
                                    echo "<option value='".$airport->getIcaoCode()."' selected>".$airport->getDescription()."</option>";
                                    $locationOrigin = array(floatval($airport->getLatitude()), floatval($airport->getLongitude()));
                                } else {
                                    echo "<option value='".$airport->getIcaoCode()."'>".$airport->getDescription()."</option>";
                                }
                            }

                        echo "</select>";
                    }


                    echo "<h4>Flüge:</h4>";

                    // *** get departures ***
                    $departures = $db->getDepartures($_GET['airport'], $_SESSION['numberEntries']);

                    // *** results? ***
                    if(isset($departures)) {
                        // define arrays
                        $locations = array();
                        $flightinfos = array();

                        echo "<div class='list-group'>";

                            // ** loop departures **
                            foreach ($departures as $departure) {
                                // set active class
                                $active = (isset($_GET['id']) AND $_GET['id'] == $departure->getId()) ? ' active' : '';

                                // search images of aircrafttype
                                $aircrafttypeImages = $flickr->searchPhotos($departure->getAircrafttype().',Plane', 1);

                                // * output departures *
                                echo "
                                <a href='?site=departures&airport=".$_GET['airport']."&id=".$departure->getId()."' class='list-group-item".$active."'>
                                    <h4 class='list-group-item-heading'>
                                        ".$departure->getAirportCity()."
                                    </h4>
                                    <p class='list-group-item-text'>
                                        <span class='glyphicon glyphicon-export' aria-hidden='true'></span> Startzeit: ".$departure->getDeparturetime()." Uhr<br />
                                        <span class='glyphicon glyphicon-import' aria-hidden='true'></span> Ankunftszeit: ".$departure->getArrivaltime()." Uhr<br />
                                    </p>
                                </a>";

                                // add location/s for map
                                if(isset($_GET['id'])) {
                                    // save result for details separate
                                    if($_GET['id'] == $departure->getId()) {
                                        $dDetails = $departure;
                                        $locations[$departure->getId()] = array(floatval($departure->getLatitude()), floatval($departure->getLongitude()));
                                    }
                                }
                                else {
                                    $locations[$departure->getId()] = array(floatval($departure->getLatitude()), floatval($departure->getLongitude()));
                                }

                                // add flightinfo for map
                                $flightinfo = "
                                ".$flickr->getPhotos($aircrafttypeImages, 'q')."
                                <div style='float:left;'>
                                    <h5>".$departure->getAirportCity()."</h5>
                                    Startzeit: ".$departure->getDeparturetime()." Uhr<br />
                                    Ankunftszeit: ".$departure->getArrivaltime()." Uhr<br />
                                    Flugzeugtyp: ".$departure->getAircrafttype()."<br />
                                    Geschwindigkeit: ".(($departure->getSpeed() == '') ? "-" : ($departure->getSpeed() * 1.852)." km/h")."<br />
                                    Flughöhe: ".(($departure->getAltitude() == '') ? "-" : ($departure->getAltitude() * 30)." m")."<br />
                                    Position: ".$departure->getLatitude().", ".$departure->getLongitude()."<br />
                                    <a href='?site=departures&airport=".$_GET['airport']."&id=".$departure->getId()."'>›› Details</a>
                                </div>";
                                $flightinfos[$departure->getId()] = str_replace(array("\r\n", "\n", "\r"), ' ', $flightinfo);
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

            if(isset($departures)) {
                if(isset($_GET['id']) AND $_GET['id'] > 0) {
                    // edit city for flickr
                    if(strpos($dDetails->getAirportCity(), ' ') !== false) {
                        $city = substr($dDetails->getAirportCity(), 0, strpos($dDetails->getAirportCity(), ' '));
                    }
                    else {
                        $city = $dDetails->getAirportCity();
                    }

                    // search images of city
                    $cityImages = $flickr->searchPhotos($city.',city,attractions', 5);


                    // * output impressions *
                    echo "
                    <div class='panel panel-default'>
                        <div class='panel-body'>
                            <h2>".$dDetails->getAirportCity()."</h2>
                            <h4>".$dDetails->getAirportDescription()."</h4>
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