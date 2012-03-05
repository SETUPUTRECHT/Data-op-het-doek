<?php

class makemobClass extends mysqlClass{
	
	public function __construct() {
		
	}

	//Add log entry
	public function addLog($action){
	$output = $this->query('core', "INSERT INTO `log` (`id` , `action` , `datetime` ) VALUES ( NULL , '".$action."', CURRENT_TIMESTAMP );");
	}
	
	//Retrieve log entries
	public function getLog($amount = 30){
	$output = $this->query('core', "SELECT * FROM `log` ORDER BY `id` DESC LIMIT ".$amount);
	}

	//Retrieve the feed
	public function getFeeds($amount = 20){
	$this->addLog("Retrieving feed");
	
	$q = $this->query('core', "SELECT * FROM `feed` ORDER BY `datetime` DESC LIMIT ".$amount);
	
	if($q['succes']){
		$output = $q;
	}else{
		$output['succes'] = false;
	}

	return($output);
	}
	
	//If a cachefile doesn't exist
	public function noCachefile($hash){

	$q = $this->query('core', "SELECT * FROM `cachefile` WHERE `hash` = '".$hash."'");

	if($q['succes']){
		$output = false;
	}else{
		$output = true;
	}
	
	return($output);
	}
	
	//Add an url that should be monitored to the feed
	public function addFeed($url, $interval = 180){
	$hash = $this->randomHash(15);

	$q = $this->query('core', "INSERT INTO `feed` ( `id` , `feedurl` , `hash`, `interval` , `datetime` )
	VALUES ( NULL , '".$url."', '".$hash."', '".$interval."', CURRENT_TIMESTAMP );");

	if($q['succes']){
		$this->addLog('Added new url: '.$url);
		$output['succes'] = true;
		$output['hash'] = $hash;
	}else{
		$output['succes'] = false;
	}

	return($output);
	}
	
	//Remove an entry from the feed
	public function removeFeed($hash){

	$q = $this->query('core', "DELETE FROM `feed` WHERE `hash` = '".$hash."'");

	if($q['succes']){
		$output['succes'] = true;
	}else{
		$output['succes'] = false;
	}

	return($output);
	}
	
	//Update the time of a feed entry
	public function updateFeed($hash){

	$output = $this->query('core', "UPDATE `feed` SET `datetime` = CURRENT_TIMESTAMP WHERE `feed`.`hash` = '".$hash."';");

	return($output);
	}
	
	//Update a file that's in the cache
	public function updateCachefile($hash, $url){
		
		$this->addLog("Downloading: ".$url);
		
		$html = $this->curl($url); //Aggregate page
		$save = $this->saveFile($hash, $html);

		if($save['succes']){
			$this->addLog("Succesfully saved ".$hash);
			$updatefeed = $this->updatefeed($hash);
			$output['succes'] = $updatefeed['succes'];
		}else{
			$output['succes'] = false;
		}

	return($output);
	}

	//Save a file to the database
	public function saveFile($hash, $content){

	$file = "cachefile/".$hash.".txt";
	$this->write_to_file($file, $content);
	
	$q = $this->query('core', "SELECT `hash` FROM `cachefile` WHERE `hash` = '".$hash."'");
	
	if($q['succes']){
		$output = $this->query('core', "UPDATE `cachefile` SET `datetime` = '".date("Y-m-d H:i:s" ,time())."', `cachefile` = '".$file."' WHERE `hash` = '".$hash."'");
	}else{
		$output = $this->query('core', "INSERT INTO `cachefile` (`id`, `hash`, `datetime`, `cachefile`) VALUES (NULL, '".$hash."', '".date("Y-m-d H:i:s" ,time())."', '".$file."');");
	}

	return($output);
	}
	
	//Write to a file
	public function write_to_file($file, $content){

	$fh = fopen($file, 'w') or die("can't open file");
	if(fwrite($fh, $content)){
		$output = true;
	}else{
		$output = false;
	}
	fclose($fh);

	return($output);
	}

	
	//Read a file
	public function read_from_file($file){

	$fh = fopen($file, 'r');
	$data = fread($fh, filesize($file));
	fclose($fh);
	if(!$data){
		$output['succes'] = false;
	}else{
		$output['succes'] = true;
		$output['content'] = $data;
	}

	return($output);
	}

	//Use CURL to download something
	public function curl($url){
	include_once("inc.curl.php");

	$c = new curl($url) ;
	$c->setopt(CURLOPT_FOLLOWLOCATION, true);

	$output = $c->exec();

	if($theError = $c->hasError()){
	  $output = $theError ;
	}

	//Close
	$c->close();

	return($output);
	}
	
}

?>
