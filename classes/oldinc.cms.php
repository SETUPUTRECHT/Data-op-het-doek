<?php

class cmsClass extends mysqlClass{
	
	public $settings;
	public $elements;
	
	public function __construct() {
		//Load the website settings
		$settings = $this->getWebsiteSettings();
		if($settings['succes']){
			array_shift($settings);
			foreach($settings as $i => $setting){
				$this->settings[$setting['name']] = $setting['content'];
			}
		}
	}
	
	public function mobifyURLs($content){
		
		$output = str_replace($this->settings['basefolder'], $this->settings['basefolder'].'mobile/', $content);
		$output = str_replace('content/uploads', 'upload', $output);
		$output = str_replace('{basefolder}', $this->settings['basefolder'], $output);
		
		return($output);
	}
	
	public function translate($options){
		
		if(!isset($options['context'])){
			$options['context'] = 'default';
		}
			switch($options['context']){
			
			case "elementtable":
			
			$labels[0]['computer'] = 'id';
			$labels[0]['human'] = '';
			$labels[1]['computer'] = 'name';
			$labels[1]['human'] = 'Naam';
			$labels[2]['computer'] = 'categoryID';
			$labels[2]['human'] = 'Categorie';
			$labels[3]['computer'] = 'elementtypeID';
			$labels[3]['human'] = 'Elementtype';
			$labels[4]['computer'] = 'modified';
			$labels[4]['human'] = 'Aangepast op';
			$labels[5]['computer'] = 'statusID';
			$labels[5]['human'] = 'Status';
			
			break;
			
			default:
			
			$labels[0]['computer'] = 'parentID';
			$labels[0]['human'] = 'Deze pagina is een subpagina van';
			$labels[1]['computer'] = 'templateID';
			$labels[1]['human'] = 'Te gebruiken template';
			$labels[2]['computer'] = 'urlrewrite';
			$labels[2]['human'] = 'Url naar deze pagina';
			$labels[3]['computer'] = 'menuname';
			$labels[3]['human'] = 'De te gebruiken naam in menu\'s';
			$labels[4]['computer'] = 'name';
			$labels[4]['human'] = 'Paginanaam';
			$labels[5]['computer'] = 'headhtml';
			$labels[5]['human'] = 'Extra te gebruiken vermeldingen in head van html';
			$labels[6]['computer'] = 'title';
			$labels[6]['human'] = 'Titel van deze pagina (seo)';
			$labels[7]['computer'] = 'mobile';
			$labels[7]['human'] = 'Gebruik deze pagina in mobiele versie';
			$labels[8]['computer'] = 'link';
			$labels[8]['human'] = 'Link naar een externe pagina (met http://)';
			$labels[9]['computer'] = 'newwindow';
			$labels[9]['human'] = 'Open pagina in een pop-up';
			$labels[10]['computer'] = 'hidden';
			$labels[10]['human'] = 'Verberg deze pagina';
			$labels[11]['computer'] = 'keywords';
			$labels[11]['human'] = '3-5 steekwoorden (gescheiden door comma)';
			$labels[12]['computer'] = 'disabled';
			$labels[12]['human'] = 'Dit is een inactieve pagina';
			
			break;
			
			}
		
		
		
		if(isset($options['mode']) && $options['mode'] == 'computer'){
			foreach($labels as $i => $label){
				if($label['human'] == $options['content']){
					$output = $label['computer'];
					break;
				}
			}
		}else{
			foreach($labels as $i => $label){
				if($label['computer'] == $options['content']){
					$output = $label['human'];
					break;
				}
			}
		}
		
		if(!isset($output)){
			$output = $options['content'];
		}
		
		return($output);
	}
	
