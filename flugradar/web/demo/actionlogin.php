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
                   `username`,
                   `password`,
                   `admin`
            FROM `users`            
            ORDER BY `id`";
        
    //Von Datenbank lesen
    $result = $db->query($sql);
        
    //Pruefvariablen
    $bUser = false;
    $bPwd = false;
    
    //Passwort hash
//    var_dump($_SESSION['pwd']);
//    $hPwd = password_hash($_SESSION['pwd'], PASSWORD_BCRYPT);
//    var_dump($hPwd);
    
    //Daten prüfen
    while($row = $result->fetch_assoc()){
        //Benutzername
        if ($row['username'] == $_SESSION['user']) {
            $bUser = true;            
        }
        //Passwort
        $dPwd = password_verify($_SESSION['pwd'], $row['password']);
        if ($dPwd) {
            $bPwd = true;            
        }        
    }
    
    //Zugang prüfen
    if ($bUser == false) {
        //exit("<p>Benutzer nicht vorhanden<br /><a href='login.php'>Zum Login</a></p>");
        exit('<SCRIPT type="text/javascript">
        alert("Benutzer nicht vorhanden");
        </script>
        <body onLoad="document.location.href="index.php">
        <!-- <a href="index.php">Zum Login</a> -->
        ');
    }
    if ($bPwd == false) {
        exit("<p>Passwort ist falsch<br /><a href='login.php'>Zum Login</a></p>");
    }
    
}