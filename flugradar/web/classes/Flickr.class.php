<?php

    /**
    * flickr class
    * 
    * - {@link __construct}:  constructor
    * - {@link searchPhotos}: search photos
    * - {@link getPhotos}:    get photos
    * 
    * @name    classes/Flickr.class.php
    * @author  Dario Kuster
    * @version 19.12.2016
    */
    class Flickr {

        // variable
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

            // if conig-file exists
            if(file_exists($configFile)) {
                $config = parse_ini_file($configFile, true);
                $this->config = $config['flickr'];

                // error
                if(!isset($this->config)) {
                    throw new Exception("Flickr: Zugriffsdaten in Config-File fehlen!");
                }
            }
            else {
                throw new Exception("Flickr: Config-File fehlt!");
            }
        }

        /**
        * search photos
        *
        * @author  Dario Kuster
        * @version 28.11.2016
        *
        * @param   string  $tags
        * @param   integer $perPage
        * @return  array
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
                // return only the data for the photos
                $photos = $data['photos']['photo'];

                // return photo array
                return $photos;
            } else {
                // return false
                return false;
            }
        }

        /**
        * get photos
        * 
        * @author  Dario Kuster
        * @version 28.11.2016
        * 
        * @param   array  $photos
        * @param   string $size
        * @return  string
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