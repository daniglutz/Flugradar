<?php
/**
 * File actionlogin
 * The actionlogin can vallidate the login data and give access to the application.
 * 
 * @name actionlogin.php
 * @author Andreas Trachsel
 * @version 28.12.2016
 *  
 */


/** ** database class ** */
include_once './classes/Database.class.php';


// take session
session_start();

// check user data
userValidate();    
    
// login ok
header("Location: ./?site=departures&airport=LSZH");


//-------functions-------

function inSession() {
    //is not in the session
    if (!isset($_SESSION['user'])) {
        exit("<p>Kein Zugang<br /><a href='login.php'>Zum Login</a></p>");
    }    
}


function userValidate() {
    // variables for access validate
    $bUser = false;
    $bPwd = false;
    
    // create database object
    $db = new Database();
    
    //define SQL query
    $sql = "
    SELECT 
        `id`,
        `username`,
        `password`,
        `admin`
    FROM `users`            
    WHERE `username` = '".$_POST['user']."'";
    
    // *** run query ***
    $result = $db->query($sql);

    // *** results? ***
    if($result->num_rows) {
        // ** save result **
        $row = $result->fetch_assoc();
        
        // check username
        if($row['username'] == $_POST['user']) {
            $bUser = true;
        }
        // check password
        if(password_verify($_POST['pwd'], $row['password'])) {
            $bPwd = true;
        }
        
        if($bUser AND $bPwd) {
            setSession($row);
        }
        else {
            error($bUser, $bPwd);
        }
    }
    else {
        error($bUser, $bPwd);
    }
}

function setSession($row) {
    $_SESSION['id'] = $row['id'];
    $_SESSION['user'] = $row['username'];
    $_SESSION['admin'] = $row['admin'];
}

// validate access
function error($bUser, $bPwd) {
    
    if($bUser == false) {
        $_SESSION['error'] = "
        <div class='alert alert-danger' role='alert'>
            <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
            <span class='sr-only'>Hinweis:</span>
            <b>Benutzer nicht vorhanden</b><br />
            Bitte geben Sie einen gültigen Benutzernamen ein.
        </div>";
    }
    elseif($bPwd == false) {
        $_SESSION['error'] = "
        <div class='alert alert-danger' role='alert'>
                <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
                <span class='sr-only'>Hinweis:</span>
                <b>Passwort ungültig</b><br />
                Bitte geben Sie ein gültiges Passwort ein.
        </div>";
    }
}