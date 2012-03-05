<?php

//Settings for voter
$voteSettings['type'] = 'bar';
$voteSettings['range'] = 20;
$voteSettings['category'][0] = "informatief";
$voteSettings['category'][1] = "cool";

//Check if user has cookie
$getUserhash = $dod->getUserhash();

if($getUserhash['succes']){
	
	//Identify voter
	$getVoter = $dod->getVoter($getUserhash['content']);
	
	if($getVoter['succes']){
		
		//if($getVoter['status'] == 'new'){
			
		//	header("Location: /intro");
			
		//}else{
		
		//Haal de projecten op
		$getProjects = $dod->getProjects();
		
		if($getProjects['succes']){
			array_shift($getProjects);
			
			// Start the projectlist
			$projectList = '<ul id="projecten" class="ui-corner-all" data-theme="c">
';
			
			//Get votes of the user
			$getVotes = $dod->getVotes($getUserhash['content'], $voteSettings);
			
			//Loop over the projects
			foreach($getProjects as $i2 => $project){
				
				if($getVotes['succes']){
					//array_shift($getVotes);
					//echo '<pre>'.print_r($getVotes, true).'</pre>';
					$getRating = $dod->getVoteforProject($getVotes, $project);
					
					//echo '<pre>'.print_r($getRating, true).'</pre>';
				}
				
				$ratingBtns = '';
				
				switch($voteSettings['type']){
					
					case "bar":
					
					foreach($voteSettings['category'] as $i => $cat){
						
						$ratingBtns .= '<div class="category '.$cat.'">
<label>'.$cat.'</label>';
						
					$i = 0;
					
					while($i < $voteSettings['range']){
						if($getRating['succes']){
							if(isset($getRating['votes'][$cat]) && ($i+1) <= $getRating['votes'][$cat]){
								$active = ' active';
							}else{
								$active = '';
							}
						}else{
							$active = '';
						}
						
						$ratingBtns .= '<a class="bar '.($i+1).$active.'"></a>
';
						$i++;
					}
					
					$ratingBtns .= '</div>
';
					
					}
					break;
					
					case "star":
					
					$ratingBtns = '<a class="bar 1"></a>
					<a class="bar 2"></a>
					<a class="bar 3"></a>
					<a class="bar 4"></a>
					<a class="bar 5"></a>
';
					break;
					
					case "slider":
					$ratingBtns = '
					<label for="slider">Slider:</label>
					<input type="range" name="slider" value="0" min="0" max="20" />
';
					break;
				
				}
				
				if($i2 == 0){
					$firstlast = ' class="first"';
				}elseif($i2 == (count($getProjects)-1)){
					$firstlast = ' class="last"';
				}else{
					$firstlast = '';
				}
				
				$projectList .= '<li'.$firstlast.'>
				<div class="preview">
					<h3>'.$project['naam'].'</h3>
					<p><img src="'.$project['snapshot'].'" /></p>
				</div>
				
				<!--<div class="rating '.$project['id'].'">
					<input type="range" name="slider" class="sldr" value="0" min="0" max="100"  />
				</div>-->
				
				<div class="rating '.$project['id'].'">
					<!--<p>'.$rating.'</p>-->
					'.$ratingBtns.'
				</div>
				<div class="clear"></div>
				</li>
				';
			}
			
			
			$projectList .= '</ul>';
			
			
			//if($getVotes['succes']){
				$page['content'] = $projectList;
			//}
			
		}else{
			$page['content'] = 'There are no projects to vote on...';
		}
		
	//}
		
	}else{
		
		//$page['content'] = 'Cookie found, but no database voter found for hash: '.$getUserhash['content'];
		header("Location: /");
		
	}
	
}else{
	
	$page['content'] = 'You have no cookie support...';
	
}

?>