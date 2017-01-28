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

        // *** get user ***
        $userobj = $db->getUser($user);

        // *** results? ***
        if(isset($userobj)) {
            // check username
            if($userobj->getUsername() == $user) {
                $userValid = true;
            }
            // check password
            if(password_verify($pw, $userobj->getPassword())) {
                $pwValid = true;
            }

            // set session
            if($userValid AND $pwValid) {
                setSession($userobj);
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
	* @param   object $userobj
	* @return  void
	*/
    function setSession($userobj) {
        $_SESSION['userId'] = $userobj->getId();
        $_SESSION['user'] = $userobj->getUsername();
        $_SESSION['admin'] = $userobj->getAdmin();
        $_SESSION['standardAirport'] = $userobj->getStandardAirport();
        $_SESSION['numberEntries'] = $userobj->getNumberEntries();
        $_SESSION['refreshTime'] = $userobj->getRefreshTime();
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
            $_SESSION['error'] = getMessage("Hinweis:", "Benutzer nicht vorhanden", "Bitte geben Sie einen gültigen Benutzernamen ein");
        }
        // password not valid
        elseif($pwValid == false) {
            $_SESSION['error'] = getMessage("Hinweis:", "Passwort ungültig", "Bitte geben Sie ein gültiges Passwort ein");
        }
        // no error
        else {
            $_SESSION['error'] = null;
        }
    }
    
?>