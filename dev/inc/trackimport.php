<?php
$sender = isset($_POST['gpxsender']) ? $_POST['gpxsender'] : null;
$gruppe = isset($_POST['gpxgruppe']) ? $_POST['gpxgruppe'] : null;
$gruppe = $gruppe != 0 ? $gruppe : null;
$UID = isset($_POST['gpxsenderUID']) ? $_POST['gpxsenderUID'] : null;
$OID = isset($_POST['gpxsenderOID']) ? $_POST['gpxsenderOID'] : null;
$nummer = isset($_POST['gpxsenderDNR']) ? $_POST['gpxsenderDNR'] : null;
$gpx = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : null;
$EID = isset($_POST['EID']) ? $_POST['EID'] : null;
$EID = intval($EID);
require "../../../secure/info.inc.php";

if(isset($gpx)){	
	//if($gpx['type'] == "application/xml"){
		$uploaddir = '../gpximport';
		move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir."/".$gpx);
	//}
	if (file_exists($uploaddir."/".$gpx)) {
		$xml = simplexml_load_file($uploaddir."/".$gpx);
		foreach( $xml->children() AS $child ) {
			$name = $child->getName();
			if ($name == 'trk') {
				foreach( $child->children() AS $grandchild ) {
					$grandname = $grandchild->getName();
					if ($grandname == 'name') {
						//echo '<hr>Trackname: '.$grandchild.'<br> Sender: '.$sender.'<br> EID: '.$EID.'<br>';
					}
					if ($grandname == 'trkseg') {
						
						foreach( $grandchild->children() AS $greatgrandchild ) {
							$greatgrandname = $greatgrandchild->getName();
							if ($greatgrandname == 'trkpt') {
								$lat=$greatgrandchild['lat'];
								$lon=$greatgrandchild['lon'];
								foreach( $greatgrandchild->children() AS $elegreatgrandchild ) {
									if($elegreatgrandchild->getName()=='time'){$zeit=$elegreatgrandchild;}
									if($elegreatgrandchild->getName()=='ele'){$ele=$elegreatgrandchild;}else{$ele="268";}
								}
							}
							if ($greatgrandname == 'ele') {
								print_r('time'.$greatgrandchild);
							}
							$zeit = str_replace("T", " ", $zeit);
							$zeit = str_replace("Z", "", $zeit);
							$t = (strtotime($zeit)*1000);
							$insert = $db->prepare("INSERT INTO tracking (UID,OID,lat,lon,timestamp,hdop,altitude,speed,herkunft,EID,nummer,gruppe) VALUES ('".$UID."','".$OID."','".$lat."','".$lon."','".$t."','0','".$ele."','0','GPX',".$EID.",'".$nummer."','".$gruppe."')");
							$insert->execute() or die(print_r($insert->errorInfo()));
						}
					}
				}
			} 
		}
		unlink($uploaddir."/".$gpx);
	} else {
	echo $gpx['type'];
		exit(' Failed to open gpx.');
	}
}else{
	echo 'no gpxfile';
	}
?>