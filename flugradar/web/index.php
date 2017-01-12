<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>FlugRadar</title>
		
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap.css" rel="stylesheet">
		
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<?php
			/** ** mysql class ** */
			require_once('./classes/Mysql.class.php');
            /** ** flightaware class ** */
            require_once('./classes/FlightAware.class.php');
			/** ** flickr class ** */
			require_once('./classes/Flickr.class.php'); 
			/** ** functions ** */
			require_once('./functions.php');
            
            // *** start/continue session ***
			session_start();
            
			// *** create database object ***
			$db = new Mysql();
            
            // *** create flickr object ***
            $flickr = new Flickr();
            
            
            // select standard airport
            if((!isset($_GET['airport']) OR $_GET['airport'] == '') AND isset($_SESSION['standardAirport'])) {
                $_GET['airport'] = $_SESSION['standardAirport'];
            }
            
            // ** output menu **
			echo "
            <nav class='navbar navbar-default navbar-fixed-top'>
                <ul class='nav navbar-nav'>";
                    
                    if(isset($_SESSION['user'])) {
                        echo "
                        <li>
                            <a href='?site=departures&airport=".$_GET['airport']."'>
                                <span class='glyphicon glyphicon-map-marker' aria-hidden='true'></span> Letzte Abflüge
                            </a>
                        </li>";
                    } else {
                        echo "
                        <li>
                            <a href='?site=login'>
                                <span class='glyphicon glyphicon-log-in' aria-hidden='true'></span> Login
                            </a>
                        </li>";
                    }
                    
                    echo "
                </ul>
            </nav>";
			
            // ** include subsite **
            if(isset($_GET['site']) AND ($_GET['site'] == 'actionlogin' OR isset($_SESSION['user']))) {
                include 'sites/'.$_GET['site'].'.php';
            } elseif(!isset($_SESSION['user'])) {
                include 'sites/login.php';
            }
		?>
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>