	//Add a new element
	public function addElement($vars){
	
	$mm = new cmsClass;
	
	//Start query
	$q = "INSERT INTO `mobman`.`element` ( `id` , `name` ,`elementtypeID` , `filterID` , `moduleID` , `categoryID` , `pageID` , `statusID` , `modified` , `volgnr` )
	VALUES ( NULL , '".$mm->sanitize($vars['elementname'], 'w')."', '".$vars['elementtypeID']."', '0', '0', '1', '0', '0', CURRENT_TIMESTAMP , '0' );";
	
	$addElement = $this->query("core", $q);
	if($addElement['succes']){
		$getElement = $this->getElement(array("elementName" => $mm->sanitize($vars['elementname'], 'w')));
		if($getElement['succes']){
			$elementID = $getElement[0]['id'];
			
			//Unset already used vars
			unset($vars['elementtypeID']);
			unset($vars['elementname']);
			
			//print_r($vars);
			
			//Add received fields
			foreach($vars as $fieldname => $fieldval){
				$addNode = $this-> addNode(array('name' => $fieldname, 'elementID' => $elementID, 'content' => $mm->sanitize($fieldval, 'w')));
			}
			
			$output['succes'] = true;
			
		}else{
			$output['succes'] = false;
		}
		
	}else{
		$output['succes'] = false;
	}
	
	return($output);
	}
	
	public function deleteElement($options){
		
		if(isset($options['elementID']) && !empty($options['elementID'])){
			$q = "DELETE FROM `element` WHERE `element`.`id` = ".$options['elementID'];
			$removeElement = $this->query("core", $q);
			if($removeElement['succes']){
			
				$removeNodes = $this->deleteNode(array('elementID' => $options['elementID']));
				if($removeNodes['succes']){
					$output['succes'] = true;
				}else{
					$output['succes'] = false;
				}
				
			}else{
				$output['succes'] = false;
			}
		}else{
			$output['succes'] = false;
		}
		
		return($output);
	}
	
	public function sanitize($content, $mode = 'read'){
		
		$allowedTags='<a><p><strong><em><u><h1><h2><h3><h4><h5><h6><img><li><ol><ul><span><div><br><ins><del>';
		
		if($mode == 'read'){
		   	$output = strip_tags(stripslashes(htmlspecialchars_decode($content, ENT_QUOTES)),$allowedTags);
		}else{
			$output = htmlspecialchars(strip_tags($content,$allowedTags), ENT_QUOTES);
		}
	return($output);
	}
	
	//Get elements
	public function getElement($options){
	
	$params = '';
	
	if(isset($options['elementID'])){
		$Qid = "`id` = ".$options['elementID'];
		$params .= $Qid;
	}else{
		$Qid = '';
	}
	
	if(isset($options['elementName'])){
		$Qname = "`name` = '".$options['elementName']."'";
		if(stristr($params, '`id` =')){
			$params .= ' AND '.$Qname;
		}else{
			$params .= $Qname;
		}
	}else{
		$Qname = '';
	}
	
	if(isset($options['categoryID'])){
		$Qcat = "`categoryID` = '".$options['categoryID']."'";
		if(stristr($params, '`id` =') || stristr($params, '`name` =')){
			$params .= ' AND '.$Qcat;
		}else{
			$params .= $Qcat;
		}
	}else{
		$Qcat = '';
	}
	
	//Start query
	$q = "SELECT * FROM `element` WHERE ".$params."";
	
	//Perform query
	$output = $this->query("core", $q);

	return($output);
	}

	//Get elements
	public function getCategory($options){
	
	$params = '';
	
	if(isset($options['categoryID'])){
		$Qid = "`id` = ".$options['categoryID'];
		$params .= $Qid;
	}else{
		$Qid = '';
	}
	
	if(isset($options['categoryName'])){
		$Qname = "`name` = '".$options['categoryName']."'";
		if(stristr($params, '`id` =')){
			$params .= ' AND '.$Qname;
		}else{
			$params .= $Qname;
		}
	}else{
		$Qname = '';
	}
	
	if($params == ''){
		$params = 1;
	}
	
	//Start query
	$q = "SELECT * FROM `category` WHERE ".$params;
	
	//Perform query
	$output = $this->query("core", $q);

	return($output);
	}
	
