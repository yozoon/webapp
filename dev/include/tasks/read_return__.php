<?php

$sql_query= $db->prepare("SELECT ".$column." FROM ".$table." where ".$skey." LIKE '".$sentry."'");
$sql_query->execute($sql_query->errorInfo());
$results = $sql_query->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $result){
	$val = ($decrypt ? string_decrypt($result[$column]) : $result[$column]);
	if($column=="pois"){
		$val = substr(substr($val, 1), 0, -1);
	}
	return print_r(json_decode($val, true),true);
}

?>
