<?php

//Classes includen
include_once("../classes/inc.functions.php");
include_once("../classes/inc.mysql.php");
include_once("../classes/inc.filter.php");
include_once("../classes/inc.cms.php");
include_once("../classes/inc.login.php");
include_once("../classes/inc.mobify.php");
include_once("../classes/inc.dod.php");

//Initiate Makemob class
$cms = new cmsClass;
$login = new loginClass;
$mob = new mobifyClass;
$dod = new dodClass;

$getVotes = $cms->query("core", "SELECT * FROM vote ORDER BY voterHash, id ASC");
$voteFeed = array();

if($getVotes['succes']){

$currentVoterhash = '';

foreach($getVotes as $i => $vote){
	
	$voteholder = $vote;
	
	//print_r($vote['projectID']);
	$getVolgnr = $cms->query("core", "SELECT `volgnr` FROM `project` WHERE `id` =".$vote['projectID']." AND `active` =1");
	if($getVolgnr['succes']){
		//print_r($getVolgnr);
		$voteholder['volgnr'] = $getVolgnr[0]['volgnr'];
	}
	
	if($vote['voterHash'] != $currentVoterhash){
		
		$getVoter = $cms->query("core", "SELECT * FROM voter WHERE hash = '".$vote['voterHash']."'");
		
		if($getVoter['succes']){
			$voterData['voter'] = $getVoter[0];
			$voterData['votes'][0] = $voteholder;
		}
		
		if(count($voteFeed) > 0){
			array_push($voteFeed, $voterData);
		}else{
			$voteFeed[0] = $voterData;
		}
		
	}else{
		
		if(count($voteFeed) > 0){
			//echo count($voteFeed);
			array_push($voteFeed[(count($voteFeed)-1)]['votes'], $voteholder);
		}
		
	}
	
	$currentVoterhash = $vote['voterHash'];
}

}

echo json_encode($voteFeed);

//echo '<pre>'.print_r($voteFeed, true).'</pre>';


//$page['content'] = '<h3>Testingg...</h3>';

?>