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


//imports
include_once 'classes/Database.class.php';
include_once 'index.php';


//take session
session_start();

//origin
fromLogin();

//check user data
userValidate();    
    
//login ok    
include 'index.php';


//-------functions-------

function fromLogin() {
    //from login page
    if (isset($_POST['user'])) {
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['pwd'] = $_POST['pwd'];
    }
    //not from login page
    if (!isset($_SESSION['user'])) {
        exit("<p>Kein Zugang<br /><a href='login.php'>Zum Login</a></p>");
    }
}


function inSession(){
    //is not in the session
    if (!isset($_SESSION['user'])) {
        exit("<p>Kein Zugang<br /><a href='login.php'>Zum Login</a></p>");
    }    
}


function userValidate(){
    //create SQL object
    $db = new Database();
    
    //define SQL query
    $sql = "SELECT 
                   `id`,
                   `username`,
                   `password`,
                   `admin`
            FROM `users`            
            ORDER BY `id`";
        
    //execute query
    $result = $db->query($sql);
        
    //variables for access validate
    $bUser = false;
    $bPwd = false;
    
    //check data
    while($row = $result->fetch_assoc()){
        //username
        if ($row['username'] == $_SESSION['user']) {
            $bUser = true;            
        }
        //password
        $dPwd = password_verify($_SESSION['pwd'], $row['password']);
        if ($dPwd) {
            $bPwd = true;            
        }        
    }
    
    //validate access
    if ($bUser == false) {
        exit("      <div class='alert alert-danger' role='alert'>
                    <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
                    <span class='sr-only'>Hinweis:</span>
                    <b>Benutzer nicht vorhanden</b><br />
                    Bitte geben Sie einen gültigen Benutzernamen ein.
                </div>");
    }
    if ($bPwd == false) {
        exit("      <div class='alert alert-danger' role='alert'>
                    <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
                    <span class='sr-only'>Hinweis:</span>
                    <b>Passwort ungültig</b><br />
                    Bitte geben Sie ein gültiges Passwort ein.
                </div>");
    }    
}