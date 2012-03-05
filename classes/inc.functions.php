<?php

class functionsClass {
	
	public function __construct() {
		//$db_account = $this->get_database_login();
	}

	//Dialog laten zien
	public function dialog($msg){

	$html = '<div class="notification">
'.$msg.'
</div>';

	$output = $html;

	return($output);
	}
	
	//Notification laten zien
	public function notification($msg){

	$html = '<div class="notification">
'.$msg.'
</div>';

	$output = $html;

	return($output);
	}
	
	//Meta redirect maken
	public function redirect($interval, $adres){
	$meta_header = '<meta http-equiv="refresh" content="'.$interval.';URL='.$adres.'" />
	';
	return($meta_header);
	}
	
	//Write to a file
	public function write_to_file($file, $content){

	$fh = fopen($file, 'w') or die("can't open file");
	if(fwrite($fh, $content)){
		$output = true;
	}else{
		$output = false;
	}
	fclose($fh);

	return($output);
	}

	
	//Read a file
	public function read_from_file($file){

	$fh = fopen($file, 'r');
	$data = fread($fh, filesize($file));
	fclose($fh);
	if(!$data){
		$output['succes'] = false;
	}else{
		$output['succes'] = true;
		$output['content'] = $data;
	}

	return($output);
	}

	public function read_files_dir($path){
		if ($handle = opendir($path)) {
			
			$output['succes'] = true;
			$filelist = array();
			
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					array_push($filelist, $file);
				}
			}
			closedir($handle);
			
			if(count($filelist) > 0){
				$output['filelist'] = $filelist;
			}
			
		}else{
			$output['succes'] = false;
		}
		
	return($output);
	}
	
	//Generate random hash with a specified length
	public function randomHash($length){
	$hash = "";
	$possible = "0123456789abcdefghijklmnopqrstuvwxyz"; 

	$i = 0; 
	while ($i < $length) {
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($hash, $char)) {
			$hash .= $char;
			$i++;
		}
	}
	  return $hash;
	}

	
	//Extract all SRC attributes from HTML
	public function extract_images($content){

	$output = '';

	$image_regex_src_url = '/<img[^>]*'.'src=[\"|\'](.*)[\"|\']/Ui';
	preg_match_all($image_regex_src_url, $content, $out, PREG_PATTERN_ORDER);
	$image = $out[1];

	foreach($image as $index => $src){
	$output .= $src;
	}

	$output = urlencode($output);

	return($output);
	}
	
	
	//Truncate texts
	public function truncate($content, $length){
	$output = '';
	
	if(strlen($content) > $length){
	$text = substr($content, 0, $length);
	$words = explode(" ", $text);

	foreach($words as $index => $word){
	if($index < (count($words)-1)){
	$output .= $word.' ';
	}
	}

	$output = substr($output, 0, (strlen($output)-1)).'...';
	}else{
	$output = $content;
	}

	return($output);
	}
	
	public function getBrowser(){
    
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";		
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
	if($bname == 'Internet Explorer' && $version < 8){
		$notification = '<p class="tooltip-under" data-tooltip="Your using an old version of Internet Explorer to browse this website. By upgrading to one of the following browsers all websites will work better and faster and you\'ll be insured of better safety.">You are using an unsafe browser!</p>';
	}else{
		$notification = '';
	}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern,
		'notification' => $notification
    );
	}
}

?>