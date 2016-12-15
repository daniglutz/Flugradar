<?php
	
	/**
	* Class for interacting with Flicker's API
	* 
	* - {@link __construct}: XXXX
	* - {@link searchPhotos}: XXXX
	* 
	* @package Flugradar
	* @name    /classes/Flickr.class.php
	* @author  Dario Kuster
	* @version 28.11.2016
	*/
	class Flickr {
		
		/**
		* Config
		* @access private
		* @var    array
		*/
		private $config;
		
		/**
		* Constructor
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
				$config = parse_ini_file($config_file, true);
				$this->config = $config['flickr'];
			}
			else {
				throw new Exception('Flickr: Configuration File Missing!');
			}
		}
		
		/**
		* Search Photos
		*
		* @access  public
		* @author  Dario Kuster
		* @version 28.11.2016
		*
		* @param   string  $tags    Tags for search
		* @param   integer $perPage Number of results
		* @return  array Array of photos
		*/
		public function searchPhotos($tags = '', $perPage = 5) {
			
			// construct the url
			$url  = 'https://api.flickr.com/services/rest/?method=flickr.photos.search';
			$url .= '&api_key='.$this->config['apiKey'];
			$url .= '&tags='.$tags;
			$url .= '&tag_mode=all';
			$url .= '&per_page='.$perPage;
			$url .= '&format=json';
			$url .= '&nojsoncallback=1';
			
			// get the results
			$data = json_decode(file_get_contents($url), true);
			$photos = array();
			
			// check if the status didn't fail
			if($data['stat'] != 'fail'){
				//return only the data for the photos
				$photos = $data['photos']['photo'];
				
				// return photo array
				return $photos;
			} else {
				// return false
				return false;
			}
		}
		
		/**
		* Get Photos
		* 
		* @access  public
		* @author  Dario Kuster
		* @version 28.11.2016
		* 
		* @param   array $photos Array of photos
		* @param   char  $size   Size of photos
		* @return  string Images tags
		*/
		public function getPhotos($photos, $size = 'q') {
			
			// initialize
			$images = "";
			
			// check if photos
			if(!empty($photos)) {
				foreach($photos as $photo){
					// construct image tag
					$images .= "
					<img
						src='http://farm".$photo['farm'].".static.flickr.com/".$photo['server']."/".$photo['id']."_".$photo['secret']."_".$size.".jpg'
						alt='".$photo['title']."'
						style='padding:0px 10px 10px 0px; float:left;'
					>";
				}
				
				// return images
				return $images;
			} else {
				// return false
				return false;
			}
		}
	}
	
?>