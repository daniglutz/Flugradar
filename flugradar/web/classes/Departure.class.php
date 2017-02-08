<?php
    
    /**
    * departure class
    * 
    * - {@link __construct}:           constructor
    * - {@link getId}:                 get id
    * - {@link getFlightIdent}:        get flightident
    * - {@link getOrigin}:             get origin
    * - {@link getDestination}:        get destination
    * - {@link getAirportCity}:        get airport city
    * - {@link getAirportDescription}: get airport description
    * - {@link getDeparturetime}:      get departuretime
    * - {@link getArrivaltime}:        get arrivaltime
    * - {@link getAircrafttype}:       get aircrafttype
    * - {@link getSpeed}:              get speed
    * - {@link getAltitude}:           get altitude
    * - {@link getLatitude}:           get latitude
    * - {@link getLongitude}:          get longitude
    * 
    * @name    classes/Departure.class.php
    * @author  Dario Kuster
    * @version 27.01.2017
    */
    class Departure {
        // variables
        private $id;
        private $flightIdent;
        private $origin;
        private $destination;
        private $airportCity;
        private $airportDescription;
        private $departuretime;
        private $arrivaltime;
        private $aircrafttype;
        private $speed;
        private $altitude;
        private $latitude;
        private $longitude;
        
        /**
        * constructor
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @param   integer  $id
        * @param   string   $flightIdent
        * @param   string   $origin
        * @param   string   $destination
        * @param   string   $airportCity
        * @param   string   $airportDescription
        * @param   datetime $departuretime
        * @param   datetime $arrivaltime
        * @param   string   $aircrafttype
        * @param   integer  $speed
        * @param   integer  $altitude
        * @param   float    $latitude
        * @param   float    $longitude
        * @return  void
        */
        function __construct($id, $flightIdent, $origin, $destination, $airportCity, $airportDescription, $departuretime, $arrivaltime, $aircrafttype, $speed, $altitude, $latitude, $longitude) {
            $this->id = $id;
            $this->flightIdent = $flightIdent;
            $this->origin = $origin;
            $this->destination = $destination;
            $this->airportCity = $airportCity;
            $this->airportDescription = $airportDescription;
            $this->departuretime = $departuretime;
            $this->arrivaltime = $arrivaltime;
            $this->aircrafttype = $aircrafttype;
            $this->speed = $speed;
            $this->altitude = $altitude;
            $this->latitude = $latitude;
            $this->longitude = $longitude;
        }

        /**
        * get id
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  integer
        */
        public function getId() {
            return $this->id;
        }

        /**
        * get flightident
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  string
        */
        public function getFlightIdent() {
            return $this->flightIdent;
        }

        /**
        * get origin
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  string
        */
        public function getOrigin() {
            return $this->origin;
        }

        /**
        * get destination
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  string
        */
        public function getDestination() {
            return $this->destination;
        }

        /**
        * get airport city
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  string
        */
        public function getAirportCity() {
            return $this->airportCity;
        }
        
        /**
        * get airport description
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  string
        */
        public function getAirportDescription() {
            return $this->airportDescription;
        }

        /**
        * get departuretime
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  datetime
        */
        public function getDeparturetime() {
            return $this->departuretime;
        }

        /**
        * get arrivaltime
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  datetime
        */
        public function getArrivaltime() {
            return $this->arrivaltime;
        }

        /**
        * get aircrafttype
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  string
        */
        public function getAircrafttype() {
            return $this->aircrafttype;
        }

        /**
        * get speed
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  integer
        */
        public function getSpeed() {
            return $this->speed;
        }

        /**
        * get altitude
        * 
        * @author  Dario Kuster
        * @version 27.01.2017
        * 
        * @return  integer
        */
        public function getAltitude() {
            return $this->altitude;
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