	//Add a new elementtype
	public function addElementtype($vars){
	
	//Start query
	$q = "INSERT INTO `elementtype` (`id`, `name`) VALUES (NULL, '".$vars['name']."');";
	$addElementtype = $this->query("core", $q);
	if($addElementtype['succes']){
		$q = "SELECT `id` FROM `elementtype` WHERE `name` = '".$vars['name']."' ORDER BY `id` ASC LIMIT 1";
		$getElementtype = $this->query("core", $q);
		
		if($getElementtype['succes']){
			$elementID = $getElementtype[0]['id'];
						
			//Start query
			$cols = "INSERT INTO `elementtypefield` (`id`, `elementtypeID`, `fieldname`, `contenttypeID`)";
			$values = " VALUES ";
			
			//Add received fields
			foreach($vars['fields'] as $i => $field){
				$values .= "(NULL, ".$elementID.", '".$field['name']."', '".$field['val']."'),";
			}
			
			//Finish the query
			$values = substr($values, 0, (strlen($values)-1)).';';
			$insertQ = $cols.$values;
			//echo $insertQ;
			$insert = $this->query("core", $insertQ);
			if($insert['succes']){
				$output['succes'] = true;
			}else{
				$output['succes'] = false;
			}
			
		}else{
			$output['succes'] = false;
		}
		
	}else{
		$output['succes'] = false;
	}
	
	return($output);
	}
	
	//Get elementtype
	public function getElementtype($options){
	
	$params = '';
	
	if(isset($options['elementtypeID'])){
		$Qid = "`id` = ".$options['elementtypeID'];
		$params .= $Qid;
	}else{
		$Qid = '';
	}
	
	if($params == ''){
		$params = 1;
	}
	
	//Start query
	$q = "SELECT * FROM `elementtype` WHERE ".$params.' ORDER BY `name` ASC';
	
	//Perform query
	$output = $this->query("core", $q);

	return($output);
	}
	
	//Get elementtype
	public function getElementtypefields($options){
	
	$params = '';
	
	if(isset($options['elementtypeID'])){
		$Qid = "`elementtypeID` = ".$options['elementtypeID'];
		$params .= $Qid;
	}else{
		$Qid = '';
	}
	
	if($params == ''){
		$params = 1;
	}
	
	//Start query
	$q = "SELECT * FROM `elementtypefield` WHERE ".$params.' ORDER BY `fieldname` ASC';
	
	//Perform query
	$output = $this->query("core", $q);

	return($output);
	}
	
	//Get status
	public function getElementstatus($options){
	
	$params = '';
	
	if(isset($options['statusID'])){
		$output['succes'] = true;
		
		switch($options['statusID']){
			case 0:
				$output['name'] = 'Unpublished';
			break;
			
			case 1:
				$output['name'] = 'Published';
			break;
		}
	}else{
		$output['succes'] = false;
	}

	return($output);
	}
	
	//Edit element settings
	public function editelementSettings($vars){
	$mm = new cmsClass;
	$elementID = $vars['id'];
	
	//Start query
	$editQ = "UPDATE `element` SET";
	
	//Exclude the id for editing
	unset($vars['id']);
	
	foreach($vars as $fieldname => $value){
		if(stristr($fieldname, "node_")){
			$this->editNode(array("nodeID" => str_replace("node_", "", $mm->sanitize($fieldname, 'w')), "content" => $mm->sanitize($value, 'w')));
		}elseif($fieldname == 'modified'){
			$editQ .= " `".$fieldname."` = NULL,";
		}else{
			$editQ .= " `".$fieldname."` = '".$mm->sanitize($value, 'w')."',";
		}
	}
	
	//Cut of the last comma
	$editQ = substr($editQ, 0, (strlen($editQ)-1));
	
	//Finish the query
	$editQ .= " WHERE `element`.`id` =".$elementID.";";
	
	$output = $this->query("core", $editQ);
	
	return($output);
	}
	
	//Add node
	public function addNode($vars){
	
	//Start query
	$insertQ = "INSERT INTO `node` (`id`, `name`, `elementID`, `content`) VALUES ( NULL, '".$vars['name']."', '".$vars['elementID']."', '".$vars['content']."');";
	
	$output = $this->query("core", $insertQ);
	
	return($output);
	}
	
