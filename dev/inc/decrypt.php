<?php
require "../../../secure/info.inc.php";
require "../../../secure/secret.php";
require "../verschluesseln.php";
$file = $_GET['href'];
$eid = $_GET['eid'];
if($eid != ""){
	if(file_exists("../../../secure/data/".$eid."/".$file.".txt")){
		$encrypted_txt = file_get_contents("../../../secure/data/".$eid."/".$file.".txt");
	}else{
		echo "Error: ".$eid."/".$file.".txt existiert nicht";
	}
}else{
	if(file_exists("../../../secure/data/".$file.".txt")){
		$encrypted_txt = file_get_contents("../../../secure/data/".$file.".txt");
	}else{
		echo "Error: ".$file.".txt existiert nicht";
	}
}
$decrypted_txt = decrypt($encrypted_txt);
echo $decrypted_txt;
?>