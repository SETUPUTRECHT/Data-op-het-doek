<?php

class dodClass extends cmsClass{

	public $user;
	
	public function __construct() {
		$user = $this->getUserhash();
	}
	
	/* Get the userhash from the "dodvoter" cookie	*/
	public function getUserhash(){
		$getCookie = $this->readCookie("dodvoter");
		
		if($getCookie['succes']){
			$output = $getCookie;
		}else{
			$hash = $this->randomHash(25);
			if($this->writeCookie("dodvoter", $hash)){
				$output['succes'] = true;
				$output['content'] = $hash;
			}else{
				$output['succes'] = false;
			}
		}
		
	return($output);
	}
	
	/* Get voter by cookie hash */
	public function getVoter($hash){
		
		$getVoter = $this->query('core', "SELECT * FROM `voter` WHERE `hash` = '".$hash."' ORDER BY `id` DESC LIMIT 1");
		
		if($getVoter['succes'] && !empty($getVoter[0]['stoelnummer'])){ //&& !empty($getVoter[0]['rijnummer']) 
			if((time()+3570) > strtotime($getVoter[0]['timestamp'])){
				$updateVoter = $this->updateVoter($hash);
			}
			$output = $getVoter;
			$output['status'] = 'existing';
			
		}else{
			$output = $this->registerVoter($hash);
			$output['status'] = 'new';
			
		}
		
	return($output);
	}
	
	/* Maak een nieuwe voter record in de database */
	public function registerVoter($hash){
		if(isset($_POST['stoelnummer']) ){ //isset($_POST['rijnummer']) && 
		
			$this->log("New voter registered"); //".$_POST['rijnummer']."
			$output = $this->query('core', "INSERT INTO `voter` (`id`, `hash`, `rijnummer`, `stoelnummer`, `timestamp`) VALUES (NULL, '".$hash."', '1', '".$_POST['stoelnummer']."', '".$this->time2mysql((time()+3600))."');");
		
		}
		
	return($output);
	}
	
	/* Update the voter record in de database (extend the session with 1 hour) */
	public function updateVoter($hash){
		//$this->log("Voter registered 30 seconds ago. Extend registration 3600 sec.");
		$output = $this->query('core', "UPDATE `voter` SET `timestamp` = '".$this->time2mysql((time()+3600))."' WHERE `voter`.`hash` = '".$hash."' ;");
		
	return($output);
	}
	
	/* Get votes */
	public function getVotes($voterhash, $voteSettings){
		//$this->log("Requesting votes for user: ".$voterhash);
		$getVotes = $this->query('core', "SELECT * FROM `vote` WHERE `voterHash` = '".$voterhash."' ORDER BY projectID, category, timestamp DESC");
		if($getVotes['succes']){
			array_shift($getVotes);
			
			//$votes = array();
			foreach($voteSettings['category'] as $i => $setting){
				//echo $setting;
				$votes[$setting] = array();
			}
			
			$currProj = 0;
			$currCat = 0;
			
			foreach($getVotes as $i => $row){
				
				if($row['projectID'] != $currProj || $row['category'] != $currCat){
					array_push($votes[$row['category']], $row);
				}
				
				$currProj = $row['projectID'];
				$currCat = $row['category'];
			}
			
			$output['succes'] = true;
			$output['votes'] = $votes;
		}else{
			$output['succes'] = false;
		}
		
	return($output);
	}
	
	/* Get votes */
	public function getVoteforProject($votes, $project){
		array_shift($votes);
		//echo '<pre>'.print_r($votes, true).print_r($project, true).'</pre>';
		//$output = false;
		
		foreach($votes['votes'] as $i => $category){
			
			foreach($category as $i => $vote){
				
				if($vote['projectID'] == $project['id']){
					//echo $vote['category'];
					$projectvotes[$vote['category']] = $vote['rating'];
					break;
				}
				
			}

		}
		
		if(count($projectvotes) > 0){
			$output['succes'] = true;
			$output['votes'] = $projectvotes;
		}else{
			$output['succes'] = false;
		}
		
	return($output);
	}
	
	/* Maak een nieuwe vote record in de database */
	public function doVote($voterhash, $rating, $projectID, $category){
		
		$output = $this->query('core', "INSERT INTO `vote` ( `id` , `projectID`, `category` , `rating` , `voterHash` , `timestamp` ) VALUES ( NULL , '".$projectID."', '".$category."', '".$rating."', '".$voterhash."', NULL );");
		
		$getProject = $this->query('core', "SELECT `naam` FROM `project` WHERE `id`=".$projectID);
		if($getProject['succes']){
			$projectNaam = '#'.$projectID.': "'.$getProject[0]['naam'].'"';
		}else{
			$projectNaam = $projectID;
		}
		
		$this->log("Project ".$projectNaam." krijgt ".$rating.' voor '.$category.'');
		
	return($output);
	}
	
	/* Get projecten */
	public function getProjects(){
		//$this->log("Requesting projectlist.");
		$output = $this->query('core', "SELECT * FROM `project` WHERE `active` = 1 ORDER BY `volgnr`, `id` ASC");
		
	return($output);
	}
	
	/* Add a log record in de database */
	public function log($msg){
		
		$output = $this->query('core', "INSERT INTO `log` (`id`, `msg`, `timestamp`) VALUES (NULL, '".$msg."', NULL);");
		
	return($output);
	}
	
	public function destroyUser(){
		
		$output = $this->killCookie("dodvoter");
		
	return($output);
	}
	
	function readCookie($cookiename){
		if(isset($_COOKIE[$cookiename]) && !empty($_COOKIE[$cookiename])){
			$output['succes'] = true;
			$output['content'] = $_COOKIE[$cookiename];
		}else{
			$output['succes'] = false;
		}
		
		return($output);
	}
	
	function killCookie($cookiename){
		$timestamp = time() - 432000;
		if(setcookie($cookiename,"",$timestamp,'/')){
			$output = true;
		}else{
			$output = false;
		}
		
		return($output);
	}
	
	function writeCookie($cookiename, $data){
		$timestamp = time() + 86400;
		$data['data'] = $data;
		
		if(setcookie($cookiename, $data, $timestamp, '/')){
			$output = true;
		}else{
			$output = false;
		}
		
		return($output);
	}

}

?>