<?php
	
	/**
	* enth�lt Funktionen
	* 
	* - {@link mysql_number}: bereitet den Wert einer Zahl f�r die Datenbank auf
	* - {@link mysql_timestamp}: bereitet den Wert eines Zeitstempels f�r die Datenbank auf
	* - {@link mysql_string}: bereitet den Wert eines Textes f�r die Datenbank auf
	* 
	* @package Flugradar
	* @name    functions.php
	* @author  Dario Kuster
	* @version 30.11.2016
	*/
	
	
	/**
	* bereitet den Wert einer Zahl f�r die Datenbank auf
	* 
	* @author  Dario Kuster
	* @version 30.11.2016
	* 
	* @param   string Wert
	* @return  bearbeitender Wert
	*/
	function mysql_number($val)
	{
		// zur�ckgeben
		return ($val == 0 OR !is_numeric($val)) ? 'NULL' : $val;
	}
	
	/**
	* bereitet den Wert eines Zeitstempels f�r die Datenbank auf
	* 
	* @author  Dario Kuster
	* @version 30.11.2016
	* 
	* @param   string Wert
	* @return  bearbeitender Wert
	*/
	function mysql_timestamp($val)
	{
		// zur�ckgeben
		return ($val == 0 OR !is_numeric($val)) ? 'NULL' : 'FROM_UNIXTIME('.$val.')';
	}
	
	/**
	* bereitet den Wert eines Textes f�r die Datenbank auf
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
		$val = str_replace(array("'", "�"), "`", $val);
		
		// zur�ckgeben
		return "'".$val."'";
	}
	
?>
