<?php
	
	/**
	* beschreibung
	* 
	* @package Templates
	* @name    depart_refresh.php
	* @author  Dario Kuster
	* @version 28.11.2016
	*/
	
	
	/** ** MySQL-Klasse einbinden ** */
	include_once './classes/Database.class.php';
	/** ** FlightAware-Klasse einbinden ** */
	include_once './classes/FlightAware.class.php';
	
	
	// *** Datenbankvebindung aufbauen ***
	$db = new Database();
	
	
	// FlightAware Objekt erzeugen
	$fa = new FlightAware();
	$departed = $fa->Departed(array('airport' => 'LSZH', 'filter' => 'airline', 'howMany' => '10', 'offset' => '0'));
	
	// speichern
	foreach($departed["DepartedResult"]["departures"] as $departinfo)
	{
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
			'".$departinfo["ident"]."',
			'".$departinfo["origin"]."',
			'".$departinfo["destination"]."',
			from_unixtime(".$departinfo["actualarrivaltime"]."),
			from_unixtime(".$departinfo["estimatedarrivaltime"]."),
			'".$departinfo["aircrafttype"]."',
			".$flightinfo["InFlightInfoResult"]["groundspeed"].",
			".$flightinfo["InFlightInfoResult"]["altitude"].",
			".$flightinfo["InFlightInfoResult"]["latitude"].",
			".$flightinfo["InFlightInfoResult"]["longitude"].")";
		
		// eintragen durchfühern (Fehlerabfang erledigt die Klasse)
		$db->query($sql);
	}
	
	
?>