	//Delete a content node
	public function deleteNode($options){
		
		if(isset($options['elementID']) && !empty($options['elementID'])){
			$q = "DELETE FROM `node` WHERE `node`.`elementID` = ".$options['elementID'];
			$removeNode = $this->query("core", $q);
			if($removeNode['succes']){
				$output['succes'] = true;
			}else{
				$output['succes'] = false;
			}
		}
		
		if(isset($options['nodeID']) && !empty($options['nodeID'])){
			$q = "DELETE FROM `node` WHERE `node`.`id` = ".$options['nodeID'];
			$removeNode = $this->query("core", $q);
			if($removeNode['succes']){
				$output['succes'] = true;
			}else{
				$output['succes'] = false;
			}
		}
		
		return($output);
	}
	
	//Edit node
	public function editNode($vars){
	
	//Start query
	$editQ = "UPDATE `node` SET `content` = '".$vars['content']."' WHERE `node`.`id` =".$vars['nodeID'].";";
	
	$output = $this->query("core", $editQ);
	
	return($output);
	}
	
	//Get pages
	public function getPages($options = 0){
	
	if($options){
	switch($options['mode']){
		
		//Filter op objecttype
		case "objtype":
		if(isset($options['objtypeID']) && !empty($options['objtypeID'])){
			//$q = "SELECT * FROM `object` WHERE `objecttypeID` = ".$options['objtypeID'];
		}else{
			//$q = "SELECT * FROM `object` ORDER BY `id` ASC";
		}
		
		break;
		
		//Laat alle objecten zien
		default:
			//$q = "SELECT * FROM `object` ORDER BY `id` ASC";
		break;
	}
	}else{
		$q = "SELECT * FROM `page` ORDER BY `id` ASC";
	}
	
	$output = $this->query("core", $q);
	return($output);
	}
	
	//Get the page settings of an id
	public function getpageSettings($options){
	
	if(isset($options['cols'])){
		$cols = '';
		foreach($options['cols'] as $i => $column){
			$cols .= ' `'.$column.'`';
		}
	}else{
		$cols = ' *';
	}
	
	if(isset($options['pageID'])){
		$q = "SELECT".$cols." FROM `page` WHERE `id` = '".$options['pageID']."'";
	}elseif(isset($options['pagename'])){
		$q = "SELECT".$cols." FROM `page` WHERE `name` = '".$options['pagename']."'";
	}
	
	$output = $this->query("core", $q);
	
	return($output);
	}
	
	//Add page settings
	public function addpageSettings($vars){
	
	//Start query
	$cols = "INSERT INTO `page` (`id`";
	$values = " VALUES (NULL";
	
	//Exclude the id since it's generated
	unset($vars['id']);
	
	//Add received fields
	foreach($vars as $fieldname => $fieldvalue){
		$cols .= ", `".$fieldname."`";
		$values .= ", '".$fieldvalue."'";
	}
	
	//Finish the query
	$cols .=")";
	$values .= ");";
	$insertQ = $cols.$values;
	
	$insert = $this->query("core", $insertQ);
	if($insert['succes']){
		$insertID = $this->query("core", "SELECT `id` FROM `page` ORDER BY `id` DESC LIMIT 1");
		if($insertID['succes']){
			$output['succes'] = true;
			$output['id'] = $insertID[0]['id'];
		}else{
			$output['succes'] = false;
		}
	}else{
		$output['succes'] = false;
	}
	
	return($output);
	}
	
	//Edit page settings
	public function editpageSettings($vars){
	
	$pageID = $vars['id'];
	
	//Start query
	$editQ = "UPDATE `page` SET";
	
	//Exclude the id for editing
	unset($vars['id']);
	
	foreach($vars as $fieldname => $value){
		$editQ .= " `".$fieldname."` = '".$value."',";
	}
	
	//Cut of the last comma
	$editQ = substr($editQ, 0, (strlen($editQ)-1));
	
	//Finish the query
	$editQ .= " WHERE `page`.`id` =".$pageID.";";
	
	$output = $this->query("core", $editQ);
	
	return($output);
	}
	
