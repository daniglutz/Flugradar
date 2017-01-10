<?php
	
	/**
	* flightaware class
	* 
	* - {@link __construct}: constructor
	* - {@link request}:     actual heavy lifter
	* - {@link __call}:      calls for requests
	* 
	* @name    classes/FlightAware.class.php
	* @author  Dario Kuster
	* @version 28.11.2016
	*/
	class FlightAware {
		
		/**
		* config
		* @access private
		* @var    array
		*/
		private $config;
		
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
			
			// link config-file
			$configFile = "./configs/config.ini";
			
			if(file_exists($configFile)) {
				$config = parse_ini_file($configFile, true);
				$this->config = $config['flightaware'];
			}
			else {
				throw new Exception('FlightAware: Configuration File Missing!');
			}
		}
        
		/**
		* actual heavy lifter
		*
		* @author  Dario Kuster
		* @version 28.11.2016
		*
		* @param   string $function
		* @param   array  $parameters
		* @return  string
		*/
		public function request($function, array $parameters) {
            
			$options = array(
				'http' =>
					array(
						'method'  => 'POST',
						'header'  => 'Content-type: application/x-www-form-urlencoded',
						'content' => http_build_query($parameters)
					)
			);

			$context  = stream_context_create($options);
			$url = "http://".$this->config['username'].":".$this->config['apiKey']."@".
				$this->config['requestURL'].$function;

			$results = file_get_contents($url, FALSE, $context);

			if(in_array('HTTP/1.1 200 OK', $http_response_header)) {
				return json_decode($results, TRUE);
			} else {
				return array('error'=>$http_response_header);
			}
		}

		/**
		* calls for requests
        *
		* @author  Dario Kuster
		* @version 28.11.2016
		*
		* @param   string $function
		* @param   array  $parameters
		* @return  array
		*/
		public function __call($function, $parameters) {
			return $this->request($function, $parameters[0]);
		}
	}
	
?>