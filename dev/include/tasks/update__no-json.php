<?php

$value_to = $separate_it = '';
foreach($json_nodes as $key => $value){
	$value_to .= $separate_it.$key .' = "'.$value.'"';
	$separate_it = ', ';
}
$insert = $db->prepare('UPDATE '.$table.' SET '.$value_to.' WHERE '.$skey.' = "'.$sentry.'"');
$insert->execute() or die(print_r($insert->errorInfo()));

?>
