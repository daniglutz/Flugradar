<?php
	
	/**
	* MySQL-Klassen des Intranet
	* 
	* - {@link mysql}: Behandelt MySQL-Abfragen
	* - {@link t_mysql}: Behandelt MySQL-Transaktionen
	* 
	* @package INTRANET
	* @name    /usr/share/php5/classes.mysql.php
	* @author  Raphael Horber
	* @version 23.08.2012
	* @see     /usr/share/php5/classes.mssql.php
	*/
	
	
	/**
	* Behandelt MySQL-Abfragen
	* 
	* - {@link __construct}: query ausf�hren und auf Fehler �berpr�fen
	* - {@link query}: query ausf�hren und auf Fehler �berpr�fen
	* - {@link numRows}: gibt die Anzahl Datens�tze des Ergebnisses zur�ck (wie mysql_num_rows())
	* - {@link fetch}: liefert einen Datensatz als assoziativen und numerischen Array (wie mysql_fetch_array())
	* - {@link result}: liefert das Ergebnis einer Spalte (wie mysql_result(..., 0, ...))
	* - {@link order}: sortiert die aktuelle Abfrage und f�hrt sie allenfalls aus (sorting)
	* - {@link limit}: limitiert die aktuelle Abfrage und f�hrt sie immer aus (paging)
	* - {@link error}: ermittelt, ob ein Fehler aufgetreten ist
	* - {@link _get_error}: gibt die Fehlerinformationen ausf�hrlich zur�ck
	* - {@link _abort}: gibt die Fehlermeldung aus und bricht ab (inkl. footer.php)
	* 
	* existiert $_GET['debug'], wird/werden die Abfrage/n ausgegeben
	* 
	* @package INTRANET
	* @author  Raphael Horber
	* @version 23.08.2012
	* @see     mssql
	*/
	class mysql
	{
		/**
		* Speichert das Resultat
		* @access public
		* @var    integer
		*/
		public $result = 0;
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
		* query ausf�hren und auf Fehler �berpr�fen
		* 
		* @access  public
		* @author  Raphael Horber
		* @version 10.08.2012
		* 
		* @param   string $sql abfrage, die ausgef�hrt werden soll
		* @return  void
		*/
		public function __construct($sql)
		{
			// ausf�hren
			$this->query($sql);
		}
		
		/**
		* query ausf�hren und auf Fehler �berpr�fen
		* 
		* @access  public
		* @author  Raphael Horber
		* @version 10.08.2012
		* 
		* @param   string $sql abfrage, die ausgef�hrt werden soll
		* @return  void
		*/
		public function query($sql)
		{
			// Leerzeichen des Query abschneiden und diesen in der Klasse speichern
			$this->_sql = trim($sql);
			
			// ausf�hren
			$this->result = @mysql_query($this->_sql);
			
			// resultat vorhanden / fehler aufgetreten
			if(!$this->result)
			{
				$this->_abort();
			}
			
			// debug-option aktiviert?
			if(isset($_GET['debug']))
			{
				echo "Abfrage:<br />\n<pre>".$this->_sql."</pre><br />\n";
			}
		}
		
		/**
		* gibt die Anzahl Datens�tze des Ergebnisses zur�ck (wie mysql_num_rows())
		* 
		* @access  public
		* @author  Raphael Horber
		* @version 17.08.2012
		* 
		* @return  integer|boolean Liefert Anzahl Ergebnisdatens�tze oder false
		*/
		public function numRows()
		{
			if($this->error())
			{
				return false;
			}
			else
			{
				return mysql_num_rows($this->result);
			}
		}
		
		/**
		* liefert einen Datensatz als assoziativen und numerischen Array (wie mysql_fetch_array())
		* 
		* @access  public
		* @author  Raphael Horber
		* @version 17.08.2012
		* 
		* @return  array|string Ergebnisdatensatz als Array oder eine Fehlermeldung.
		*/
		public function fetch()
		{
			if($this->error())
			{
				echo "Es trat ein Fehler auf. Bitte �berpr�fen sie ihre MySQL-Abfrage.";
				$return = null;
			}
			else
			{
				$return = mysql_fetch_array($this->result);
			}
			
			return $return;
		}
		
		/**
		* liefert das Ergebnis einer Spalte (wie mysql_result(..., 0, ...))
		* 
		* @access  public
		* @author  Raphael Horber
		* @version 17.08.2012
		* 
		* @param   integer|string $index spalte die zur�ckzugeben werden soll
		* @return  mixed          Entsprechender Wert
		*/
		public function result($index)
		{
			if($this->error())
			{
				echo "Es trat ein Fehler auf. Bitte �berpr�fen sie ihre MySQL-Abfrage.";
				$return = null;
			}
			elseif(!$this->numRows())
			{
				echo "
				Die Abfrage lieferte ein leeres Resultat zur�ck (d.h. null Zeilen).<br />
				Die Methode result() kann nicht ausgef�hrt werden.<br />
				Abfrage:<br /><pre>".$this->_sql."</pre>";
				$return = null;
			}
			else
			{
				$return = mysql_result($this->result, 0, $index);
			}
			
			return $return;
		}
		
		/**
		* sortiert die aktuelle Abfrage und f�hrt sie allenfalls aus (sorting)
		* 
		* @access  public
		* @author  Raphael Horber
		* @version 10.08.2012
		* 
		* @param   string       $order zus�tzlicher order-by-String (inkl. ' ORDER BY')
		* @param   boolean      $run   Abfrage nochmals ausf�hren j/n; dies ist nicht n�tig,
		*                              wenn ein paging folgt (default: false)
		* @return  void|boolean im Fehlerfall false
		*/
		public function order($order, $run = false)
		{
			// fehlendes ORDER BY abfangen
			if(strpos($order, ' ORDER BY') === false)
			{
				echo "Fehler bei Sortierung! ' ORDER BY' fehlt!";
				return false;
			}
			else
			{
				// ** falls ORDER BY schon in der Abfrage ist **
				$pos_order = strrpos($this->_sql, 'ORDER BY');
				$pos_limit = strrpos($this->_sql, 'LIMIT');
				
				if($pos_order !== false)
				{
					// sofern kein FROM hinter ORDER BY ist, ist es nicht in einem sub-select => behandeln
					if(strpos(substr($this->_sql, $pos_order), 'FROM') === false)
					{
						// falls LIMIT vorhanden (hinter ORDER BY), LIMIT wieder anh�ngen
						if($pos_limit !== false AND $pos_limit > $pos_order)
						{
							$limit = substr($this->_sql, $pos_limit);
						}
						
						// Teil bis ORDER BY speichern (urspr�ngliches ORDER BY wird entfernt)
						$this->_sql = substr($this->_sql, 0, $pos_order);
					}
				}
				elseif($pos_limit !== false)
				{
					// sofern kein FROM hinter LIMIT ist, ist es nicht in einem sub-select => behandeln
					if(strpos(substr($this->_sql, $pos_limit), 'FROM') === false)
					{
						$limit = substr($this->_sql, $pos_limit);
						
						// teil bis LIMIT speichern
						$this->_sql = substr($this->_sql, 0, $pos_limit);
					}
				}
				
				// ORDER BY anh�ngen und evtl. LIMIT anh�ngen
				$this->_sql .= $order;
				$this->_sql .= (isset($limit)) ? " ".$limit : "";
				
				// allenfalls erneut ausf�hren
				if($run === true)
				{
					$this->query($this->_sql);
				}
			}
		}
		
		/**
		* limitiert die aktuelle Abfrage und f�hrt sie immer aus (paging)
		* 
		* @access  public
		* @author  Raphael Horber
		* @version 12.04.2011
		* 
		* @param   string       $limit zus�tzlicher limit-string (inkl. ' LIMIT')
		* @return  void|boolean im Fehlerfall false
		*/
		public function limit($limit)
		{
			// fehlendes LIMIT abfangen
			if(strpos($limit, ' LIMIT') === false)
			{
				echo "Fehler bei Limitierung! ' LIMIT' fehlt!";
				return false;
			}
			else
			{
				// ** falls LIMIT schon in der abfrage enthalten ist **
				$pos = strrpos($this->_sql, 'LIMIT');
				
				if($pos !== false)
				{
					// sofern kein FROM hinter LIMIT ist, ist es nicht in einem sub-select
					if(strpos(substr($this->_sql, $pos), 'FROM') === false)
					{
						// teil bis LIMIT speichern (urspr�ngliches LIMIT wird entfernt)
						$this->_sql = substr($this->_sql, 0, $pos);
					}
				}
				
				// LIMIT anh�ngen
				$this->_sql .= $limit;
				
				// erneut ausf�hren
				$this->query($this->_sql);
				
				// ** falls wir nicht auf der Seite 1 sind, pr�fen ob ein Ergebnis vorhanden ist **
				if(strpos($limit, " LIMIT 0,") === false AND $this->numRows() == 0)
				{
					$href = str_replace('&page='.$_GET['page'], '', $_SERVER['REQUEST_URI']);
					redirect(0, $href."&page=0");
				}
			}
		}
		
		/**
		* ermittelt, ob ein Fehler aufgetreten ist
		* 
		* @access  protected
		* @author  Raphael Horber
		* @version 17.08.2012
		* 
		* @return  bool Fehler aufgetreten?
		*/
		protected function error()
		{
			// Result-ID in einer tmp-Variablen speichern
			$tmp = $this->result;
			
			// Variable in boolean umwandeln
			$tmp = (bool)$tmp;
			
			// Variable invertieren
			$tmp = !$tmp;
			
			// und zur�ckgeben
			return $tmp;
		}
		
		/**
		* gibt die Fehlerinformationen ausf�hrlich zur�ck
		* 
		* @access  private
		* @author  Raphael Horber
		* @version 23.08.2012
		* 
		* @return  string Ausf�hrliche Fehlermeldung
		*/
		private function _get_error()
		{
			if($this->error())
			{
				$str  = "
				Abfrage:<br /><pre>".$this->_sql."</pre><br />
				Antwort:".$this->_error."<br />
				Fehlercode: ".$this->_errno;
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
		* @author  Raphael Horber
		* @version 23.08.2012
		* 
		* @return  void
		*/
		private function _abort()
		{
			// fehlernummer
			$this->_errno = mysql_errno();
			// ferhlertext
			$this->_error = mysql_error();
			// fehler ausf�hrlich speichern
			$string = $this->_get_error();
			
			if($string)
			{
				// bei Transaktion abbrechen
				if(get_class($this) == 't_mysql')
				{
					mysql_query("ROLLBACK");
				}
				
				// Fehlermeldung ausgeben
				echo $string;
				
				// Falls Intranet oder FedERP (wird auch vom QS-Portal verwendet)
				if(isset($_SERVER['APPLICATION']))
				{
					/** ** Sauberer Abschluss: Footer einbinden (in Methode _abort) ** */
					include 'footer.php';
				}
				
				// Script-Abbruch
				die();
			}
		}
	}
	
	
	/**
	* Behandelt MySQL-Transaktionen
	* 
	* - {@link __construct}: startet die Transaktion
	* - {@link __destruct}: Transaktion best�tigen
	* 
	* existiert $_GET['debug'], wird/werden die Abfrage/n ausgegeben
	* 
	* @package INTRANET
	* @author  Raphael Horber
	* @version 17.08.2012
	* @see     t_mssql
	*/
	class t_mysql extends mysql
	{
		/**
		* Konstruktor: startet die Transaktion
		* 
		* @access  public
		* @author  Raphael Horber
		* @version 17.08.2012
		* 
		* @return  void
		*/
		public function __construct()
		{
			// Transaktion starten
			$this->query("START TRANSACTION");
		}
		
		/**
		* Destruktor: Transaktion best�tigen
		* 
		* @access  public
		* @author  Raphael Horber
		* @version 17.08.2012
		* 
		* @return  void
		*/
		public function __destruct()
		{
			// falls kein Fehler aufgetreten, Transaktion best�tigen
			if($this->error() === false)
			{
				$this->query("COMMIT");
			}
		}
	}
	
?>
