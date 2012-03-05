<?php

if(!isset($page['notification'])){
	$page['notification'] = '';
}

if(!isset($page['content'])){

//Content vullen
$page['content'] = '
<div data-role="page" id="">

<div data-role="header">
<h1>Empty</h1>
</div>

<div data-role="content">	
<p>Not a recognized page</p>		
</div>

</div>';

}else{

//Make the header
include('views/mobile/top.php');

if(isset($_GET['url']) && $_GET['url'] != '' && $_GET['url'] != 'home'){
$back = '<a data-rel="back" data-iconpos="left" data-icon="arrow-l">Back</a>';
}else{
$back = '';
}

$page['content'] = '
<div data-role="page" id="" data-title="'.$page['title'].'">

<div id="hedr" data-role="header" data-theme="s">
'.$back.'
<p><a href="/"><img src="/tpl/default/img/header-image.jpg" id="logo" width="285" height="146" /></a></p>
</div>

<div data-role="content">	
'.$page['content'].'
</div>

<div data-role="footer">	
<p><a href="http://setup.nl" target="_blank" rel="external"><img src="/tpl/default/img/setup-logo-link.jpg" /></a></p>
</div>

</div>
';
//Load footer
//include('views/footer.php');

}

?>