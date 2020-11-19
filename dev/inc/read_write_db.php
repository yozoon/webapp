<?php
	require "../include/rwdb.php";
	//require "../include/auth_middleware.php";
	if(!empty($_POST["database"]) || !empty($_GET["table"])){
		require_once "../../../secure/info.inc.php";
		require_once "../../../secure/secret.php";
		require_once "../verschluesseln.php";
		
		switch($_SERVER['REQUEST_METHOD']){
			case 'POST':
				$vars = [
					"type" => $_POST["database"]["type"],
					"action" => $_POST["database"]["action"],
					"table" => $_POST["database"]["table"],
					"column" => $_POST["database"]["column"],
					"json_nodes" => $_POST["json_nodes"],
					"values" => $_POST["values"],
					"select" => $_POST["select"],
					"decrypt" => false
				];
			break;
			case 'GET': 
					if($_GET["table"] == "settings"){
						$select = $_GET["EID"];
						$decrypt = true;
					}else{
						$select = $_GET["select"];
					}
				$vars = [
					"type" => $_GET["type"],
					"action" => $_GET["action"],
					"table" => $_GET["table"],
					"column" => $_GET["column"],
					"json_nodes" => $_GET["json_nodes"],
					"values" => $_GET["values"],
					"select" => $select,
					"decrypt" => $decrypt
				];
			break;
		}
		read_write_db($vars);
	}
?>