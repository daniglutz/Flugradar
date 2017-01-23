<?php
    
    /**
    * create a new user
    * 
    * @name    sites/actionusercreate.php
    * @author  Dario Kuster
    * @version 28.12.2016
    */
    
    
    // check username
    checkUsername($_POST['user']);    

    // if no errors
    if(!isset($_SESSION['error'])) {
        // encrypt password
        $pwd_encrypt = password_hash($_POST['pwd'], PASSWORD_BCRYPT);

        // create user
        createUser($_POST['user'], $pwd_encrypt, $_POST['admin']);

        // redirect
        header("Location: ./?site=settings&airport=".$_SESSION['standardAirport']);
    } else {
        // error -> go back
        header("Location: ./?site=newuser&airport=".$_SESSION['standardAirport']);
    }
    
    
	/**
	* check whether user and password are valid
	* 
	* @author  Andreas Trachsel
	* @version 28.12.2016
	* 
	* @param   string $user
	* @return  void
	*/
    function checkUsername($user) {
        // create database object
        $db = new Mysql();

        // define SQL query
        $sql = "
        SELECT *
        FROM `users`
        WHERE `username` = '".$user."'";

        // *** run query ***
        $result = $db->query($sql);

        // *** results? ***
        if($result->num_rows) {
            // set error
            $_SESSION['error'] = getMessage("Hinweis:", "Benutzer existiert schon", "Bitte geben Sie einen anderen Benutzernamen ein");
        } else {
            $_SESSION['error'] = null;
        }
    }
    
	/**
	* create user
	* 
	* @author  Dario Kuster
	* @version 28.12.2016
	* 
	* @param   string $user
	* @param   string $pwd
	* @param   string $admin
	* @return  void
	*/
    function createUser($user, $pwd, $admin) {
        // create database object
        $db = new Mysql();
        
        // INSERT SQL (user)
        $sql = "
        INSERT INTO `users` (
            `username`,
            `password`,
            `admin`
        ) VALUES (
            ".getCleanedText($user).",
            ".getCleanedText($pwd).",
            ".getCleanedCheckbox($admin).")";
        
        // *** run query ***
        $db->query($sql);
        
        
        // INSERT SQL (default settings)
        $sql = "
        INSERT INTO `user_settings` (
            `user_id`,
            `standard_airport`
        ) VALUES (
            (SELECT MAX(`id`) FROM `users`),
            ".getCleanedText($_SESSION['standardAirport']).");";
        
        // *** run query ***
        $db->query($sql);
    }
    
?>