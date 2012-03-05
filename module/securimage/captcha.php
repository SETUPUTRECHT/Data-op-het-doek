<?php

require_once 'securimage.php';

$image = new Securimage();

if(isset($_GET['code'])){
	if ($image->check($_GET['code']) == true) {
		echo "Correct!";
	}else{
		echo "Sorry, wrong code.";
	}
}else{
	$captcha = $image->show();
}



?>