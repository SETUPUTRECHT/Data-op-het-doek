<?php

$getLog = $cms->query("core", "SELECT * FROM `log` ORDER BY `id` DESC LIMIT 50");
if($getLog['succes']){
	array_shift($getLog);
	$logList = '';
	foreach($getLog as $i => $logitem){
		//Begrijpelijk tijd van maken
		$friendlyDate = $cms->friendlyDate(strtotime($logitem['timestamp']));
		
		//List html
		$logList .= '<li>'.$friendlyDate.':<br/>
		'.$logitem['msg'].'</li>';
	}

}

$page['content'] = '<a data-role="button" href="/log">Refresh</a><br/>
<ul id="logprogress" data-role="listview" data-filter="true" data-inset="true">
'.$logList.'
</ul>
<script type="text/javascript" src="/mod/log/log.js"></script>
';

?>