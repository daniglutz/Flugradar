<?php

	/**
	* user class
	* 
	* - {@link __construct}:        constructor
	* - {@link getId}:              get id
    * - {@link getUsername}:        get username
    * - {@link getPassword}:        get password
    * - {@link getAdmin}:           get admin
    * - {@link getStandardAirport}: get standard airport
    * - {@link getNumberEntries}:   get number entries
    * - {@link getRefreshTime}:     get refresh time
	* 
	* @name    classes/User.class.php
	* @author  Dario Kuster
	* @version 27.01.2017
	*/
    class User {
        // Variables
        private $id;
        private $username;
        private $password;
        private $admin;
        private $standardAirport;
        private $numberEntries;
        private $refreshTime;

		/**
		* constructor
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  void
		*/
        function __construct($id, $username, $password, $admin, $standardAirport, $numberEntries, $refreshTime) {
            $this->id = $id;
            $this->username = $username;
            $this->password = $password;
            $this->admin = $admin;
            $this->standardAirport = $standardAirport;
            $this->numberEntries = $numberEntries;
            $this->refreshTime = $refreshTime;
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
		* get username
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  string
		*/
        public function getUsername() {
            return $this->username;
        }
        
		/**
		* get password
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  string
		*/
        public function getPassword() {
            return $this->password;
        }
        
		/**
		* get admin
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  integer
		*/
        public function getAdmin() {
            return $this->admin;
        }
        
		/**
		* get standard airport
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  string
		*/
        public function getStandardAirport() {
            return $this->standardAirport;
        }
        
		/**
		* get number entries
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  integer
		*/
        public function getNumberEntries() {
            return $this->numberEntries;
        }
        
		/**
		* get refresh time
		* 
		* @author  Dario Kuster
		* @version 27.01.2017
		* 
		* @return  integer
		*/
        public function getRefreshTime() {
            return $this->refreshTime;
        }
    }