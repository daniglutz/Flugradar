<?php

    /**
    * refresh departures
    * 
    * @name    sites/depart_refresh.php
    * @author  Dario Kuster
    * @version 07.02.2017
    */


    // FlightAware object
    $fa = new FlightAware();
    // save last departures in array
    $departed = $fa->Departed(array('airport' => $_GET['airport'], 'filter' => 'airline', 'howMany' => $_GET['howMany'], 'offset' => '0'));

    // loop departures
    foreach($departed["DepartedResult"]["departures"] as $departinfo) {
        // save flight informations in array
        $flightinfo = $fa->InFlightInfo(array('ident' => $departinfo["ident"]));

        // INSERT SQL
        $sql = "
        INSERT INTO last_departures (
            flight_ident,
            origin,
            destination,
            departuretime,
            arrivaltime,
            aircrafttype,
            speed,
            altitude,
            latitude,
            longitude
        ) VALUES (
            ".getCleanedText($departinfo["ident"]).",
            ".getCleanedText($departinfo["origin"]).",
            ".getCleanedText($departinfo["destination"]).",
            ".getCleanedTimestamp($departinfo["actualdeparturetime"]).",
            ".getCleanedTimestamp($departinfo["estimatedarrivaltime"]).",
            ".getCleanedText($departinfo["aircrafttype"]).",
            ".getCleanedNumber($flightinfo["InFlightInfoResult"]["groundspeed"]).",
            ".getCleanedNumber($flightinfo["InFlightInfoResult"]["altitude"]).",
            ".getCleanedNumber($flightinfo["InFlightInfoResult"]["latitude"]).",
            ".getCleanedNumber($flightinfo["InFlightInfoResult"]["longitude"]).")";

        // run query
        $db->query($sql);
    }
    
    // redirect
    header("Location: ?site=departures&airport=".$_GET['airport']);
    