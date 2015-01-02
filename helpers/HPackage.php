<?php

class HPackage {
	public static function getLastInstalled() {
		$connect = Bundle\DB::Connect();
		$p = $connect->query("SELECT ID FROM " . DB_PREFIX . "packages ORDER BY ID DESC LIMIT 1")->fetch_object();
		
		return $p->ID;
	}
	
	public static function getPath($pack) {
		return "./packages/" . $pack;
	}
	
	public static function installManually($Package_name, $dependences = true) {
		$Package_dir = getcwd() . "/packages/" . $Package_name;
		
		$result = array();
		
		if (Bundle\Packages::IsPackageInstalled($Package_name)) {
			$result[] = array("ERR", "Balíček je už nainstalovaný.");
		} elseif (file_exists($Package_dir . "/" . $Package_name . ".php")) {
			require($Package_dir . "/" . $Package_name . ".php");
	
			$packages = new Bundle\Packages();
			$config = new Bundle\IniConfig($Package_dir . "/info.conf");
			$install = new $Package_name();
			
			$error_dependence = 0;
			
			if (isset($install->place) && $install->place != "none")
				$result[] = array("OK", "Balík <strong>" . $config->name . "</strong> se bude vykreslovat v oblasti " . $install->place . ".");
			
			if (isset($install->home_only) && $install->home_only)
				$result[] = array("OK", "Balík bude generovat obsah pouze na hlavní stránce webu " . $install->place . ".");
			
			if ($config->dependence != "none" && $dependences == true) {
				if (strpos($config->dependence, ',') !== false) {
					$dependence = preg_split(",", $config->dependence);
					
					foreach($dependence as $package) {
						$ok = $packages->IsPackageInstalled($package);
						
						if(!$ok) {
							$error_dependence += 1;
							$result[] = array("ERR", "Zjištěna nesplněná závislost na balík " . $package . ".");
						} else {
							$result[] = array("OK", "Zjištěna splněná závilost na balík " . $package . ".");
						}
					}
				} else {
					$package = $config->dependence;
					$ok = $packages->IsPackageInstalled($package);
					
					if(!$ok) {
						$error_dependence += 1;
						$result[] = array("ERR", "Zjištěna nesplněná závislost na balík " . $package . ".");
					} else {
						$result[] = array("OK", "Zjištěna splněná závilost na balík " . $config->dependence . ".");
					}
				}
			} else if($dependences == false) {
				$result[] = array("WAR", "Sledování závislostí je programem vypnuto");
			} else {
				$result[] = array("OK", "Nezjištěny žádné závislosti");
			}

			if($error_dependence > 0) {
				$result[] = array("ERR", "Máte nevyřešené závislosti mezi balíky (" . $error_dependence . ")");
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
							$result[] = array("OK", "Úspěšně nastavena oblast pro generování balíčku.");
						}
							
						$result[] = array("OK", "Balík úspěšně nainstalován do systému!</span>");
					}
				} catch (Exception $e) {
					$result[] = array("ERR", "Byla nalezena neošetřená chyba v instalačním souboru ./plugins/" . $Package_name . "/install.php, kvůli které nelze instalaci dokončit!");
				}
			}
		} else {
			$result[] = array("ERR", "Balíček " . $Package_name . " ve složce balíčků neexistuje.");
		}
		
		return $result;
	}
}
