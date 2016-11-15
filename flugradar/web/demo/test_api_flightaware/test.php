<?php
	
	/*require('FlightAware.class.php');
	
	
	$faClient = new FlightAware();
	$departed = $faClient->Departed(array('airport' => 'LSZH', 'filter' => 'airline', 'howMany' => '5', 'offset' => '0'));*/
	$departed = json_decode(file_get_contents('testdaten_Departed.txt'), TRUE);
	
	// Daten formatiert ausgeben
	foreach($departed["DepartedResult"]["departures"] as $arr_info)
	{
		/*$flightinfo = $faClient->InFlightInfo(array('ident' => $arr_info["ident"));*/
		$flightinfo = json_decode(file_get_contents('testdaten_InFlightInfo.txt'), TRUE);
		
		echo "
		Zielort: ".$arr_info["destinationCity"]."<br />
		Ankunftszeit: ".date("H:i", $arr_info["estimatedarrivaltime"])."<br />
		Flugzeugtyp: ".$arr_info["aircrafttype"]."<br />
		Geschwindigkeit: ".$flightinfo["InFlightInfoResult"]["groundspeed"]."<br />
		Flughöhe: ".$flightinfo["InFlightInfoResult"]["altitude"]."<br />
		Position: ".$flightinfo["InFlightInfoResult"]["highLatitude"].", ".$flightinfo["InFlightInfoResult"]["highLongitude"]."<br />
		<br /><br />";
	}
	
?>