<?php

class HPackage {
	public static function getLastInstalled() {
		$connect = Bundle\DB::Connect();
		$p = $connect->query("SELECT ID FROM " . DB_PREFIX . "packages ORDER BY ID DESC LIMIT 1")->fetch_object();
		
		return $p->ID;
	}
	
	public static function getAutoIncrementID() {
		$connect = Bundle\DB::Connect();
		$p = $connect->query("SHOW TABLE STATUS LIKE '". DB_PREFIX . "packages'")->fetch_object();
		
		return $p->Auto_increment;
	}
	
	public static function getPath($pack) {
		return "./packages/" . $pack;
	}
	
	public static function installManually($Package_name, $dependences = true) {
		$Package_dir = "./packages/" . $Package_name;
		
		$result = array();
		
		if (Bundle\Packages::IsPackageInstalled($Package_name)) {
			$result[] = array("ERR", HLoc::l("Package is already installed."));
		} elseif (file_exists($Package_dir . "/" . $Package_name . ".php") && file_exists($Package_dir . "/info.conf")) {
			require($Package_dir . "/" . $Package_name . ".php");

			$packages = new Bundle\Packages();
			$config = new Bundle\IniConfig($Package_dir . "/info.conf");
			$install = new $Package_name();
			
			$error_dependence = 0;
			
			if (isset($config->dependence) && $config->dependence != "none" && $dependences == true) {
				if (strpos($config->dependence, ',') !== false) {
					$dependence = preg_split(",", $config->dependence);
					
					foreach($dependence as $package) {
						$ok = $packages->IsPackageInstalled($package);
						
						if(!$ok) {
							$error_dependence += 1;
							$result[] = array("ERR", HLoc::l("Dependence error") . ": " . $package . ".");
						}
					}
				} else {
					$package = $config->dependence;
					$ok = $packages->IsPackageInstalled($package);
					
					if(!$ok) {
						$error_dependence += 1;
						$result[] = array("ERR", HLoc::l("Dependence error") . ": " . $package . ".");
					}
				}
			} else if($dependences == false) {
				$result[] = array("WAR", HLoc::l("Dependencies are disabled"));
			} else {
				$result[] = array("OK", HLoc::l("No dependencies"));
			}

			if($error_dependence > 0) {
				$result[] = array("ERR", HLoc::l("Dependence error") . " (" . $error_dependence . ")");
			} else {
				try {
					if($install->install()) {
						if(isset($install->menu_title))
							$id = $packages->Install($Package_name, $install->menu_title);
						else
							$id = $packages->Install($Package_name);
							
						if(!isset($install->home_only))
							$install->home_only = false;
						
						if(isset($install->place) && $install->place != "none") {
							Bundle\Content::Create("package", $id, $install->place, $install->home_only);
						}
							
						$result[] = array("OK", HLoc::l("Package has been successfully installed") . ".");
					}
				} catch (Exception $e) {
					$result[] = array("ERR", HLoc::l("Unhandled error in") . " ./plugins/" . $Package_name . "/install.php.");
				}
			}
		} else {
			$result[] = array("ERR", HLoc::l("Package") . " " . $Package_name . " " . HLoc::l("doesn't exist") . " .");
		}
		
		return $result;
	}
}
