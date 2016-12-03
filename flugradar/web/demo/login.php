<!DOCTYPE html>
<?php
    //Sessionmanagement
    session_start();
    
    session_destroy();
    $_SESSION = array();
    
?>
<html lang="en">
    <form action="actionlogin.php" method="post">

        <div class="title">
            <h1 align="center">Log In</h1>
        </div>

        <!--        <div class="form-group">
                    <label for="standort"><h2 align="center">Standort:
                            <select name="standort" class="form-control" id="standort" required="">
                                <option></option>
                                <option>Baden</option>
                                <option>Zürich</option>
                                <option>Bern</option>
                                <option>Basel</option>
                            </select>
                        </h2></label>
                </div>-->

        <div class="form-group">
            <label for="user"><h2>Benutzer:
                    <input type="text" name="Benutzer" class="form-control" id="user" required="">
                </h2></label>
        </div>

        <div class="form-group">
            <label for="pwd"><h2>Passwort:
                    <input type="password" class="form-control" id="pwd" required="">
                </h2></label>
        </div>

        <div align="center">
            <button type="submit" class="btn btn-default">Anmelden</button>
        </div>
    </form>
</html>