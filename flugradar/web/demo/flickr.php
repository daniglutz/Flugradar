<?php
 
	$api_key = '29c98f2427784d7e3e77dae6653ba825';
	
	$tag = 'Zürich,City,landscape';
	$perPage = 5;
	$url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search';
	$url.= '&api_key='.$api_key;
	$url.= '&tags='.urlencode($tag)."&tag_mode=all";
	$url.= '&per_page='.$perPage;
	$url.= '&format=json';
	$url.= '&nojsoncallback=1';
	
	$response = json_decode(file_get_contents($url));
	$photo_array = $response->photos->photo;
	
	foreach($photo_array as $single_photo)
	{
	 
		$farm_id = $single_photo->farm;
		$server_id = $single_photo->server;
		$photo_id = $single_photo->id;
		$secret_id = $single_photo->secret;
		$size = 'q';
		
		$title = $single_photo->title;
		
		$photo_url = 'http://farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_'.$size.'.'.'jpg';
		
		print "<img title='".$title."' src='".$photo_url."' />";
	}
 
?>