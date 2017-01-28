<?php
    
	/**
	* site settings
	* 
	* @name    sites/settings.php
	* @author  Daniel Glutz
	* @version 29.12.2016
	*/
    
    
    echo "
 	<div class='row'>
        <div class='col-md-4'></div>
		<div class='col-md-4'>
            <div class='panel panel-default'>
                <div class='panel-body'>
                    <form action='?site=actionsettings' method='post'>
                        
                        <div class='form-group'>
                            <label for='standardAirport'><h2>Standard-Flughafen:</h2></label>";

                            // *** get aiports ***
                            $airports = $db->getAirports();

                            // *** results? ***
                            if(isset($airports)) {
                                echo "<select class='form-control' id='standardAirport' name='standardAirport' >";

                                // ** loop airports **
                                foreach ($airports as $airport) {
                                    // * output dropdown *
                                    if ($_SESSION['standardAirport'] == $airport->getIcaoCode()) {
                                        echo "<option value='".$airport->getIcaoCode()."' selected>".$airport->getDescription()."</option>";
                                    } else {
                                        echo "<option value='".$airport->getIcaoCode()."'>".$airport->getDescription()."</option>";
                                    }
                                }

                                echo "</select>";
                            }
                        
                        echo "
                        </div>
                        
                        <div class='form-group'>
                            <label for='numberEntries'><h2>Anzahl Listeneinträge:</h2></label>
                            <input type='number' id='numberEntries' name='numberEntries' class='form-control' value='".$_SESSION['numberEntries']."' min='1'>
                        </div>
                        
                        <div class='form-group'>
                            <label for='refreshTime'><h2>Aktualisierungs-Zeit:</h2></label>
                            <input type='number' id='refreshTime' name='refreshTime' class='form-control' placeholder='".$_SESSION['refreshTime']."' step='30'>
                        </div>
                        <br />

                        <div align='center'>
                            <button type='submit' class='btn btn-default'>Speichern</button>
                        </div>
                        
                    </form>";
                    
                    if($_SESSION['admin'] == 1) {
                        echo "
                        <div align='center'>
                            <br />
                            <a href='?site=newuser&airport=".$_GET['airport']."'>
                                <button type='button' class='btn btn-default'>Neuer Benutzer</button>
                            </a>
                        </div>";
                    }
                    
                    echo "
                </div>
            </div>
        </div>
        <div class='col-md-4'></div>
    </div>";
    
?>