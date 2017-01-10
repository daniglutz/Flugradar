<?php
    
    /**
    * can vallidate the settings data and update to the application
    * 
    * @name    sites/actionsettings.php
    * @author  Daniel Glutz
    * @version 09.01.2017
    */

    
    // update user settings
    updateSettings($_POST['standardAirport'], $_POST['numberEntries'], $_POST['refreshTime']);
    
    // redirect
    header("Location: ./?site=departures&airport=".$_SESSION['standardAirport']);

    
	/**
	* update user settings
	* 
	* @author  Daniel Glutz
	* @version 28.12.2016
	* 
	* @param   string $standardAirport
	* @param   number $numberEntries
	* @param   number $refreshTime
	* @return  void
	*/
    function updateSettings($standardAirport, $numberEntries, $refreshTime) {
        // *** create database object ***
        $db = new Mysql();
        
        // *** run UPDATE-SQL query ***
        $db->query("
        UPDATE user_settings SET
            standard_airport= '".getCleanedText($standardAirport)."',
            number_entries = ".getCleanedNumber($numberEntries).",
            refresh_time= ".getCleanedNumber($refreshTime)."
        WHERE user_id = ".$_SESSION['userId']);
        
        // *** update session ***
        $_SESSION['standardAirport'] = $standardAirport;
        $_SESSION['numberEntries'] = $numberEntries;
        $_SESSION['refreshTime'] = $refreshTime;
    }