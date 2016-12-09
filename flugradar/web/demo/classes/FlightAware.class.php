<?php
	
	/**
	* Class for interacting with FlightAware's API
	* 
	* - {@link __construct}: XXXX
	* - {@link request}: XXXX
	* - {@link __call}: XXXX
	* 
	* @package Flugradar
	* @name    /classes/FlightAware.class.php
	* @author  Dario Kuster
	* @version 28.11.2016
	*/
	class FlightAware {
		
		/**
		* Config
		* @access private
		* @var    array
		*/
		private $config;
		
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
		public function __construct() {
			
			// link config-file
			$config_file = "./configs/config.ini";
			
			if(file_exists($config_file)) {
				$config = parse_ini_file($ini, true);
				$this->config = $config['flightaware'];
			}
			else {
				throw new Exception('FlightAware: Configuration File Missing!');
			}
		}

		/**
		* The actual heavy lifter
		*
		* @access  public
		* @author  Dario Kuster
		* @version 28.11.2016
		*
		* @param string $function   Name of the flightaware api call
		* @param array  $parameters Key/Value pair of the parameters
		* @return string JSON results
		*/
		public function request($function, array $parameters)
		{
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

			if(in_array('HTTP/1.1 200 OK', $http_response_header)){
				return json_decode($results, TRUE);
			}else{
				return array('error'=>$http_response_header);
			}
		}

		/**
		* Magic calls "$instantiatedFlightAware->[API method name]" for requests
		*
		* @param string $function      Called method name
		* @param array $parameters     Arguments passed to the method
		* @return array                Decoded results from API
		*/
		public function __call($function, $parameters) {
			
			return $this->request($function, $parameters[0]);
		}
	}
	
?>