<?php

class mobifyClass extends mysqlClass{

	//public $db_account;
	
	public function __construct() {
		//$db_account = $this->get_database_login();
	}

	public function mobifyURLs($content){
		
		$output = str_replace($this->settings['basefolder'], $this->settings['basefolder'].'mobile/', $content);
		$output = str_replace('content/uploads', 'upload', $output);
		$output = str_replace('{basefolder}', $this->settings['basefolder'], $output);
		
		return($output);
	}
	
	public function matchUrl($options = 0){
		
		if($options){
		
		if($options['url']){
			
			//Split the url
			$urlArr = explode("/", $options['url']);
			
			//if more that 1 entry
			if(isset($urlArr) && count($urlArr) > 1){
				$searchUrl = $urlArr[0];
			}else{
				$searchUrl = $options['url'];
			}
			
			//Search with the first url parameter
			$q = "SELECT * FROM `urlrewrite` WHERE `urlrewrite` LIKE '%".$searchUrl."%'";
			$getRewrite = $this->query('core', $q);
			
			if($getRewrite['succes']){
				array_shift($getRewrite);
				
				$match = array();
				
				//Comare the returned searches to the input url
				foreach($getRewrite as $i => $rule){
					$index = count($match);
					similar_text($rule['urlrewrite'], $searchUrl, $match[count($match)]['match']);
					$match[$index]['index'] = $i;
					$match[$index]['source'] = 'rewrite';
				}
			}
			
			//Search with the first url parameter
			$q = "SELECT * FROM `page` WHERE `urlrewrite` LIKE '%".$searchUrl."%'";
			$getPage = $this->query('core', $q);
			
			if($getPage['succes']){
				array_shift($getPage);
				
				$match = array();
				
				//Comare the returned searches to the input url
				foreach($getPage as $i => $rule){
					$index = count($match);
					similar_text($rule['urlrewrite'], $searchUrl, $match[$index]['match']);
					$match[$index]['index'] = $i;
					$match[$index]['source'] = 'page';
				}
			}
			
			//Sort the matches
			arsort($match);
				
			$i2 = 0;
				
			//Add matches to the matched array
			foreach($match as $i => $rule){
				//echo '<pre>'.$i.'<br/>'.print_r($rule, true).'</pre>';
				if($rule['source'] == 'rewrite'){
					$getRewrite[$rule['index']]['match'] = $rule['match'];
					$matched[$i2] = $getRewrite[$rule['index']];
					$i2++;
				}elseif($rule['source'] == 'page'){
					$getPage[$rule['index']]['match'] = $rule['match'];
					$getPage[$rule['index']]['type'] = 'page';
					$matched[$i2] = $getPage[$rule['index']];
					$i2++;
				}
			}
				
			//Make output
			$output['succes'] = true;
			$output['matches'] = $matched;
				
			/*}else{
				$output['succes'] = false;
			}else{
				$q = "SELECT * FROM `page` WHERE `urlrewrite` LIKE '%".$options['url']."%'";
				$getMatch = $this->query('core', $q);
				
				if($getMatch['succes']){
					array_shift($getMatch);
					
					foreach($getMatch as $i => $page){
						similar_text($page['urlrewrite'], $options['url'], $match[$i]['match']);
					}
					
					arsort($match);
					
					$output['succes'] = true;

					$i2 = 0;
					
					foreach($match as $i => $page){
						$getMatch[$i]['match'] = $page['match'];
						$getMatch[$i]['type'] = 'page';
						$output[$i2] = $getMatch[$i];
						$i2++;
					}
					
				}else{
					$output['succes'] = false;
				}
			}*/
			
		}
		
		}
		
		return($output);
	}
	
	public function echo_mime($path){
		
		$extension = stristr($path, '.');
		
		switch($extension){
		
			case ".css":
				header('Content-type: text/css');
			break;
			
			case ".js":
				header('Content-type: text/javascript');
			break;
			
			case ".atom":
				header('Content-type: application/atom+xml');
			break;
			
			case ".bmp":
				header('Content-type: image/bmp');
			break;
			
			case ".gif":
				header('Content-type: image/gif');
			break;
			
			case ".jpg":
				header('Content-type: image/jpeg');
			break;
			
			case ".jpeg":
				header('Content-type: image/jpeg');
			break;
			
			case ".png":
				header('Content-type: image/png');
			break;
			
			case ".json":
				header('Content-type: image/jpeg');
			break;
			
			case ".pdf":
				header('Content-type: application/pdf');
			break;
			
			case ".xml":
				header('Content-type: text/xml');
			break;
			
			default:
				header('Content-type: text/plain');
			break;
			
		}
		
	}
	
}

?>