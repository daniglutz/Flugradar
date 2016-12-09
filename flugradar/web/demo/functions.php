<?php
	
	/**
	* enthält Funktionen
	* 
	* - {@link mysql_number}: bereitet den Wert einer Zahl für die Datenbank auf
	* - {@link mysql_timestamp}: bereitet den Wert eines Zeitstempels für die Datenbank auf
	* - {@link mysql_string}: bereitet den Wert eines Textes für die Datenbank auf
	* 
	* @package Flugradar
	* @name    functions.php
	* @author  Dario Kuster
	* @version 30.11.2016
	*/
	
	
	/**
	* bereitet den Wert einer Zahl für die Datenbank auf
	* 
	* @author  Dario Kuster
	* @version 30.11.2016
	* 
	* @param   string Wert
	* @return  bearbeitender Wert
	*/
	function mysql_number($val)
	{
		// zurückgeben
		return ($val == 0 OR !is_numeric($val)) ? 'NULL' : $val;
	}
	
	/**
	* bereitet den Wert eines Zeitstempels für die Datenbank auf
	* 
	* @author  Dario Kuster
	* @version 30.11.2016
	* 
	* @param   string Wert
	* @return  bearbeitender Wert
	*/
	function mysql_timestamp($val)
	{
		// zurückgeben
		return ($val == 0 OR !is_numeric($val)) ? 'NULL' : 'FROM_UNIXTIME('.$val.')';
	}
	
	/**
	* bereitet den Wert eines Textes für die Datenbank auf
	* 
	* @author  Dario Kuster
	* @version 30.11.2016
	* 
	* @param   string Wert
	* @return  bearbeitender Wert
	*/
	function mysql_string($val)
	{
		// remove spaces
		$val = trim($val);
		// repleace quotes
		$val = str_replace(array("'", "´"), "`", $val);
		
		// zurückgeben
		return "'".$val."'";
	}
	
?>
