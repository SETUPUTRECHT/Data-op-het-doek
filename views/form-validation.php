<?php

//Load the form validation script
$formValidation = $mm->loadModule(array("modulename" => "form-validation"));
$page['javascript'] .= $formValidation['javascript'];
$page['stylesheet'] .= $formValidation['stylesheet'];


?>