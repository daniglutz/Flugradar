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
			/** ** Datenbank-Klasse einbinden ** */
			include_once './classes/Database.class.php';
			/** ** Flickr-Klasse einbinden ** */
			require_once('./classes/Flickr.class.php'); 
			/** ** Functions einbinden ** */
			include_once './functions.php';
			
			// *** Datenbankvebindung aufbauen ***
			$db = new Database();
		?>
		
		<?php
			if(isset($_GET['site']) AND $_GET['site'] != 'abfluege')
				echo "<h1 class='text-center'>FlugRadar</h1>";
		
			echo "
			<div class='panel panel-default'>
			
				<nav class='navbar navbar-default'>
					<ul class='nav navbar-nav'>
						<li><a href='?site=abfluege&airport=".$_GET['airport']."'>Letzte Abflüge</a></li>
						<li><a href='?site=login&airport=".$_GET['airport']."'>Login</a></li>
						<li><a href='?site=settings&airport=".$_GET['airport']."'>Einstellungen</a></li>
					</ul>
				</nav>
				
				<div class='panel-body'>";
					
					if(isset($_GET['site']) AND $_GET['site'] != "")
					{
						include $_GET['site'].'.php';
					}
				
				echo "
				</div>
			
			</div>";
		?>
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>