	//Edit page settings
	public function delpageSettings($pageID){
	
	//Start query
	$q = '';
	
	//foreach($pageIDs as $i => $pageID){
		$q .= "DELETE FROM `page` WHERE `page`.`id` = ".$pageID.";";
	//}
	
	$output = $this->query("core", $q);
	
	return($output);
	}
	
	//Get pages
	public function getAdminPages($options = 0){
	
	if($options){
	switch($options['mode']){
		
		//Filter op objecttype
		case "objtype":
		if(isset($options['objtypeID']) && !empty($options['objtypeID'])){
			//$q = "SELECT * FROM `object` WHERE `objecttypeID` = ".$options['objtypeID'];
		}else{
			//$q = "SELECT * FROM `object` ORDER BY `id` ASC";
		}
		
		break;
		
		//Laat alle objecten zien
		default:
			//$q = "SELECT * FROM `object` ORDER BY `id` ASC";
		break;
	}
	}else{
		$q = "SELECT * FROM `adminpage` ORDER BY `id` ASC";
	}
	
	$output = $this->query("core", $q);
	return($output);
	}
	
	public function getpageID($pageName){
	
	$q = "SELECT `id` FROM `page` WHERE `urlrewrite` = '".$pageName."'";
	$getPage = $this->query("core", $q);
	
	if($getPage['succes']){
		$pageID = $getPage[0]['id'];
	}else{
		$pageID = 0;
	}
	
	return($pageID);
	}
	
	public function getpageFile($urlrewrite){
	
	$q = "SELECT `name` FROM `page` WHERE `urlrewrite` = '".$urlrewrite."'";
	$getName = $this->query("core", $q);
	
	if($getName['succes']){
		$output = $getName[0]['name'];
	}else{
		$output = '404-page-not-found';
	}
	
	return($output);
	}
	
	public function newpageFile($source = 'page/default_page.php', $new){
	
		if(copy($source, $new)) {
			$output = true; 
		}else{
			$output = false;
		}
	
	return($output);
	}
	
	public function deletePage($options){
		$pageSettings = $this->getpageSettings($options);
		
		if($pageSettings['succes']){
			array_shift($pageSettings);
			$delPagesettings = $this->delpageSettings($options['pageID']);
		
			if($delPagesettings['succes']){
				if($this->removepageFile($pageSettings[0]['name'])){
					$output['succes'] = true;
					$output['pagename'] = $pageSettings[0]['name'];
				}else{
					$output['succes'] = false;
				}
			}else{
				$output['succes'] = false;
			}
		}else{
			$output['succes'] = false;
		}
		
		return($output);
	}
	
	public function removepageFile($pagename){
	
		if(file_exists('page/'.$pagename.'.php')){
		if(copy('page/'.$pagename.'.php', 'trash/'.$pagename.'-'.time().'.php')) {
			if(unlink('page/'.$pagename.'.php')){
				$output = true; 
			}else{
				$output = false;
			}
		}else{
			$output = false;
		}
		}else{
			$output = false;
		}
	
	return($output);
	}
	
