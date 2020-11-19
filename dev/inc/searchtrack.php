<?php
session_start();
($_SESSION["etrax"]["usertype"] ? '' : header('Location: index.php'));
require "../../../secure/info.inc.php";
require "../../../secure/secret.php";
require "../verschluesseln.php";
define("sessionstart",false);
require "../sessionhandler.php"; //Der Sessionhandler schreibt die Sessionwerte neu

/*	session_start();
	if(!isset($_SESSION["etrax"]["usertype"])){
		header("Location: index.php");
	}
	require "../../../secure/info.inc.php";
	require "../../../secure/secret.php";
	require "../verschluesseln.php";
 */

$EID = htmlspecialchars($_GET['EID']);
$getgruppe = "gruppe = '".htmlspecialchars($_GET['Gruppe'])."'";
$getid = "id = '".htmlspecialchars($_GET['id'])."'";

/*$arearesult = $db->prepare("SELECT suchgebiete FROM settings WHERE EID = ".$EID);
$arearesult->execute($arearesult->errorInfo());
$koordinaten = $arearesult->fetch(PDO::FETCH_ASSOC);
$json = string_decrypt($koordinaten['suchgebiete']);
$json_array = json_decode($json, true);*/
if(isset($_GET['Gruppe'])) {
header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="E'.$EID.'-'.htmlspecialchars($_GET['name']).'-'.htmlspecialchars($_GET['id']).'.gpx"');
//$coords = [];
//$coords = $json_array["features"][htmlspecialchars($_GET['id'])]['geometry']['coordinates'][0];

//Suchgebiete aufbauen
$einsatz_query = $db->prepare("SELECT suchgebiete FROM settings WHERE EID = ".$EID."");
$einsatz_query->execute($einsatz_query->errorInfo());
while ($einsatz = $einsatz_query->fetch(PDO::FETCH_ASSOC)){
	$suchgebiete_json = string_decrypt($einsatz['suchgebiete']);
	$suchgebiete = json_decode($suchgebiete_json, true);
	
}

//Alle Suchgebiete für Darstellung
$coords = [];
$suchgebiet = false;
if(!empty($suchgebiete["features"])){
	$nr_i = 0;
	foreach($suchgebiete["features"] as $gebiet2){
		$i = 0;
		if($suchgebiete['features'][$nr_i]['properties']['id'] == htmlspecialchars($_GET['id'])){ //Auswahl des Suchgebietes
			
			$coords = $suchgebiete['features'][$nr_i]['geometry']['coordinates'][0];
			//print_r($suchgebiete['features'][$nr_i]['geometry']['coordinates'][0]);
			if($suchgebiete['features'][$nr_i]['properties']['typ'] == "Suchgebiet"){
				$suchgebiet = true;
			}
		}
		$nr_i++;
	}
}

$time = time();
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>
	<gpx xmlns="http://www.topografix.com/GPX/1/1" creator="eTrax | rescue" version="1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.topografix.com/GPX/1/1/gpx.xsd">
		<metadata>
			<link href="www.etrax.at">
				<text>'.htmlspecialchars($_GET['name']).'-'.htmlspecialchars($_GET['Gruppe']).'</text>
			</link>
		</metadata>
	<trk>
	<name>'.htmlspecialchars($_GET['name']).'-'.htmlspecialchars($_GET['Gruppe']).'</name>
		<trkseg>';
$count = 0;
$x_temp_i = $y_temp_i = 0;
foreach ($coords as $coord) {
	if($count >= 0){
		if(isset($coord[1])){
			if($x_temp_i != $coord[0] && $y_temp_i != $coord[1]){ //Entfernt doppelte Einträge
				echo '
					<trkpt lat="'.$coord[1].'" lon="'.$coord[0].'">
						<time>'.date("Y-m-d",$time).'T'.date("H:i:s",$time).'Z</time>
					</trkpt>
				';
			}
			$x_temp_i = $coord[0];
			$y_temp_i = $coord[1];
			if($suchgebiet && ($count+1) == count($coords) && isset($coords[0][1]) && isset($coords[0][0])){ //Bei einem Suchgebiet wird als letzter Punkt der 1. Punkt nochmal gesetzt da GPX keine Polygone unterstüzt
				$time+=1000;
				echo '
				<trkpt lat="'.$coords[0][1].'" lon="'.$coords[0][0].'">
					<time>'.date("Y-m-d",$time).'T'.date("H:i:s",$time).'Z</time>
				</trkpt>
			';
			}
		}
	}
	$count++;
	$time+=1000;
}
echo '
		</trkseg>
	</trk>
</gpx>';
}?>