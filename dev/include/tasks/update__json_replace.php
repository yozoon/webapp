<?php

$json = string_decrypt($sql_json[$column]);
$json_array = json_decode($json, true);
if($column == "suchgebiete"){
	foreach($json_array['features'] as $key => $val_array){
		$properties = $val_array['properties'];
		$property = array_search($values, $properties);
		if($property){
			foreach($json_nodes as $nkey => $nvalue){
				$val_array['properties'][$nkey] = $nvalue;
			}
			$json_array['features'][$key] = $val_array;
		}
	}
}else if($column == "personen_im_einsatz"){
	$json_array = $json_nodes;
}

//$key = array_search($values, $json_array);
//print_r($json_array);
$json_array = json_encode($json_array);

print_r($json_array);
$encrypted = string_encrypt($json_array);
($skey == "EID") ?
	$insert = $db->prepare("UPDATE ".$table." SET ".$column." = :encrypted WHERE ".$skey." = ".$sentry) : 
	$insert = $db->prepare("UPDATE ".$table." SET ".$column." = :encrypted WHERE ".$skey." = :".$sentry."");
	
$insert->bindValue(":encrypted", $encrypted, PDO::PARAM_STR);
$insert->execute() or die(print_r($insert->errorInfo()));
?>
