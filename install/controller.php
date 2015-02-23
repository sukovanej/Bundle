<?php
	define("_BD", "budnle");
	$error = "";
	$result = "";
	
	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);
	
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
				
				$prefix = $_POST["data_db_prefix"];
				
				$str = file_get_contents("install/install.sql");
				
				$str = str_replace("bundle_", $prefix, $str);
				
				file_put_contents("install/install.sql", $str);
		
				SplitSQL("install/install.sql", $mysqli);
				
				$result .= "Databázová struktura a vstupní data vytvořena <br />";
				
				require("func/bundle_Loader.php");
				require("helpers/HPackage.php");
				require("helpers/HConfiguration.php");
				require("helpers/HLoc.php");
				
				define("DB_PREFIX", (new Bundle\IniConfig("config.ini"))->db_prefix);
				
				HConfiguration::Create("BaseURL", str_replace("install.php", "", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"));
				
				foreach($_POST["packages"] as $package) {
					$rresult = (HPackage::installManually($package, false));
					
					$result .= "Nainstalován balíček <strong>" . $package . "</strong>. <br />";
					
					foreach ($rresult as $val)
						$result .= $val[1] . "<br />";
				}
					
				$user = Bundle\User::Create($_POST["admin_name"], $_POST["admin_password"], $_POST["admin_email"]);
				$user->Update("Role", 0);
				Bundle\DB::Connect()->query("UPDATE " . DB_PREFIX . "users SET Role = 0");
				$result .= "Administrátorský účet vytvořen <br />";
				
				require("themes/bootstrap/install.php");
				(new InstallTheme)->Install();
				
				$result .= "Šablona nainstalována <br />";
				
				$Package = Bundle\Package::GetPackageByName("maintenance");
				$Package->Update("IsActive", 0);
				
				$result .= "Režim údržby vypnut <br />";
				
				HConfiguration::Set("Name", @$_POST["web_name"]);
				HConfiguration::Set("Author", @$_POST["web_author"]);
				
				$done = true;
				$error = $mysqli->error;
			}
		}
		
		@$mysqli->close();
	}
