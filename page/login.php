<?php

//Make the loginform
$loginForm = $login->loginForm('/'.$cms->settings['basefolder'].'page/'.$cms->settings['adminfolder'].'.login', '/'.$cms->settings['basefolder'].'page/'.$cms->settings['adminfolder'].'.content');

//Input username focussen
$page['xtrascript'] .= '$("input[name=uname]").focus()';

//Check of de gebruiker is ingelogd
if($login->loggedin()){
	//Logged in
	if(isset($_GET['sub']) && !empty($_GET['sub'])){
		if(isset($_SESSION['sid'])){
			if($login->destroySession($_SESSION['sid'])){
				$dialog['head'] = 'U bent uitgelogd';
				$dialog['txt'] = 'Een moment geduld a.u.b. u wordt over enkele seconden doorgestuurd naar de homepage';
				$dialog['redirect'] = '/'.$basefolder;
			}else{
				$dialog['head'] = 'Uitloggen is mislukt';
				$dialog['txt'] = 'De actieve sessie kan niet worden afgebroken';
				$dialog['redirect'] = '/'.$basefolder;
			}
		}else{
			$dialog['head'] = 'Geen sessie';
			$dialog['txt'] = 'Er is geen actieve sessie van u.<br/>Een moment geduld a.u.b. u wordt over enkele seconden doorgestuurd naar het loginscherm';
			$dialog['redirect'] = '/'.$basefolder;;
		}
	
	$page['meta_header'] = $cms->redirect(3, '/'.$basefolder);
	
	$page['content'] .= $cms->dialog('<h2>'.$dialog['head'].'</h2>
	<p>'.$dialog['txt'].'</p>');
		
	}else{
	
		//Home pagina includen
		if(isset($_POST['redir'])){
			if($_POST['redir'] == '/'.$cms->settings['basefolder'].'page/'.$cms->settings['adminfolder'].'.login'){
				$redir = '/'.$cms->settings['basefolder'].'page/'.$cms->settings['adminfolder'].'.content';
			}
			$page['meta_header'] = $cms->redirect(4, $_POST['redir']);
		}else{
			$redir = '/'.$cms->settings['basefolder'].'page/'.$cms->settings['adminfolder'].'.content';
			$page['meta_header'] = $cms->redirect(1, $redir);
		}
		
		$page['content'] .= $cms->dialog('<h2>U bent al ingelogd</h2>
			<p>Een moment geduld a.u.b. u wordt over enkele seconden doorgestuurd naar de beheeromgeving</p>');
	
	}
	
//Anders indien logingegevens zijn ingevoerd
}elseif(isset($_POST['uname']) && !empty($_POST['uname']) && isset($_POST['pword']) && !empty($_POST['pword'])){
	if($login->login($_POST['uname'], md5($_POST['pword']))){
		//logged in
		$page['meta_header'] = $cms->redirect(2, $_POST['redir']);
		$page['content'] .= $cms->dialog('<h2>U bent ingelogd</h2>
	<p>Een moment geduld a.u.b. u wordt over enkele seconden doorgestuurd naar de beheeromgeving</p>
	<a href="/bierpot" data-role="button">Terug naar bierpot</a>');
	}else{
		$page['content'] .= '<div id="login">'.$cms->dialog('<h2>Inloggen mislukt</h2><p>De ingevoerde combinatie van email & wachtwoord is niet bij ons bekend.<br/></p>').$loginForm.'</div>';
	}

//Anders: Loginformulier tonen
}else{
	$page['content'] .= $loginForm;
}

?>