	//Get elements of a page
	public function getElements($options = 0){
	
	$Qpage = '';
	$Qcat = '';
	$Qelt = '';
	$q = "SELECT * FROM `element`";
	
	if($options){
	
	if(isset($options['pageID'])){
		$pageID = $options['pageID'];
		$Qpage = " `element`.`pageID` = ".$pageID;
	}
	
	if(isset($options['pageName'])){
		$pageID = $this->getpageID($options['pageName']);
		$Qpage = " `element`.`pageID` = ".$pageID;
	}
	
	if(isset($options['categoryID'])){
		$Qcat = " `element`.`categoryID` = ".$options['categoryID'];
	}
	
	if(isset($options['elementtypeID'])){
		$Qelt = " `element`.`elementtypeID` = ".$options['elementtypeID'];
	}
	
	$q .= " WHERE";
	}
	
	if($Qpage != ''){
		$q .= $Qpage;
	}
	
	if($Qcat != '' && $Qpage != '' && stristr($q, $Qpage)){
		$q .= ' OR '.$Qcat;
	}else{
		$q .= $Qcat;
	}
	
	if($Qelt != '' && $Qpage != '' && $Qcat != ''){
		$q .= ' OR '.$Qelt;
	}else{
		$q .= $Qelt;
	}
	
	$q .= ' ORDER BY `element`.`volgnr` ASC';
	//echo $q;
	$query = $this->query("core", $q);
	$output = $query;
	
	if(isset($this->elements) && count($this->elements) > 1){
		array_shift($query);
		array_push($this->elements, $query);
	}else{
		array_shift($query);
		$this->elements = $query;
	}
	
	return($output);
	}
	
	//Handle an element
	public function handleElement($el){
	
	//If there's a module specified
	if(isset($el['moduleID']) && $el['moduleID'] != 0){
		//$output = 'load module: '.$el['moduleID'];
		$getModule = $this->getModule($el['moduleID']);
		if($getModule['succes']){
			include('module/'.$getModule[0]['folder'].'/'.$getModule[0]['folder'].'.php');
		}
	
	//If there's a filter specified
	}elseif(isset($el['filterID']) && $el['filterID'] != 0){
		
		//Get filter specifications
		$getFilter = $this->getFilter($el['filterID']);
		if($getFilter['succes']){
			
			$applyFilter['file'] = $getFilter[0]['file'];
			
			//Get the feed associated with the element
			$getElementfeed = $this->getElementfeed($el['id']);
			if($getElementfeed['succes']){
				$applyFilter['hash'] = $getElementfeed[0]['hash'];
			}else{
				$applyFilter['hash'] = false;
			}
			
			//Initiate Filter class
			$filter = new filterClass;

			//Get a content by applying a filter to a contentfile
			$makeNode = $filter->applyFilter($applyFilter['file'], $applyFilter['hash']);
			
			if($makeNode['succes']){
				$output['content'] = $makeNode['content'];
			}else{
				$output['content'] = 'niks...';
			}
		}else{
			$output['content'] = 'niks...';
		}

	}else{
		$nodes = $this->getNodes($el['id']);
		if($nodes['succes']){
			array_shift($nodes);
			$output['succes'] = true;
			$output['nodes'] = $nodes;
		}
		//$output = $nodes;
	}
	
	return($output);
	}
	
	//Get node(s) associated with an element
	public function getNodes($options = 0){
	
	if($options){
	
	if(isset($options['elementID'])){
		$getElement = $this->getElement(array('elementID' => $options['elementID']));
		if($getElement['succes']){
			$elementtypeID = $getElement[0]['elementtypeID'];
			$getElementtypefield = $this->getElementtypefields(array('elementtypeID' => $elementtypeID));
			if($getElementtypefield['succes']){
				array_shift($getElementtypefield);
				
				$q = "SELECT * FROM `node` WHERE `elementID` = ".$options['elementID']." ORDER BY `id` ASC";
				$getNodes = $this->query("core", $q);
				if($getNodes['succes']){
					array_shift($getNodes);
					
					$i = 0;
					while($i < count($getNodes)){
						foreach($getElementtypefield as $i => $eltype){
							if($eltype['fieldname'] == $getNodes[$i]['name']){
								$getNodes[$i]['contenttypeID'] = $eltype['contenttypeID'];
								$getNodes[$i]['content'] = $this->sanitize($getNodes[$i]['content']);
							}
						}
						
						$i++;
					}
					$output['succes'] = true;
					$output['nodes'] = $getNodes;
				}else{
					$output['succes'] = false;
				}
				
			}else{
				$output['succes'] = false;
			}
		}else{
			$output['succes'] = false;
		}
	}else{
		$output['succes'] = false;
	}
	
	}else{
		$output['succes'] = false;
	}
	
	return($output);
	}
	
