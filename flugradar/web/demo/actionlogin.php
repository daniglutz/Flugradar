<?php
//Importe
include_once 'classes/Database.class.php';



//Session aufnehmen
session_start();

//Ursprung
fromLogin();

//Benutzerdatenbank abfragen
userValidate();    
    
//Weiterleitung    
include 'index.php';




//-------Funktionen-------

function fromLogin() {
    //Kommt von Login Seite
    if (isset($_POST['user'])) {
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['pwd'] = $_POST['pwd'];
    }
    //Kommt von ausserhalb
    if (!isset($_SESSION['user'])) {
        exit("<p>Kein Zugang<br /><a href='login.php'>Zum Login</a></p>");
    }
}


function inSession(){
    //Kommt von ausserhalb
    if (!isset($_SESSION['user'])) {
        exit("<p>Kein Zugang<br /><a href='login.php'>Zum Login</a></p>");
    }    
}


function userValidate(){
    //SQL Objekt erzeugen
    $db = new Database();
    
    //SQL abfrage definieren
    $sql = "SELECT 
                   `id`,
                   `user`,
                   `password`,
                   `admin`,
            FROM `users`            
            ORDER BY `id`";
        
    //Von Datenbank lesen
    $result = $db->query($sql);
    
    var_dump($result);
    
    //Prüfen Benutzer und Passwort
    $bUser = false;
    $bPwd = false;
    
//    foreach ($result as $value) {
//        if ($value == $_SESSION['user']) {
//            $bUser = true;            
//        }
//        if ($bUser == false) {
//            exit("<p>Benutzer nicht vorhanden<br /><a href='login.php'>Zum Login</a></p>");           
//        }
//        if ($value == $_SESSION['pwd']) {
//            $bPwd = true;            
//        }
//        if ($bPwd == false) {
//            exit("<p>Passwort ist falsch<br /><a href='login.php'>Zum Login</a></p>");
//        }        
//    }           
}