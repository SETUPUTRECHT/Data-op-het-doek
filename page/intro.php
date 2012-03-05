<?php

//Check if user has cookie
$getUserhash = $dod->getUserhash();

if($getUserhash['succes']){
	//Identify voter
	$getVoter = $dod->getVoter($getUserhash['content']);
	
	if($getVoter['status'] == 'existing'){
		$formField['stoelnummer'] = $getVoter[0]['stoelnummer'];
		$formField['rijnummer'] = $getVoter[0]['rijnummer'];
	}else{
		$formField['stoelnummer'] = '';
		$formField['rijnummer'] = '';
	}
	
}

$page['javascript'] .= '<script type="text/javascript" src="/mod/form-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/mod/form-validation/js/add-methods.min.js"></script>
<script type="text/javascript" src="/mod/form-validation/js/messages_nl.js"></script>
<script type="text/javascript" src="/mod/form-validation/js/form-validation.js"></script>
<link rel="stylesheet" href="/mod/form-validation/css/form-validation.css" />
';

$page['content'] = '<h1 class="intro">Laat uw stem horen</h1>
			<p style="text-align:center;"><img src="/tpl/default/img/intro-stembus.jpg" /></p>
<p>
Tijdens de voorstelling "Data op het Doek" kunt u via deze website stemmen op de verschillende visualisaties. Per film kunt u aangeven hoe <u>informatief</u> en hoe <u>cool</u> u de visualisatie vindt.
<br/>
<br/>
Stemmen doet u door in de betreffende categorie op de stembalk te <strong><u>KLIKKEN</u></strong>.<br/>
<br/>
</p>

<h2>Uw informatie</h2>

<p>
Voor het maken van een datavisualisatie van de stemmen van vandaag vragen wij u de onderstaande gegevens in te vullen.
</p>

<form action="/vote" method="post" class="validateme">

<div data-role="fieldcontain">
	<label for="stoelnummer">Uw stoelnummer:</label>
	<input type="number" name="stoelnummer" id="stoelnummer" value="'.$formField['stoelnummer'].'" class="required">
</div>

<!--<div data-role="fieldcontain">
	<label for="rijnummer">Uw rijnummer:</label>
	<input type="number" name="rijnummer" id="rijnummer" value="'.$formField['rijnummer'].'" class="required">
</div>-->

<div data-role="fieldcontain">
	<input type="submit" id="snd" value="Beginnen" data-theme="s" data-icon="arrow-r" data-iconpos="right" />
</div>

</form>
';



?>