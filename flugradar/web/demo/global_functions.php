<?php
	
	/**
	* enthält alle globalen Funktionen
	* 
	* - {@link connectmysql}: stellt eine Verbindung zu einer MySQL-Datenbank her
	* - {@link selectdb}: wählt eine MySQL-Datenbank aus
	* 
	* @package FLUGRADAR
	* @name    global_functions.php
	* @author  Dario Kuster
	* @version 13.03.2015
	*/
	
	
	{// *** MySQL ***
	/**
	* stellt eine Verbindung zu einer MySQL-Datenbank her
	* 
	* @author  Dario Kuster
	* @version 17.11.2010
	* 
	* @param   string $db Datenbank zu der verbunden werden soll
	* @return  void
	*/
	function connectmysql($db)
	{
		$connect = @mysql_pconnect('localhost', 'root', 'root');
		if(!$connect)
		{
			die("Fehler bei der MySQL-Verbindung:<br />".mysql_error());
		}
		
		$select = @mysql_select_db($db, $connect);
		if(!$select)
		{
			die("Fehler bei der Datenbank-Auswahl:<br />".mysql_error());
		}
	}
	
	/**
	* wählt eine MySQL-Datenbank aus
	* 
	* @author  Dario Kuster
	* @version 01.10.2008
	* 
	* @param   string $db zu wählende Datenbank
	* @return  void
	*/
	function selectdb($db)
	{
		$select = @mysql_select_db($db);
		if(!$select)
		{
			die("Fehler bei der Datenbank-Auswahl:<br />".mysql_error());
		}
	}
	}// *** / MySQL ***
	
	
?>
