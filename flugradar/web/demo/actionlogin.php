<?php

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
    
    //Prüfen Benutzer und Passwort
    while($row = $result->fetch_assoc()){
        if($_SESSION['user'] == $row['user']){
            if($_SESSION['pwd'] == $row['pwd']){                       
                break;
            }
            else{
                exit("<p>Passwort ist falsch.<br /><a href='login.php'>Zum Login</a></p>");
            }
        }
        else{
            exit("<p>Benutzer ist falsch.<br /><a href='login.php'>Zum Login</a></p>");
        }
    }
            
}