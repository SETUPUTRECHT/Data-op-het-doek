<?php

//Basic vars
$modulePath = stristr(str_replace('\\', '/', dirname(__FILE__)), $this->getBasefolder()).'/';
$moduleName = 'form-validation';

//Include your stylesheet
$module['stylesheet'] = '<link rel="stylesheet" href="'.$this->getBasefolder().'/modcss/'.$moduleName.'/'.time().'/form-validation.css" type="text/css" />
';

//Include your javascripts
$module['javascript'] = '<script type="text/javascript" src="'.$this->getBasefolder().'/modjs/'.$moduleName.'/'.time().'/jquery.validate.min.js"></script>
<script type="text/javascript" src="'.$this->getBasefolder().'/modjs/'.$moduleName.'/'.time().'/add-methods.min.js"></script>
<script type="text/javascript" src="'.$this->getBasefolder().'/modjs/'.$moduleName.'/'.time().'/messages_nl.js"></script>
<script type="text/javascript" src="'.$this->getBasefolder().'/modjs/'.$moduleName.'/'.time().'/form-validation.js"></script>
';

//If you need PHP generated on document load script place it in XTRASCRIPT
$module['xtrascript'] = '';

//Include your HTML content
$module['content'] = '';		

?>