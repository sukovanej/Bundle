<?php

class HPackage {
	public static function getLastInstalled() {
		$connect = bundle_DB::Connect();
		$p = $connect->query("SELECT ID FROM " . DB_PREFIX . "bundle_packages ORDER BY ID DESC LIMIT 1")->fetch_object();
		
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
		} elseif (file_exists($Package_dir . "/" . $Package_dir . ".php")) {
			require($Package_dir . "/" . $Package_dir . ".php");
	
			$packages = new Bundle\Packages();
			$config = new Bundle\IniConfig($Package_dir . "/info.conf");
			$install = new $Package_dir();
			
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
			} else if($dependence == false) {
				$result[] = array("WAR", "Sledování závislostí je programem vypnuto");
			} else {
				$result[] = array("OK", "Nezjištěny žádné závislosti");
			}

			if($error_dependence > 0) {
				$result[] = array("ERR", "Máte nevyřešené závislosti mezi balíky (" . $error_dependence . ")");
			} else {
				$error = false;
				$error_file = "";
				
				foreach($install->includes as $file) {
					$_url = "./packages/" . $Package_name . "/" . $file;
					
					if (!file_exists($_url)) {
						$error = true;
						$result[] = array("ERR", "Soubor " . $file . " nebylo možné uložit.");
					}
				}
				
				if (!$error) {
					foreach($install->includes as $file) {
						$_url = "./packages/" . $Package_name . "/" . $file;
						
						Bundle\Includes::Create($_url);
						$result[] = array("OK", "Soubor " . $_url . " úspěšně uložen.");
					}
				}
					
				if(!$error) {
					$result[] = array("OK", "Všechny potřebné soubory byly uloženy.");
					
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
				} else {
					$result[] = array("ERR", "Instalace nelze dokončit, protože ve složce balíčku se nenachází všechny přiložené soubory.");
				}
			}
		} else {
			$result[] = array("ERR", "Balíček " . $Package_name . " ve složce balíčků neexistuje.");
		}
		
		return $result;
	}
}
