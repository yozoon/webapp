<?php
session_start();
if(!isset($_SESSION["etrax"]["usertype"]) && $_SESSION["etrax"]["adminOID"] == "DEV"){
header("Location: index.php");
}
require "../secure/info.inc.php";
require "../secure/secret.php";
require "dev/verschluesseln.php";
if(isset($_POST["database"])){
	$database = $_POST["database"];
}else{
	$database="";
}

?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<title>eTrax|rescue</title>
		<meta charset="UTF-8">
		<meta name="description" content="GPS Einsatztrackingtool">
		<meta name="author" content="Phlipp Toscani & Nicolaus Piso">
		<meta name="creator" content="Nicolaus Piso">
		<meta name="robots" content="noindex,nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=yes">
		<link rel="shortcut icon" href="dev/img/icon.png" type="image/png">
		<link rel="stylesheet" href="v5/css/styles.css">
		<script src="dev/vendor/js/jquery-3.5.1.min.js"></script>
		<style>
		body{
			background:#fff;
		}
		.table td {
			max-width: 400px;
			max-height: 600px;
			overflow-y:auto;
		}
		.table td.tdtrue div {
			max-height: 600px;
			overflow-y:auto;
			overflow-x: hidden;
			word-wrap: anywhere;
			width: 300px;
		}
		.table td.tdlogo div {
			max-height: 100px;
			overflow:hidden;
		}
		</style>
	</head>
	<body>

		<form action="readdatabase.php" method="post" >
				
		<div class="form-group">
			<label for="database">Datenbank:</label>
			<select name="database" id="database">
				<?php
				
				$tables = $db->query("SHOW TABLES");

				while ($row = $tables->fetch(PDO::FETCH_NUM)) {
					if(strpos($row[0],'tracking') === false){
						echo "<option value=".$row[0].">$row[0]</option>";
					}
				}
				?>
			</select>
			<input type="submit" value="anzeigen">
		</div>
		<div>Datenbank Tabelle: <b><?php echo $database;?></b></div>
		</form>
		<table class="table table-bordered table-striped">
		<?php
			
			if(isset($_POST["database"])){
				$header=0;
				//print_r($pie);
				//User aus der DB holen
				$db_query = $db->prepare("SELECT * FROM ".$database);
				$db_query->execute($db_query->errorInfo());
				while ($result = $db_query->fetch(PDO::FETCH_ASSOC)){
					if($header==0){
						$header='1'; 
						echo "<tr>";
						foreach($result as $title => $val){ 
							echo '<th>'.$title.'</th>';  
						} 
						echo "</tr>"; 
					}
						echo "<tr>";
							foreach($result as $key => $value)
							{
								$output = "";
								if($key == "data" || $key == "pois" || $key == "gruppen" || $key == "suchgebiete" || $key == "gesucht" || $key == "einteilung" || $key == "personen_im_einsatz" || $key == "messages" || $key == "funk" || $key == "protokoll" || $key == "orginfo" || $key == "checkliste"){
									if($value != ""){
										$output = string_decrypt($value);
									}
									$btn_class = 'json';
								}else{
									if($value != ""){
										$output = decryptdb($value,$database,$key);
									}
									$btn_class = '';
									$EID = ($key == 'EID') ? $value : '';
								}
								if($output == "")$output = "--";
								if($key != "pwd"){$edit = "true";$save = "<button type='button' contentEditable='false' class='btn btn-primary d-none savechanges $btn_class' data-id='$EID' data-key='$key' data-where='and_$key' data-value='$output'>speichern</button>";}else{$edit = "false";$save = "";}
								echo "<td class='td$edit td$key'><div class='value' contentEditable='$edit'>$output</div> $save</td>";
							}
						echo "</tr>";
					
				} 
			}
            ?>
        </table>
	</body>
	<script>
		
	jQuery(".value").focus(function() {
		$(".btn").addClass("d-none");
		$(this).parent().find(".btn").removeClass("d-none");
	});
	jQuery(".savechanges").click(function() {
		$(this).addClass("d-none");
		let value = jQuery(this).parent().find(".value").html();console.log(value);
		let id = jQuery(this).attr("data-id");
		let keyname = jQuery(this).attr("data-key");
		let where = jQuery(this).attr("data-where");
		let encrypt = ($(this).hasClass('json')) ? false : true;
			var data = {};
				data['table'] = "<?php echo $database;?>";
				data['and_ID'] = id;
				data[''+keyname+''] = ''+value+'';
				data['encrypt'] = encrypt;
			jQuery.ajax({
				url: "write/updatedb.php",
				type: "post",
				data: data
			});
	});
	</script>
</html>