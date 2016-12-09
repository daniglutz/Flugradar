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
	/** ** Functions einbinden ** */
	include_once './functions.php';
	
	
	// *** Datenbankvebindung aufbauen ***
	$db = new Database();
	
	
	// FlightAware Objekt erzeugen
	$fa = new FlightAware();
	$departed = $fa->Departed(array('airport' => $_GET['airport'], 'filter' => 'airline', 'howMany' => $_GET['howMany'], 'offset' => '0'));
	
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
		
		// eintragen durchfühern (Fehlerabfang erledigt die Klasse)
		$db->query($sql);
	}
	
?>