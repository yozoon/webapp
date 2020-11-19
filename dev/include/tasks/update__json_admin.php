<?php

if($sql_json[$column] != null){
	$userjson =string_decrypt($sql_json[$column]);
	$data = json_decode($userjson, true);
}else{
	$data = [];
}
//Wert neu setzten
foreach ($json_nodes as $key => $value) {
	if (strpos($key, 'md5_') !== false){
		$key = str_replace("md5_","",$key);
		for ($s = '', $i = 0, $z = strlen($a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')-1; $i != 32; $x = rand(0,$z), $s .= $a[$x], $i++); 
		$str = htmlspecialchars($value, ENT_QUOTES).$s;
		$strmd5 = md5($str);
		$value = $strmd5.':'.$s;
	}
	if (strpos($key, 'sha256_') !== false){ //strikes Hashing nach sha256
		$key = str_replace("sha256_","",$key);
		$value = hash("sha256",htmlspecialchars($value,ENT_QUOTES),false);
		echo "<br>Ich bins!<br><br>";
	}
	$data[0][$key] = $value;
}
//Ausgabe
foreach ($data[0] as $key => $entry) {
	echo $key ."=>". $entry."<br>";
}
// in DB schreiben
print_r($data);
$encrypted = string_encrypt(json_encode($data, JSON_UNESCAPED_UNICODE));
$insert = $db->prepare("UPDATE ".$table." SET ".$column." = :encrypted WHERE ".$skey." = :entry");
$insert->bindValue(":encrypted", $encrypted, PDO::PARAM_STR);
$insert->bindValue(":entry", $sentry, PDO::PARAM_STR);
$insert->execute() or die(print_r($insert->errorInfo()));

if(!empty($values)){ //Sobald die Values nicht leer sind, werden diese upgedated. Unabhängig davon ob als Type JSON, JSON_APPEND, JSON_DELETE oder anderes definiert wurde.
	$and = $comma = $value = "";
	$where = $skey." = '".$sentry."'";
	foreach ($values as $name => $val){
		if (strpos($name, 'md5_') !== false){
			//echo "Key: ".$name." Value: ".$val."<br>";
			$name = str_replace("md5_","",$name);
			for ($s = '', $i = 0, $z = strlen($a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')-1; $i != 32; $x = rand(0,$z), $s .= $a[$x], $i++); 
			$str = htmlspecialchars($val, ENT_QUOTES).$s;
			$strmd5 = md5($str);
			$val = $strmd5.':'.$s;
			//echo "Modified Key: ".$name." Modified Value: ".$val."<br>";
		}
		if (strpos($name, 'sha256_') !== false){ //strikes Hashing nach sha256
			$name = str_replace("sha256_","",$name);
			$val = hash("sha256",htmlspecialchars($val,ENT_QUOTES),false);
		}
		$value .= $comma.$name." = '".$val."'";
		$and = " AND ";
		$comma = ",";
	}
	//echo $value." WHERE ".$where;
	//schreiben
	$insert = $db->prepare("UPDATE ".$table." SET ".$value." WHERE ".$where);
	$insert->execute() or die(print_r($insert->errorInfo()));
}

?>
