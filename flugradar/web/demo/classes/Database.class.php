<?php
	
	/**
	* Datenbank-Klasse
	* 
	* - {@link __construct}: query ausführen und auf Fehler überprüfen
	* - {@link query}: query ausführen und auf Fehler überprüfen
	* - {@link getError}: gibt die Fehlerinformationen ausführlich zurück
	* 
	* @package Flugradar
	* @name    /classes/Database.class.php
	* @author  Dario Kuster
	* @version 28.11.2016
	*/
	class Database extends mysqli
	{
		/**
		* Verbindung
		* @access protected
		* @var    object
		*/
		protected static $connect;
		/**
		* SQL-Abfrage
		* @access private
		* @var    string
		*/
		private $_sql = '';
		
		/**
		* constructor
		* 
		* @access  public
		* @author  Dario Kuster
		* @version 28.11.2016
		* 
		* @return  void
		* @throws  Exception when the script can't find the config-file
		*/
		public function __construct()
		{
			if(!isset(self::$connect)) {
				// link config-file
				$config_file = "./configs/config.ini";
				
				if(file_exists($config_file)) {
					$config = parse_ini_file($config_file, true);
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
			
			return self::$connect;
		}
		
		/**
		* query ausführen und auf Fehler überprüfen
		* 
		* @access  public
		* @author  Dario Kuster
		* @version 28.11.2016
		* 
		* @param   string $sql abfrage, die ausgeführt werden soll
		* @return  void
		*/
		public function query($sql)
		{
			// Leerzeichen des Query abschneiden und diesen in der Klasse speichern
			$this->_sql = trim($sql);
			
			// ausführen
			$result = self::$connect->query($this->_sql);
			
			// wenn fehler aufgetreten
			$this->getError();
			
			return $result;
		}
		
		/**
		* gibt die Fehlerinformationen ausführlich zurück
		* 
		* @access  private
		* @author  Dario Kuster
		* @version 28.11.2016
		* 
		* @return  string Ausführliche Fehlermeldung
		*/
		private function getError()
		{
			if(self::$connect->error)
			{
				$error_str = "
				<div class='alert alert-danger' role='alert'>
					<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
					<span class='sr-only'>Error:</span>
					<b>MySQL Error (".self::$connect->errno.")</b><br />
					".self::$connect->error."
				</div>";
			}
			else
			{
				$error_str = "";
			}
			
			return $error_str;
		}
	}
	
?>