	//Get filter specs
	public function getFilter($filterID){
	
	$q = "SELECT * FROM `filter` WHERE `id` = ".$filterID;
	
	$output = $this->query("core", $q);
	return($output);
	}
	
	//Get filter specs
	public function getModule($moduleID){
	
	$q = "SELECT * FROM `module` WHERE `id` = ".$moduleID;
	
	$output = $this->query("core", $q);
	return($output);
	}
	
	//Get element feed specs
	public function getElementfeed($elementID){
	
	$q = "SELECT * FROM `elementfeed` LEFT JOIN `feed` ON `elementfeed`.`feedID` = `feed`.`id` WHERE `elementID` =".$elementID;
	
	$output = $this->query("core", $q);
	return($output);
	}
	
	//Get website settings
	public function getWebsiteSettings(){
	
	$q = "SELECT * FROM `settings` ORDER BY `id` ASC";
	
	$output = $this->query("core", $q);
	return($output);
	}
	
	public function getTemplate($templateName){
		//include template
		include('template/'.$templateName.'/'.$templateName.'.php');
	
	return($output);
	}
	
	public function getBasefolder(){
	
	$urlArr = explode("index.php", $_SERVER['PHP_SELF']);
	$output = substr($urlArr[0], 0, (strlen($urlArr[0])-1));
	
	return($output);
	}
	
	//Add module
	public function loadModule($options = 0){

	if(isset($options['modulename']) && !empty($options['modulename'])){
		$filePath = 'module/'.$options['modulename'].'/'.$options['modulename'].'.php';
		if(file_exists($filePath)){
			include($filePath);
		}else{
			$module['stylesheet'] = '';
			$module['javascript'] = '';
			$module['xtrascript'] = '';
			$module['content'] = '<p class="notification">Please install module "'.$options['modulename'].'" in the module folder.</p>';
		}
	}
	
	if(isset($module)){
		$output = $module;
	}
	
	return($output);
	}
	
	public function friendlyDate($timestamp){
	
	//Standard values
	$SECOND = 1;
	$MINUTE = 60 * $SECOND;
	$HOUR = 60 * $MINUTE;
	$DAY = 24 * $HOUR;
	$MONTH = 30 * $DAY;
	$YEAR = 12 * $MONTH + 11;
	
	//Time difference
	$delta = time()-$timestamp;
	
	if ($delta < 0){
	  return "not yet";
	}
	if ($delta < 1 * $MINUTE){
	  return $delta == 1 ? "one second ago" : $delta." seconds ago";
	}
	if ($delta < 2 * $MINUTE){
	  return "a minute ago";
	}
	if ($delta < 45 * $MINUTE){
	  return round($delta/$MINUTE)." minutes ago";
	}
	if ($delta < 90 * $MINUTE){
	  return "an hour ago";
	}
	if ($delta < 24 * $HOUR){
	  return round($delta/$HOUR)." hours ago";
	}
	if ($delta < 48 * $HOUR){
	  return "yesterday";
	}
	if ($delta < 30 * $DAY){
	  return round($delta/$DAY)." days ago";
	}

	if ($delta < 12 * $MONTH){
	  $months = round($delta/$MONTH);
	  //Convert.ToInt32(Math.Floor((double)ts.Days / 30));
	  return $months <= 1 ? "one month ago" : $months + " months ago";
	}else{
	  $years = round($delta/$YEAR);
	  //Convert.ToInt32(Math.Floor((double)ts.Days / 365));
	  return $years <= 1 ? "one year ago" : $years." years ago";
	}
	
	//return($output);
	}
	
	public function makeQR($data){
		
		if(stristr($data, "www.")){
			$data = str_replace('www.', '', $data);
		}
		
		if(stristr($data, "http://")){
			$data = str_replace('http://', 'www.', $data);
		}
		
		$output = '<img src="/'.$this->settings['basefolder'].'qrcode/'.urlencode($data).'"/>';
		
		return($output);
	}
	
}

?>