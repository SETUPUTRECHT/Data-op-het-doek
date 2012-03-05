<?php

class filterClass extends mysqlClass{
	
	public function __construct() {
		
	}
	
	//Apply a Filter file to get content from a cached file
	public function applyFilter($name, $fileHash){
	
	if($fileHash){
		//Read the specified file
		$file = $this->getFile($fileHash);
	}else{
		//No hash is specified so there's no way of reading te right file
		$file['succes'] = false;
	}
	//File is retrieved
	if($file['succes']){
	
		//Include phpQuery
		require_once('classes/phpQuery/phpQuery.php');
		
		//Create document from file and select document in phpQuery
		$doc = phpQuery::newDocument($file['content']);
		phpQuery::selectDocument($doc);
		
		//Include the filter
		include('filter/'.$name.'.php');
	
	//The specified file could not be read
	}else{
		$output['succes'] = false;
		$output['content'] = '';
	}
	
		return($output);
	}
	
	//Get file from the database
	public function getFile($hash){

	$getFile = $this->query('core', "SELECT * FROM `cachefile` WHERE `hash` = '".$hash."' LIMIT 1");
	if($getFile['succes']){
		$output = $this->read_from_file($getFile[0]['cachefile']);
	}else{
		$output = $getFile;
	}

	return($output);
	}
	
}

?>
