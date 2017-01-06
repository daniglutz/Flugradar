<?php
	
    /** ** functions ** */
    require_once('functions.php');
    
	/**
	* mysql class
	* 
	* - {@link __construct}: constructor
	* - {@link query}: run query and error handling
	* - {@link getError}: get error message
	* 
	* @name    /classes/Mysql.class.php
	* @author  Dario Kuster
	* @version 19.12.2016
	*/
	class Mysql extends mysqli
	{
		/**
		* connect
		* @access protected
		* @var    array
		*/
		protected static $connect;
		
		/**
		* constructor
		* 
		* @access  public
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
		* @access  public
		* @author  Dario Kuster
		* @version 28.11.2016
		* 
		* @param   string $sql query to run
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
		* @access  private
		* @author  Dario Kuster
		* @version 28.11.2016
		* 
		* @return  string detail error message
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
	}
	
?>
