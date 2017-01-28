<?php
	
	/**
	* site login
	* 
	* @name    sites/login.php
	* @author  Dario Kuster
	* @version 06.01.2017
	*/
    
    
    echo "
 	<div class='row'>
		<div class='col-md-4'></div>
        <div class='col-md-4'>
            <div class='panel panel-default'>
                <div class='panel-body'>
                    <form action='?site=actionlogin' method='post'>
                        
                        ".((isset($_SESSION['error'])) ? $_SESSION['error'] : "")."
                        
                        <div class='form-group'>
                            <label for='user'><h2>Benutzername:</h2></label>
                            <input type='text' name='user' class='form-control' id='user' required=''>
                        </div>

                        <div class='form-group'>
                            <label for='pwd'><h2>Passwort:</h2></label>
                            <input type='password' name='pwd' class='form-control' id='pwd' required=''>  
                        </div>

                        <div align='center'>
                            <button type='submit' class='btn btn-default'>Anmelden</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class='col-md-4'></div>
    </div>";
    
?>