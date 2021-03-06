<?php

    /**
    * functions
    * 
    * - {@link getMessage}:          get formatted message
    * - {@link getCleanedNumber}:    clean and get number for database
    * - {@link getCleanedTimestamp}: clean and get timestamp for database
    * - {@link getCleanedText}:      clean and get text for database
    * - {@link getCleanedCheckbox}:  clean and get checkbox for database
    * 
    * @name    functions.php
    * @author  Dario Kuster
    * @version 23.01.2017
    */


    /**
    * get formatted message
    * 
    * @author  Dario Kuster
    * @version 03.01.2017
    * 
    * @param   string $title
    * @param   string $subtitle
    * @param   string $message
    * @return  string
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
    * @param   number $val
    * @return  string/number
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
    * @param   timestamp $val
    * @return  string
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
    * @param   string $val
    * @return  string
    */
    function getCleanedText($val) {
        // return text
        return "'".str_replace(array("'", "´"), "`", $val)."'";
    }

    /**
    * clean and get checkbox for database
    * 
    * @author  Dario Kuster
    * @version 23.01.2017
    * 
    * @param   string $val
    * @return  integer
    */
    function getCleanedCheckbox($val) {
        // return value
        return ($val == 'on') ? 1 : 0;
    }

?>
