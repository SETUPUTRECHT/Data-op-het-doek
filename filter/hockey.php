<?php
	
/* Filter based on m.nu.nl */
$output['content'] = '';

/////////////////////
// START OF FILTER //
/////////////////////

//Filter content using phpQuery
$dom_td = pq("div.resultsStandenMotor")->find('a.activeTeam')->parent()->parent()->find("td");

$output['content'] = '<table>
<tr style="padding-top:6px;"><th>Datum & Tijd</th><th>W.nr.</th></tr>
<tr>
';

//Get a header
$i = 0;
foreach(pq($dom_td) as $td){
	if($i == 3){
		$output['content'] .= '</tr><tr style="padding-top:6px;"><th>Team</th><th>Team</th></tr><tr>';
	}elseif($i == 5){
		$output['content'] .= '</tr><tr style="padding-top:6px;"><th>Veld</th><th>Accommodatie</th></tr><tr>';
	}
	
	if($i == 0){
		$output['content'] .= '<td style="text-align:center;">'.strip_tags(pq($td)->html());
	}elseif($i == 1){
		$output['content'] .= ' om '.strip_tags(pq($td)->html()).'</td>';
	}else{
		$output['content'] .= '<td style="text-align:center;">'.strip_tags(pq($td)->html()).'</td>';
	}
	
	$i++;
}

$output['content'] .= '</tr>
</table>';
	
if(strlen($output['content']) > 1){
	$output['succes'] = true;
}else{
	$output['succes'] = false;
}

?>