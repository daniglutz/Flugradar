<!DOCTYPE html>
<html lang="en">
 	<div class="row">
		<div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="actionlogin.php" method="post">

                        <?php
                            echo (isset($_SESSION['error'])) ? $_SESSION['error'] : "";
                        ?>

                        <div class="form-group">
                            <label for="user"><h2>Benutzer:</h2></label>
                            <input type="text" name="user" class="form-control" id="user" required="">
                        </div>

                        <div class="form-group">
                            <label for="pwd"><h2>Passwort:</h2></label>
                            <input type="password" name="pwd" class="form-control" id="pwd" required="">  
                        </div>

                        <div align="center">
                            <button type="submit" class="btn btn-default">Anmelden</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</html>