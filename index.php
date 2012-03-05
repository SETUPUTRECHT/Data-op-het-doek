<?php

//Classes includen
include_once("classes/inc.functions.php");
include_once("classes/inc.mysql.php");
include_once("classes/inc.filter.php");
include_once("classes/inc.cms.php");
include_once("classes/inc.login.php");
include_once("classes/inc.mobify.php");
include_once("classes/inc.dod.php");

//Initiate Makemob class
$cms = new cmsClass;
$login = new loginClass;
$mob = new mobifyClass;
$dod = new dodClass;

//Get settings
if($cms->settings){
	$basefolder = $cms->settings['basefolder'];
	$templateName = 'default';//$cms->settings['template'];
}
session_start();
$_SESSION['mobile'] = true;

//Standaard stylesheets
$page['stylesheet'] = '';
//Javascripts initieren
$page['javascript'] = '';

//Template ophalen
$template = $cms->getTemplate($templateName);
//Stylesheet?
if($template['stylesheet']){
	$page['stylesheet'] .= $template['stylesheet'];
}
//Javascript?
if($template['javascript']){
	$page['javascript'] .= $template['javascript'];
}

//Pagina title
$page['title'] = '';

//Meta header initieren
$page['meta_header'] = '';

//xtrascript instellen om document(ready) te vullen
$page['xtrascript'] = '
';

if(isset($_GET['url']) && $_GET['url'] != ''){
	
	$getMatch = $mob->matchUrl(array('url' => $_GET['url']));

	if($getMatch['succes']){
		//print_r($getMatch);
		
		switch($getMatch['matches'][0]['type']){
			
			case "page":
				$pagefile = $getMatch['matches'][0]['name'];
				$pagefilePath = 'page/'.$pagefile.'.php';
				
				//$page['content'] = $pagefile;
				
				if(file_exists($pagefilePath)){
					//Pagina includen met filename + .php
					include($pagefilePath);
				}else{
					header('Location: /404-page-not-found');
				}
			break;
			
			case "file":
				
				if(stristr($getMatch['matches'][0]['path'], '.php')){
					$filePath = substr($getMatch['matches'][0]['path'], 0, (strpos($getMatch['matches'][0]['path'], '.php')+4));
					include($filePath);
				}else{
					$file = $cms->read_from_file(str_replace($getMatch['matches'][0]['urlrewrite'], $getMatch['matches'][0]['path'], $_GET['url']));
					$mob->echo_mime(str_replace($getMatch['matches'][0]['urlrewrite'], $getMatch['matches'][0]['path'], $_GET['url']));
					
					if($file['succes']){
						echo $file['content'];
					}
				}
				
				$__outputfile = true;
			break;
			
			case "php":
				$filePath = $getMatch['matches'][0]['path'].'index.php';
				include($filePath);
				$__outputfile = true;
			break;
			
			default:
			
			break;
		}
	
	}else{
		include('page/vote.php');
		//header('Location: /404-page-not-found');
	}
	
}else{
	include('page/intro.php');
}

if(!isset($__outputfile)){
//Mobile frame
include('views/mobile/frame.php');

//Mobile seo
include('views/mobile/seo.php');

//De html outputten
include("output.php");
}

?>
