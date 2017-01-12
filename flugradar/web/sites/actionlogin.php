<?php
    
    /**
    * can vallidate the login data and give access to the application
    * 
    * @name    sites/actionlogin.php
    * @author  Andreas Trachsel
    * @version 28.12.2016
    */
    
    
    // take session
    session_start();
    
    // check user data
    userValidate($_POST['user'], $_POST['pwd']);    

    // login ok -> go to settings
    header("Location: ./?site=settings&airport=".$_SESSION['standardAirport']);
    
    
	/**
	* check whether user and password are valid
	* 
	* @author  Andreas Trachsel
	* @version 28.12.2016
	* 
	* @param   string $user
	* @param   string $pw
	* @return  void
	*/
    function userValidate($user, $pw) {
        // variables for access validate
        $userValid = false;
        $pwValid = false;

        // create database object
        $db = new Mysql();

        // define SQL query
        $sql = "
        SELECT 
            `users`.`id`,
            `users`.`username`,
            `users`.`password`,
            `users`.`admin`,
            `user_settings`.`standard_airport`,
            `user_settings`.`number_entries`,
            `user_settings`.`refresh_time`
        FROM `users`
            LEFT JOIN `user_settings`
                ON `users`.`id` = `user_settings`.`user_id`
        WHERE `users`.`username` = '".$user."'";

        // *** run query ***
        $result = $db->query($sql);

        // *** results? ***
        if($result->num_rows) {
            // ** save result **
            $row = $result->fetch_assoc();

            // check username
            if($row['username'] == $user) {
                $userValid = true;
            }
            // check password
            if(password_verify($pw, $row['password'])) {
                $pwValid = true;
            }

            // set session
            if($userValid AND $pwValid) {
                setSession($row);
            }
        }
        
        // set error
        setError($userValid, $pwValid);
    }
    
	/**
	* set session
	* 
	* @author  Andreas Trachsel
	* @version 28.12.2016
	* 
	* @param   array $row
	* @return  void
	*/
    function setSession($row) {
        $_SESSION['userId'] = $row['id'];
        $_SESSION['user'] = $row['username'];
        $_SESSION['admin'] = $row['admin'];
        $_SESSION['standardAirport'] = $row['standard_airport'];
        $_SESSION['numberEntries'] = $row['number_entries'];
        $_SESSION['refreshTime'] = $row['refresh_time'];
    }
    
	/**
	* set error
	* 
	* @author  Andreas Trachsel
	* @version 28.12.2016
	* 
	* @param   boolean $userValid
	* @param   boolean $pwValid
	* @return  void
	*/
    function setError($userValid, $pwValid) {
        // user not valid
        if($userValid == false) {
            $_SESSION['errorLogin'] = getMessage("Hinweis:", "Benutzer nicht vorhanden", "Bitte geben Sie einen gültigen Benutzernamen ein");
        }
        // password not valid
        elseif($pwValid == false) {
            $_SESSION['errorLogin'] = getMessage("Hinweis:", "Passwort ungültig", "Bitte geben Sie ein gültiges Passwort ein");
        }
        // no error
        else {
            $_SESSION['errorLogin'] = null;
        }
    }
    
?>