<?php

	/**
	* airport class
	* 
	* - {@link __construct}:    constructor
	* - {@link getIcaoCode}:    get icao code
    * - {@link getDescription}: get description
    * - {@link getLatitude}:    get latitude
    * - {@link getLongitude}:   get longitude
	* 
	* @name    classes/Airport.class.php
	* @author  Dario Kuster
	* @version 27.01.2017
	*/
    class Airport {
        // Variables
        private $icaoCode;
        private $description;
        private $latitude;
        private $longitude;

		/**
		* constructor
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  void
		*/
        function __construct($icaoCode, $description, $latitude, $longitude) {
            $this->icaoCode = $icaoCode;
            $this->description = $description;
            $this->latitude = $latitude;
            $this->longitude = $longitude;
        }

		/**
		* get icao code
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  string
		*/
        public function getIcaoCode() {
            return $this->icaoCode;
        }

		/**
		* get description
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  string
		*/
        public function getDescription() {
            return $this->description;
        }
        
		/**
		* get latitude
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  float
		*/
        public function getLatitude() {
            return $this->latitude;
        }
        
		/**
		* get longitude
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  float
		*/
        public function getLongitude() {
            return $this->longitude;
        }
    }