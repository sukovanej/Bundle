<?php
	$error = "";
	$result = "";
	
	if (isset($_POST["submit_1"])) {
		@$mysqli = new mysqli($_POST["data_host"], $_POST["data_name"], $_POST["data_password"], $_POST["data_db"]);
		@$mysqli->set_charset("utf8");
		
		function IsWritable($folder) {
			return substr(sprintf('%o', fileperms($folder)), -4) == "0774" ? "true" : "false";
		}
			
		if (mysqli_connect_errno()) {
			$error = mysqli_connect_error() . "\n";
		} else if (!IsWritable("upload")) {
			$error .= "Složka upload nemá nastavená práva pro zápis.";
		} else if (!IsWritable("upload/users")) {
			$error .= "Složka upload/users nemá nastavená práva pro zápis.";
		} else if (empty($_POST["web_name"]) || empty($_POST["web_author"])) {
			$error .= "Je potřeba vyplnit jak název webu, tak i jméno autora webu. Obojí "
					. "je možno později změnit v administraci.\n";
		} else if (empty($_POST["admin_name"]) || empty($_POST["admin_password"]) ||
				empty($_POST["admin_password_again"]) || empty($_POST["admin_email"])) { 
			$error .= "Pro vytvoření administrátorského účtu je potřeba vyplnit"
					. "všechny údaje";
		} else if ($_POST["admin_password"] != $_POST["admin_password_again"]) {
			$error .= "Hesla se musí shodovat";
		} else {
			@file_put_contents("config.ini", 
					  "user=" . $_POST["data_name"] . "\n"
					. "password=" . $_POST["data_password"] . "\n"
					. "host=" . $_POST["data_host"] . "\n"
					. "database=" . $_POST["data_db"] . "\n"
					. "db_prefix=" . $_POST["data_db_prefix"]
					);
			
			if(filesize("config.ini") == 0) {
				$error .= "Nepodařilo se uložit konfigurační soubor <em>config.ini</em>, musíte pro něj"
					. "ručně nastavit práva zápisu.";
			} else {
				$mysqli->select_db($_POST["data_db"]);
				SplitSQL("install/install.sql", $mysqli);
				
				require("func/bundle_Loader.php");
				require("helpers/HPackage.php");
				define("DB_PREFIX", (new Bundle\IniConfig("config.ini"))->db_prefix);
				
				foreach($_POST["packages"] as $package)
					$result .= HPackage::installManually($package, false)[1];
					
				Bundle\User::Create($_POST["admin_name"], $_POST["admin_password"], $_POST["admin_email"]);
				Bundle\DB::Connect()->query("UPDATE " . DB_PREFIX . "users SET Role = 0");
				$result .= "Administrátorský účet vytvořen";
				
				$done = true;
				$error = $mysqli->error;
			}
		}
		
		@$mysqli->close();
	}
