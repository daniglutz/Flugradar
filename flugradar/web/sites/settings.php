<!DOCTYPE html>
<html lang="en">

    <!--
        File settings
        The settingsfile can set default settings
        
    
        @name settings.php
        @author Daniel Glutz
        @version 29.12.2016
          
    -->
    <?php
    /**     * * database class ** */
    include_once './classes/Mysql.class.php';
    /**     * * functions ** */
    include_once './functions.php';

    echo "
	<div class='row'>
            <div class='col-md-3'>
                <div class='panel panel-default'>
                    <div class='panel-body'>
                    <form action='?site=actionsettings' method='post'>
                        <label for='flughafen'><h2>Standard-Flughafen:</h2></label>";

                        // *** define query ***
                        $sql = "
                        SELECT
                            `icao_code`,
                            `description`,
                            `latitude`,
                            `longitude`
                        FROM `airports`
                        WHERE `latitude` IS NOT NULL AND `longitude` IS NOT NULL
                        ORDER BY `description`";

                        // *** run query ***
                        $resultA = $db->query($sql);

                        // *** results? ***
                        if ($resultA->num_rows) {
                            echo "<select class='form-control' name='flughafen' id='flughafen'>";

                            // ** loop results **
                            while ($row = $resultA->fetch_assoc()) {
                                // * output dropdown for airports *
                                if ($_GET['airport'] == $row['icao_code']) {
                                    echo "<option value='" . $row['icao_code'] . "' selected>" . $row['description'] . "</option>";
                                } else {
                                    echo "<option value='" . $row['icao_code'] . "'>" . $row['description'] . "</option>";
                                }
                            }
                            echo "</select>
                            <br>";
                        }
                        echo "
                        
                        <label for='refresh-time'><h2>Aktualisierungs-Zeit:</h2></label>
                        <input type='text' name='refresh' class='form-control' id='refresh' placeholder='10'>

                        <div class='form-group'><br>
                            <button type='submit' class='btn btn-default'>Speichern</button>
                        </div>
                    </form>
                </div> 
            </div>
        </div>
    </div>";
    ?>
</html>