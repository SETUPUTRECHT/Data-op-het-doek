<?php

if(isset($pagename) && !empty($pagename)){

//Get pagesettings
$pageSettings = $cms->getpageSettings(array("pagename" => $pagename));

if($pageSettings['succes']){

	//Fill the page and settings values
	$page['title'] = $pageSettings[0]['title'];
	$settings['seo']['keywords'] = $pageSettings[0]['keywords'];
}

}else{
	$page['title'] = 'Data op het doek - Stemtool';
}

//Standard nonchaning values
$settings['seo']['analytics_code'] = 'UA-000000000';
$settings['seo']['description'] = 'Description of your website';

//SEO settings
if(!isset($settings['seo']['keywords'])){
	$settings['seo']['keywords'] = '';
}

?>