<?php

if(isset($_POST['action'])){
	
	switch($_POST['action']){
		
		case "doVote":
			
			$getUserhash = $dod->getUserhash();
			if($getUserhash['succes']){
				$getVoter = $dod->getVoter($getUserhash['content']);
				if($getVoter['succes']){
					if(strtotime($getVoter[0]['timestamp']) > time()){
						$doVote = $dod->doVote($getUserhash['content'], $_POST['rating'], $_POST['projectID'], $_POST['category']);
						if($doVote['succes']){
							$output['succes'] = true;
						}else{
							$output['succes'] = false;
							$output['err']['nr'] = 1;
							$output['err']['msg'] = 'Registering the vote failed.';
						}
					}else{
						$output['succes'] = false;
						$output['err']['nr'] = 2;
						$output['err']['msg'] = 'User session is expired';
					}
				}else{
					$output['succes'] = false;
					$output['err']['nr'] = 3;
					$output['err']['msg'] = 'Can\'t identify voter with database';
				}
			}else{
				$output['succes'] = false;
				$output['err']['nr'] = 4;
				$output['err']['msg'] = 'Can\'t get userhash from cookie';
			}
			//$output['succes'] = true;
			//$output['hash'] = $getUserhash['content'];
			
		break;
		
		case "getLog":
		
		$getLog = $cms->query("core", "SELECT * FROM `log` ORDER BY `id` DESC LIMIT 30");
		if($getLog['succes']){
			array_shift($getLog);
			$logList = '';
			foreach($getLog as $i => $logitem){
				//Begrijpelijk tijd van maken
				$friendlyDate = $cms->friendlyDate(strtotime($logitem['timestamp']));
				
				//List html
				$logList .= '<li>'.$friendlyDate.': '.$logitem['msg'].'</li>';
			}

		}
		
		$output['succes'] = true;
		$output['loglist'] = $logList;
		
		break;
		
		default:
			$output['succes'] = false;
		break;
	}

}else{
	$output['succes'] = false;
}

echo json_encode($output);

?>