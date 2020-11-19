<nav class="editmenu navbar navbar-expand-lg navbar-light bg-light bg-white border-0 radius-0">
	<div class="d-flex flex-grow-1">
		<span class="w-100 d-lg-none d-block"><!-- hidden spacer to center brand on mobile --></span>
		<a class="btn border-0 btn-outline-danger" href="#">
			<i class="material-icons gray-dark pr-2">face</i><?php echo $_SESSION["etrax"]["name"]; ?>
		</a>
		<div class="w-100 text-right">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#UserNavBar">
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>
	</div>
	<div class="collapse navbar-collapse flex-grow-1 text-right" id="UserNavBar" style="padding:0px;">
		<ul class="navbar-nav ml-auto flex flex-column">
			
			<?php
			if(isset($user_arr["OID"])){
				$db_organisation = $db->prepare("SELECT usersync FROM organisation WHERE OID = '".$_SESSION["etrax"]["OID"]."'");
				$db_organisation->execute($db_organisation->errorInfo());
				
				while ($result = $db_organisation->fetch(PDO::FETCH_ASSOC)){
					$usersync = $result['usersync'];
				}
				?>
				<li class="nav-item">
					<button class="btn border-0 btn-outline-danger showuserdetails" data-usersync="<?php echo($usersync);?>" data-oid="<?php echo $user_arr["OID"];?>" data-uid="<?php echo $_SESSION["etrax"]["UID"];?>" data-name="<?php echo $user_arr["name"];?>" data-dienstnummer="<?php echo $user_arr["dienstnummer"];?>" data-typ="<?php echo $user_arr["typ"];?>" data-username="<?php echo $user_arr["name"];?>" data-email="<?php echo $user_arr["email"];?>" data-bos="<?php echo $user_arr["bos"];?>" data-telefon="<?php echo $user_arr["telefon"];?>" data-einsatzfaehig="<?php echo $user_arr["einsatzfaehig"];?>" data-notfallkontakt="<?php echo $user_arr["notfallkontakt"];?>" data-notfallinfo="<?php echo $user_arr["notfallinfo"];?>" data-kommentar="<?php echo $user_arr["kommentar"];?>" title="Nutzerdaten anzeigen und bearbeiten"><i class="material-icons gray-dark pr-2">edit</i> Userdaten bearbeiten</button>
				</li>
				<li class="nav-item">
					<button class="btn border-0 btn-outline-danger showdatenschutz"  title="Informationen zum Datenschutz anzeigen"  data-toggle="modal" data-target="#dsmodal"><i class="material-icons gray-dark pr-2">remove_red_eye</i> Datenschutzinfo</button>
				</li>
			<?php
			}
			
			if(is_numeric($userlevel) && $userlevel <= 6){ //Anzeigen Link Adminbereich für Globale und Organisations Administratoren sowie permanente Einsatzleiter
			
			?>
			<li class="nav-item">
				<a class="btn border-0 btn-outline-danger" href="admin.index.php?do=" target="_blank"><i class="material-icons gray-dark pr-2">settings_applications</i> Administrationsbereich</a>
			</li>
			<?php
			}
			?>
			<li class="nav-item">
				<a class="btn border-0 btn-outline-danger" href="index.php?logout" target="_self"><i class="material-icons gray-dark pr-2">power_settings_new</i> Logout</a>
			</li>
			
		</ul>
	</div>
</nav>