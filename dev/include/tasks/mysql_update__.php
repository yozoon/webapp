<?php

//die in $select übergebenen Werte werden zur Auswahl von WHERE genützt um eine exakte Auswahl in der DB zu ermöglichen
if(isset($select)){	
	$where = "";
	$x = 0;
	foreach ($select as $skey => $sentry) {
		if($x >0 ){
			$where = $where." AND ".$skey." = '".$sentry."'";
		} else {
			$where = "".$skey." = '".$sentry."'";
		}
		$x++;
	}
	$delete = $db->prepare("UPDATE ".$table." SET ".$column." = ".$values." WHERE ".$where);
	$delete->execute() or die(print_r($delete->errorInfo()));
} else {
	echo "Es wurde kein Feld zur Identifikation angegeben!";
}

?>
