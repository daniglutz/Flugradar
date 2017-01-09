<?php

       /*
	* actionsettings
        * 
	* @package Flugradar
	* @name    sites/actionsettings.php
	* @author  Daniel Glutz
	* @version 09.01.2017
	*/

    updateDB($_POST['refresh'], $_POST['flughafen']);

    // update DB user_settings
    function updateDB($refresh, $flughafen) {
        // create database object
        $db = new Mysql();
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        } 

        //define SQL query
        $sql = "
        SELECT 
            `users`.`id`,
            `user_settings`.`standard_airport`,
            `user_settings`.`number_entries`,
            `user_settings`.`refresh_time`
        FROM `users`
            LEFT JOIN `user_settings`
                ON `users`.`id` = `user_settings`.`user_id`
        WHERE `users`.`username` = '" . ($_SESSION['user']) . "'";

        // *** run query ***
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        $userSettingsId = $row['id'];
           
        $sql = "UPDATE user_settings SET standard_airport='$flughafen', refresh_time='$refresh' WHERE id='$userSettingsId'";
        $db->query($sql);
        
        if ($db->query($sql) === TRUE) {
            echo "<script type='text/javascript'>alert('Erfolgreich gespeichert')</script>";
        } else {
            echo "<script type='text/javascript'>alert('Speichern fehlgeschlagen')</script>";
        }
    }