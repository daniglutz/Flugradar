<?php

    /**
    * site user create
    * 
    * @name    sites/user_create.php
    * @author  Dario Kuster
    * @version 06.01.2017
    */
    
    
    echo "
    <div class='row'>
        <div class='col-md-4'></div>
        <div class='col-md-4'>
            <div class='panel panel-default'>
                <div class='panel-body'>";
                
                    // check if admin
                    if($_SESSION['admin'] == 1) {
                        echo "
                        <form action='?site=actionnewuser' method='post'>

                            ".((isset($_SESSION['error'])) ? $_SESSION['error'] : "")."

                            <div class='form-group'>
                                <label for='user'><h2>Benutzername:</h2></label>
                                <input type='text' name='user' class='form-control' id='user' required=''>
                            </div>

                            <div class='form-group'>
                                <label for='pwd'><h2>Passwort:</h2></label>
                                <input type='password' name='pwd' class='form-control' id='pwd' required=''>  
                            </div>

                            <div class='form-group'>
                                <label for='admin'><h2>Admin:</h2></label>
                                <input type='checkbox' name='admin' class='checkbox' id='admin'>  
                            </div>

                            <div align='center'>
                                <button type='submit' class='btn btn-default'>Erstellen</button>
                            </div>

                        </form>

                        <div align='center'>
                            <br />
                            <a href='?site=settings&airport=".$_GET['airport']."'>
                                <button type='button' class='btn btn-default'>zurück</button>
                            </a>
                        </div>";
                    } else {
                        // get message
                        echo getMessage("Hinweis:", "Kein Zugriff", "Sie verfügen über keine Rechte, diese Seite zu sehen.");
                    }
                    
                    echo "
                </div>
            </div>
        </div>
        <div class='col-md-4'></div>
    </div>";
    
?>