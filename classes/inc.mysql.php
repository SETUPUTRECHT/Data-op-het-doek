<?php

class mysqlClass extends functionsClass{

	public $db_account;
	
	public function __construct() {
		//$db_account = $this->get_database_login();
	}
	
	//Database gegevens die nodig zijn voor een connectie
	private function get_database_login($db = "core"){

	if($db == 'core'){
		$output['username'] = 'USERNAME';
		$output['password'] = 'PASSWORD';
		$output['database'] = 'DATABASENAME';
	}/*elseif($db == 'mobman'){
		$output['username'] = 'root';
		$output['password'] = '';
		$output['database'] = 'clients';
	}*/
	
	return($output);
	}

	//Functie voor het gemakkelijk uitvoeren van queries
	public function query($db, $query){ 
	
	//Vraag de gegevens voor de database op
	$db_account = $this->get_database_login($db);
	
	//Maak verbinding
	mysql_connect("localhost",$db_account['username'],$db_account['password']);
	@mysql_select_db($db_account['database']) or die( "Unable to select database");
	///

	//Voer de query uit en zet de resultaten in $result
	$result = mysql_query($query);

	//Als de query correct is uitgevoerd bekijken wat voor query het was
	if($result){
	//INSERT
	if(stristr($query, 'INSERT')){
	$output['succes'] = true;
	//UPDATE
	}elseif(stristr($query, 'UPDATE `')){
	$output['succes'] = true;
	//DELETE
	}elseif(stristr($query, 'DELETE FROM')){
	$output['succes'] = true;
	}else{
	//WHERE
	$i = 0;
	$num = mysql_num_rows($result);

	if($num > 0){
		//Query is gelukt
		$output['succes'] = true;
			
		//Alle resultaten doorlopen
		While($row = mysql_fetch_assoc($result)){
			//Voor iedere 'ROW' alle variabelen doorlopen
			foreach ($row as $fieldname => $waarde){
				//row_fields array vullen
				$row_fields[$fieldname] = iconv('Windows-1252', 'UTF-8', $waarde);
			}
			$output[$i] = $row_fields;
			$i++;
		}
			
	}else{
		//Query is uitgevoerd, maar bracht geen rows op
		$output['succes'] = false;
	}
	}
	}else{
		//Query is niet uitgevoerd antwoord met een 0
		$output['succes'] = false;
	}

	return($output);
	}
	
	public function time2mysql($timestamp){
    return date('Y-m-d H:i:s', $timestamp);
	}
	
	public function mysql2nl($datetime){
    return date('d-m-Y H:i:s', strtotime($timestamp));
	}
}

?>