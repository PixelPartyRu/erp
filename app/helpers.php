<?php
function select_inn($debitors){
	$select  = array();	
	
	foreach($debitors as $debitor){
		$select[$debitor->id] = $debitor->name.' ('.$debitor->inn.')';
	}
	return $select;
}

?>