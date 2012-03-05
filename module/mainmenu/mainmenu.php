<?php

/* Main menu module */
//$modulePath = stristr(str_replace('\\', '/', dirname(__FILE__)), $this->getBasefolder()).'/';
$moduleName = 'mainmenu';

//Module variables
$module['stylesheet'] = '<link rel="stylesheet" href="/mod/'.$moduleName.'/css/mainmenu.css" type="text/css" />
';

$module['javascript'] = '<script type="text/javascript" src="/mod/'.$moduleName.'/js/mainmenu.js"></script>
';

$module['xtrascript'] = '';

//Get pages
$menuPages = $this->getPages(array('hidden' => 0));

//Pages loaded?
if($menuPages['succes']){
	array_shift($menuPages);
	
	//Make menuHTML
	$menuHTML = '';
	
	$basefolder = '/'.$this->settings['basefolder'];
	foreach($menuPages as $i => $menuPage){
		//If page has menuname make an entry
		if(isset($menuPage['menuname'])){
			if(isset($menuPage['link']) && !empty($menuPage['link'])){
				
			}
			
			if(isset($menuPage['newwindow']) && $menuPage['newwindow'] != 0){
				$target = ' target="_blank"';
			}else{
				$target = '';
			}
			

						
			if(isset($_GET['url'])){
				if($_GET['url'] == $menuPage['urlrewrite']){
					$active = ' active';
				}else{
					$active = '';
				}
			}elseif($i == 0 && !isset($active)){
				$active = ' ui-btn-active';
			}else{
				$active = '';
			}
			
			
			
			$subPages = $this->getPages(array("parentID" => $menuPage['id']));
			$subHTML = '';
			
			//Pages loaded?
			if($subPages['succes']){
				$subHTML = '<li class="sublinks">
';
			//print_r($subPages);
				array_shift($subPages);
				
				foreach($subPages as $i => $subPage){
				
				if(isset($subPage['link']) && !empty($subPage['link'])){
					
				}
				
				if(isset($subPage['newwindow']) && $subPage['newwindow'] != 0){
					$target = ' target="_blank"';
				}else{
					$target = '';
				}
				
				if($i == (count($subPages) - 1)){
					$last = 'last ';
				}else{
					$last = '';
				}
				
				if(isset($_GET['page'])){
					if($_GET['page'] == $subPage['urlrewrite']){
						$active = ' active';
					}else{
						$active = '';
					}
				}elseif($i == 0 && !isset($active)){
					$active = ' active';
				}else{
					$active = '';
				}
				
				$subHTML .= '<a class="'.$last.$subPage['name'].$active.'"'.$target.' href="'.$basefolder.'page/'.$subPage['urlrewrite'].'">'.$subPage['menuname'].'</a>
';
				}
				$subHTML .= '</li>
';
			}
			
			
			if($menuPage['parentID'] == 0){
			
			if($subHTML == ''){
				$dropdown = '';
			}else{
				 $dropdown = ' class="dropdown"';
			}
			
			/*$menuHTML .= '<li class="mainpage '.$menuPage['name'].$active.'"><a'.$dropdown.$target.' href="'.$basefolder.'page/'.$menuPage['urlrewrite'].'">'.$menuPage['menuname'].'</a></li>
'.$subHTML.'
';*/
//<li class="mainpage '.$menuPage['name'].$active.'">
$menuHTML .= '<a'.$target.' class="'.$menuPage['name'].'" data-role="button" href="/'.$menuPage['urlrewrite'].'">'.$menuPage['menuname'].'</a>
';
			}
		}
	}
	//$menuHTML .= '</ul></div>
//';
	
}

$module['content'] = $menuHTML;

?>