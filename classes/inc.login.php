<?php

class loginClass extends cmsClass{
	
	public function __construct() {
		
	}
	
	public function loggedin(){
	
	if(isset($_SESSION['sid']) && $this->validSession($_SESSION['sid'])){
		if($this->extendSession($_SESSION['sid'])){
			$output = true;
		}else{
			$output = false;
		}
		
	}else{
		$output = false;
	}
	
	return($output);
	}

	function accesslevel($level){



	}

	private function getAccountSession(){
	if(isset($_SESSION['sid']) && $this->validSession($_SESSION['sid'])){
		$getAccountID = $this->accountIDfromSession($_SESSION['sid']);
		//print_r($getAccountID);
		if($getAccountID['succes']){
			$output = $this->getAccount($getAccountID[0]['accountID']);
		}else{
			$output['succes'] = false;
		}
	}else{
		$output['succes'] = false;
	}

	return($output);
	}

	function getAccount($accountID = 0){

	if($accountID != 0){
		$q = "SELECT * FROM `account` WHERE `id` =".$accountID." LIMIT 1";
		$output = $this->query('core', $q);
	}else{
		$output['succes'] = false;
	}

	return($output);
	}

	public function getAccountmenu($settings){

	if($this->loggedin()){
	$getAccount = $this->getAccountSession();
		if($getAccount['succes']){//'.$getAccount[0]['email'].'
			$output = '<div id="accountmenu">
<span></span>
<a href="/'.$settings['basefolder'].'page/'.$settings['adminfolder'].'.login/logout">Logout</a></div>';
		}else{
			$output = '<div id="accountmenu"><a href="/'.$settings['basefolder'].'page/'.$settings['adminfolder'].'.login/logout">Logout</a></div>';
		}
	}else{
	$output = '';//<div id="accountmenu"><a href="/'.$basefolder.'/page/aanmelden">Uw site aanmelden</a><span></span><a href="/'.$basefolder.'/page/over-ons">Over ons</a><span></span><a href="/'.$basefolder.'/page/login">Inloggen</a></div>';
	}
	return($output);
	}

	function validSession($sid){
	$query = $this->query("core", "SELECT `accountID` FROM `session` WHERE `sid` = '".$sid."' AND `valid` > NOW()");
	return($query['succes']);
	}

	function accountIDfromSession($sid){
	$output = $this->query("core", "SELECT `accountID` FROM `session` WHERE `sid` = '".$sid."' AND `valid` > NOW() LIMIT 1");
	return($output);
	}

	function extendSession($sid){
	$query = $this->query("core", "UPDATE `session` SET `valid` = '".date("Y-m-d H:i:s", (time()+3600))."' WHERE `session`.`sid` = '".$sid."';");
	return($query['succes']);
	}

	public function login($username, $password){
		$query = $this->query("core", "SELECT `id`, `email` FROM `account` WHERE `email` = '".$username."' AND `password` = '".$password."' ORDER BY id DESC LIMIT 1");

		if($query['succes']){
			
			$session = $this->registerSession($query[0]['id'], $this->randomHash(15));
			if($session['succes']){
				$_SESSION['sid'] = $session['sid'];
				$output = true;
			}else{
				$output = false;
			}
			
		}else{
			$output = false;
		}
		
		return($output);
	}

	function registerSession($accountID, $sid){
	if($this->validSession($sid)){
		$this->destroySession($sid);
	}

	$query = $this->query("core", "INSERT INTO `session` (`id`, `sid`, `valid`, `accountID`) VALUES (NULL, '".$sid."', '".date("Y-m-d H:i:s", (time()+3600))."', '".$accountID."');");
	if($query['succes']){
		$output['succes'] = 1;
		$output['sid'] = $sid;
	}else{
		$output['succes'] = 0;
	}
	return($output);
	}

	function destroySession($sid){
	$query = $this->query("core", "UPDATE `session` SET `valid` = '".date("Y-m-d H:i:s", (time()-3600))."' WHERE `session`.`sid` = '".$sid."';");
	return($query['succes']);
	}

	public function loginForm($action, $redir){

	$output = '
<div class="form formlogin rounded8">
<div class="top"><p>Login</p></div>
<form method="post">
<input type="hidden" name="redir" value="'.$redir.'">
<table>
<tr><td><label>E-mail</label></td></tr>
<tr><td><input class="txtbox" type="text" name="uname" maxlength="120"></td></tr>
<tr><td><label>Password</label></td></tr>
<tr><td><input class="txtbox" type="password" name="pword" maxlength="120"></td></tr>
<tr><td><br/></td></tr>
<tr><td><input class="btn" type="submit" value="login"></td></tr>
</table>
</form>
</div>
';

	return($output);
	}
	
}

//Session starten
if(!isset($_SESSION['sid'])){
session_start();
}
?>