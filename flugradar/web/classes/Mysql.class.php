<?php

    /** ** airport class ** */
    require_once('./classes/Airport.class.php');
    /** ** departure class ** */
    require_once('./classes/Departure.class.php');
    /** ** user class ** */
    require_once('./classes/User.class.php'); 
    /** ** functions ** */
    require_once('./functions.php');

    /**
    * mysql class
    * 
    * - {@link __construct}:   constructor
    * - {@link query}:         run query and error handling
    * - {@link getError}:      get error message
    * - {@link getAirports}:   get airports
    * - {@link getDepartures}: get departures
    * - {@link getUser}:       get user
    * 
    * @name    classes/Mysql.class.php
    * @author  Dario Kuster
    * @version 27.01.2017
    */
    class Mysql extends mysqli {

        // variable
        protected static $connect;

        /**
        * constructor
        * 
        * @author  Dario Kuster
        * @version 28.11.2016
        * 
        * @return  void
        * @throws  exception when the script can't find the config-file
        */
        public function __construct() {

            if(!isset(self::$connect)) {
                // link config-file
                $configFile = "./configs/config.ini";

                // if conig-file exists
                if(file_exists($configFile)) {
                    $config = parse_ini_file($configFile, true);
                    $dbdata = $config['mysql'];

                    if(isset($dbdata)) {
                        self::$connect = new mysqli($dbdata['host'], $dbdata['username'], $dbdata['password'], $dbdata['database']);

                        if(self::$connect->connect_error) {
                            throw new Exception("MySQL: Fehler bei der MySQL-Verbindung!");
                        }
                    }
                    else {
                        throw new Exception("MySQL: Zugriffsdaten in Config-File fehlen!");
                    }
                }
                else {
                    throw new Exception("MySQL: Config-File fehlt!");
                }
            }

            // return
            return self::$connect;
        }

        /**
        * run query and error handling
        * 
        * @author  Dario Kuster
        * @version 28.11.2016
        * 
        * @param   string $sql
        * @return  void
        */
        public function query($sql) {

            // remove spaces and run query
            $result = self::$connect->query(trim($sql));

            // error handling
            echo $this->getError();

            // return
            return $result;
        }

        /**
        * get error message
        * 
        * @author  Dario Kuster
        * @version 28.11.2016
        * 
        * @return  string
        */
        private function getError() {
            
            // if error
            if(self::$connect->error) {
                // construct error text
                $error_str = getMessage("Error:", "MySQL Error (".self::$connect->errno.")", self::$connect->error);
            }
            else {
                $error_str = "";
            }

            // return
            return $error_str;
        }

        /**
        * get airports
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  array
        */
        public function getAirports() {
            // create array
            $airports = array();

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
            $result = $this->query($sql);

            // *** results? ***
            if($result->num_rows) {
                // loop and save in array
                while ($row = $result->fetch_assoc()) {
                    $airport = new Airport($row['icao_code'], $row['description'], $row['latitude'], $row['longitude']);
                    $airports[] = $airport;
                }
            }
            else {
                $airports = null;
            }

            // return
            return $airports;
       }

        /**
        * get departures
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @param   string $airport
        * @param   string $numberEntries
        * @return  array
        */
        public function getDepartures($airport, $numberEntries) {
            // create array
            $departures = array();

            // *** define query ***
            $sql = "
            SELECT
                `last_departures`.`id`,
                `last_departures`.`flight_ident`,
                `last_departures`.`origin`,
                `last_departures`.`destination`,
                `airports`.`city` AS `airport_city`,
                `airports`.`description` AS `airport_description`,
                DATE_FORMAT(`last_departures`.`departuretime`, '%H:%i') AS `departuretime`,
                DATE_FORMAT(`last_departures`.`arrivaltime`, '%H:%i') AS `arrivaltime`,
                `last_departures`.`aircrafttype`,
                `last_departures`.`speed`,
                `last_departures`.`altitude`,
                `last_departures`.`latitude`,
                `last_departures`.`longitude`
            FROM `last_departures`
                LEFT JOIN `airports`
                    ON `last_departures`.`destination` = `airports`.`icao_code`
            WHERE `last_departures`.`origin` = '".$airport."'
            ORDER BY `last_departures`.`id` DESC
            LIMIT ".$numberEntries;

            // *** run query ***
            $result = $this->query($sql);

            // *** results? ***
            if($result->num_rows) {
                // loop and save in array
                while ($row = $result->fetch_assoc()) {
                    $departure = new Departure(
                            $row['id'],
                            $row['flight_ident'],
                            $row['origin'],
                            $row['destination'],
                            $row['airport_city'],
                            $row['airport_description'],
                            $row['departuretime'],
                            $row['arrivaltime'],
                            $row['aircrafttype'],
                            $row['speed'],
                            $row['altitude'],
                            $row['latitude'],
                            $row['longitude']
                        );
                    $departures[] = $departure;
                }
            }
            else {
                $departures = null;
            }

            // return
            return $departures;
       }

        /**
        * get user
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @param   string $user
        * @return  object
        */
        public function getUser($user) {

            // *** define query ***
            $sql = "
            SELECT 
                `users`.`id`,
                `users`.`username`,
                `users`.`password`,
                `users`.`admin`,
                `user_settings`.`standard_airport`,
                `user_settings`.`number_entries`,
                `user_settings`.`refresh_time`
            FROM `users`
                LEFT JOIN `user_settings`
                    ON `users`.`id` = `user_settings`.`user_id`
            WHERE `users`.`username` = '".$user."'";

            // *** run query ***
            $result = $this->query($sql);

            // *** results? ***
            if($result->num_rows) {
                // loop and save in variable
                $row = $result->fetch_assoc();
                $user = new User($row['id'], $row['username'], $row['password'], $row['admin'], $row['standard_airport'], $row['number_entries'], $row['refresh_time']);
            }
            else {
                $user = null;
            }

            // return
            return $user;
       }
    }
