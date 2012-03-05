<?php


if(isset($page['content'])){

$html = '
<!DOCTYPE html> 
<html> 
	<head> 
	<title>'.$page['title'].'</title> 
	
	<meta name="viewport" content="width=device-width,initial-scale=1,max-scale=1,user-scalable=no">
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="/tpl/default/img/favicon-dod.png" />
	<!--<link rel="shortcut icon" type="image/x-icon" href="'.$mm->settings['favicon'].'" />
	<link rel="apple-touch-icon" href="'.$mm->settings['touchicon'].'"/>-->

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="/tpl/default/js/settings.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.js"></script>
	<script type="text/javascript" src="/tpl/default/js/slider.js"></script>
	
	'.$page['stylesheet'].'
	'.$page['meta_header'].'
	'.$page['javascript'].'

<script type="text/javascript">
$(document).ready(function(){
	'.$page['xtrascript'].'
});
</script>

</head> 

<body> 

'.$page['content'].'

</body>
</html>
';

}

/*elseif(isset($page['sub'])){
$html = '
<div data-role="dialog" id="'.$page['page'].'">

'.$page['content'].'
</div>
';*/

echo $html;
?>
