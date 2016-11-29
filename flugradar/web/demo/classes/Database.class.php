<?php
	
	/**
	* Datenbank-Klasse
	* 
	* - {@link Database}: Behandelt MySQL-Abfragen
	* 
	* @package Flugradar
	* @name    /classes/Database.class.php
	* @author  Dario Kuster
	* @version 28.11.2016
	*/
	
	
	/**
	* Behandelt MySQL-Abfragen
	* 
	* - {@link __construct}: query ausführen und auf Fehler überprüfen
	* - {@link query}: query ausführen und auf Fehler überprüfen
	* - {@link error}: ermittelt, ob ein Fehler aufgetreten ist
	* - {@link _get_error}: gibt die Fehlerinformationen ausführlich zurück
	* - {@link _abort}: gibt die Fehlermeldung aus und bricht ab
	* 
	* @package Flugradar
	* @author  Dario Kuster
	* @version 28.11.2016
	*/
	class Database
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
		* Fehlernummer
		* @access private
		* @var    integer
		*/
		private $_errno = 0;
		/**
		* Fehlermeldung
		* @access private
		* @var    string
		*/
		private $_error = '';
		
		/**
		* query ausführen und auf Fehler überprüfen
		* 
		* @access  public
		* @author  Dario Kuster
		* @version 28.11.2016
		* 
		* @return  void
		*/
		public function __construct()
		{
			if(!isset(self::$connect)) {
				$ini = "./configs/config.ini";
				
				if(file_exists($ini)) {
					$config = parse_ini_file($ini, true);
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
			// connect to the database
			$connect = $this->__construct();
			
			// Leerzeichen des Query abschneiden und diesen in der Klasse speichern
			$this->_sql = trim($sql);
			
			// ausführen
			$result = $connect->query($this->_sql);
			
			// wenn fehler aufgetreten
			if($this->error())
			{
				$this->_abort();
			}
			
			return $result;
		}
		
		/**
		* ermittelt, ob ein Fehler aufgetreten ist
		* 
		* @access  protected
		* @author  Dario Kuster
		* @version 28.11.2016
		* 
		* @return  bool Fehler aufgetreten?
		*/
		protected function error()
		{
			$connect = $this->__construct();
			return $connect->error;
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
		private function _get_error()
		{
			if($this->error())
			{
				$str = "
				<div class='alert alert-danger' role='alert'>
					<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>
					<span class='sr-only'>Error:</span>
					<b>MySQL Error (".$this->_errno.")</b><br />
					".$this->_error."
				</div>";
			}
			else
			{
				$str = 0;
			}
			
			return $str;
		}
		
		/**
		* gibt die Fehlermeldung aus und bricht ab (inkl. footer.php)
		* 
		* Gibt die Fehlermeldung aus,
		* bricht allenfalls die Transaktion ab,
		* bindet den Footer ein und bricht das Script ab.
		* 
		* @access  private
		* @author  Dario Kuster
		* @version 28.11.2016
		* 
		* @return  void
		*/
		private function _abort()
		{
			$connect = $this->__construct();
			
			// fehlernummer
			$this->_errno = $connect->errno;
			// ferhlertext
			$this->_error = $connect->error;
			// fehler ausführlich speichern
			$string = $this->_get_error();
			
			if($string)
			{
				// Fehlermeldung ausgeben
				echo $string;
			}
		}
	}
	
	
?>
