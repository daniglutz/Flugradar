<?php
	
	/**
	* functions
	* 
	* - {@link getMessage}: get formatted message
	* - {@link getCleanedNumber}: clean and get number for database
	* - {@link getCleanedTimestamp}: clean and get timestamp for database
	* - {@link getCleanedText}: clean and get text for database
	* 
	* @package Flugradar
	* @name    functions.php
	* @author  Dario Kuster
	* @version 30.11.2016
	*/
    
    
	/**
	* get formatted message
	* 
	* @author  Dario Kuster
	* @version 03.01.2017
	* 
	* @param   string title
	* @param   string subtitle
	* @param   string message
	* @return  formatted message
	*/
    function getMessage($title, $subtitle, $message) {
        // construct message
        $msg = "
        <div class='alert alert-danger' role='alert'>
            <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
            <span class='sr-only'>".$title."</span>
            <b>".$subtitle."</b><br />
            ".$message."
        </div>";

        // return message
        return $msg;
    }
    
	/**
	* clean and get number for database
	* 
	* @author  Dario Kuster
	* @version 30.11.2016
	* 
	* @param   number number
	* @return  cleaned number
	*/
	function getCleanedNumber($val) {
		// return number
		return ($val == 0 OR !is_numeric($val)) ? 'NULL' : $val;
	}
	
	/**
	* clean and get timestamp for database
	* 
	* @author  Dario Kuster
	* @version 30.11.2016
	* 
	* @param   timestamp timestamp
	* @return  cleaned timestamp
	*/
	function getCleanedTimestamp($val) {
		// return date
		return ($val == 0 OR !is_numeric($val)) ? 'NULL' : 'FROM_UNIXTIME('.$val.')';
	}
	
	/**
	* clean and get text for database
	* 
	* @author  Dario Kuster
	* @version 30.11.2016
	* 
	* @param   string text
	* @return  cleaned text
	*/
	function getCleanedText($val) {
		// return text
		return "'".str_replace(array("'", "´"), "`", $val)."'";
	}
	
?>
