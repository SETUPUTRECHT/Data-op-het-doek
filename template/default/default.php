<?php

if(isset($_SESSION['mobile'])){

$output['stylesheet'] .= '<link rel="stylesheet" href="/tpl/default/css/basic.css" />
';

}else{

$output['stylesheet'] = '<link rel="stylesheet" href="/'.$this->settings['basefolder'].'tpl/default/css/basic.css" type="text/css" />
<link rel="stylesheet" href="/'.$this->settings['basefolder'].'tpl/default/css/form.css" type="text/css" />
<link rel="stylesheet" href="/'.$this->settings['basefolder'].'tpl/default/css/design-v1.css" type="text/css" />
<!--[if IE 6]>
<link rel="stylesheet" href="/'.$this->settings['basefolder'].'tpl/default/css/ie6.css" type="text/css" />
<![endif]-->
';

include_once("classes/inc.login.php");
$login = new loginClass;

if($login->loggedin() && isset($_GET['page']) && stristr($_GET['page'], $this->settings['adminfolder'].'.') || isset($_GET['page']) && stristr($_GET['page'], $this->settings['adminfolder'].'.')){

$output['stylesheet'] .= '
<link rel="stylesheet" href="/'.$this->settings['basefolder'].'tpl/default/css/backend.css" type="text/css" />
';

}

$output['javascript'] = '<script type="text/javascript" src="/'.$this->settings['basefolder'].'tpl/default/js/jquery-1.5.2.min.js"></script>
<script type="text/javascript">
var basefolder = "/'.$this->settings['basefolder'].'";
</script>
';

}

?>