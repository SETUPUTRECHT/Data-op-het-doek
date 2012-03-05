<?php

//Add the top part
$page['top'] = '<div id="mainmenu" data-role="controlgroup" data-type="horizontal">
';

//Load the mainmenu
$mainmenu = $cms->loadModule(array("modulename" => "mainmenu", "matchedpage" => $pagefile));
$page['stylesheet'] .= $mainmenu['stylesheet'];
$page['javascript'] .= $mainmenu['javascript'];
//$page['xtrascript'] .= $mainmenu['xtrascript'];

$page['top'] .= $mainmenu['content'];

$page['top'] .= '
</div>
';


?>