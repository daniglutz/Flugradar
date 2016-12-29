<?php
	
	/**
	* refresh departures
	* 
	* @name    depart_refresh.php
	* @author  Dario Kuster
	* @version 28.11.2016
	*/
	
    
	/*
	// FlightAware object
	$fa = new FlightAware();
    // save last departures in array
	$departed = $fa->Departed(array('airport' => $_GET['airport'], 'filter' => 'airline', 'howMany' => $_GET['howMany'], 'offset' => '0'));
	
	// loop departures
	foreach($departed["DepartedResult"]["departures"] as $departinfo)
	{
        // save flight informations in array
		$flightinfo = $fa->InFlightInfo(array('ident' => $departinfo["ident"]));
		
		// INSERT SQL
		$sql = "
		INSERT INTO `last_departures` (
			`flight_ident`,
			`origin`,
			`destination`,
			`arrivaltime`,
			`departuretime`,
			`aircrafttype`,
			`speed`,
			`altitude`,
			`latitude`,
			`longitude`
		) VALUES (
			".mysql_string($departinfo["ident"]).",
			".mysql_string($departinfo["origin"]).",
			".mysql_string($departinfo["destination"]).",
			".mysql_timestamp($departinfo["estimatedarrivaltime"]).",
			".mysql_timestamp($departinfo["actualdeparturetime"]).",
			".mysql_string($departinfo["aircrafttype"]).",
			".mysql_number($flightinfo["InFlightInfoResult"]["groundspeed"]).",
			".mysql_number($flightinfo["InFlightInfoResult"]["altitude"]).",
			".mysql_number($flightinfo["InFlightInfoResult"]["latitude"]).",
			".mysql_number($flightinfo["InFlightInfoResult"]["longitude"]).")";
		
		// run query
		$db->query($sql);
	}
    */
    
    // redirect
    header("Location: ?site=departures&airport=".$_GET['airport']);
	
?>