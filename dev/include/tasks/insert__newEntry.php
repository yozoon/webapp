<?php

$insert = $db->prepare("INSERT INTO ".$table." (".$skey.") VALUES (:encrypted)");$insert->bindValue(":encrypted", $encrypted, PDO::PARAM_STR);
$insert->bindValue(":".$skey."", $sentry, PDO::PARAM_STR);
$insert->execute() or die(print_r($insert->errorInfo()));

?>
