<?php
	ob_start();
	
	define("_BD", "bundle");
	
	/** uncomment to enable PHP errors
	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);
	**/
	
	if(filesize("config.ini") == 0)
		header("location: ./install.php");
	
	// load kernel files
	session_start();
	require("func/bundle_Loader.php");
	require("helpers/HLoader.php");
	
	// db prefix -> default "bundle_"
	define("DB_PREFIX", (new Bundle\IniConfig("config.ini"))->db_prefix);
	
	// Include packages files
	foreach(Bundle\Package::GetInstalledPackages() as $package) {		
		$package_name = $package->Name;
		require("packages/" . $package_name. "/" . $package_name . ".php");
		$obj = new $package_name();
		$obj->IncludeAllFiles();
	}
	
	// Bundla 1.2: debug bug
	if(!isset($_GET["router"]))
		$_GET["router"] = "";
	else
		$_GET["router"] = urlencode($_GET["router"]);
	
	// Dynamic url
	$router = isset($_GET["router"]) ? $_GET["router"] : null;

	// Default config object
	$Page = new Bundle\Template;
	
	// User object - if user's logged in
	if (isset($_SESSION["user"])) {
		$User = new Bundle\User($_SESSION["user"]);
	}
	
	Bundle\Events::BootAllPacks(); // 
	Bundle\Events::Execute("Boot");

	// Generate content
	if (substr($router, 0, strlen("administrace")) === "administrace") {
		require("admin/index.php");
	} else if ($router == "prihlaseni") {
		require("pages/bundle_Login.php");
	} else if ($router == "registrace") {
		require("pages/bundle_Register.php");
	} else { 
		if (Bundle\Url::IsDefinedUrl($router)) {
			$Url = Bundle\Url::InstByUrl($router);

			if ($Url->Type == "article") {
				$Page->Actual = (new Bundle\Article($Url->Data))->Title;
				
				Bundle\Events::Execute("Article", array(new Bundle\Article($Url->Data)));
				
			} else if ($Url->Type == "page") {
				$Page->Actual = (new Bundle\Page($Url->Data))->Title;
				
				Bundle\Events::Execute("Page", array(new Bundle\Page($Url->Data)));
				
			} else if ($Url->Type == "category") {
				$Page->Actual = "Články v kategorii " . (new Bundle\Category($Url->Data))->Title;
				
				Bundle\Events::Execute("Category", array(new Bundle\Category($Url->Data)));
				
			} else if ($Url->Type == "package") {
				$Page->Actual = (new Bundle\Package($Url->Data))->Title;
				
				Bundle\Events::Execute("Package", array( new Bundle\Package($Url->Data)));
				
			}
		} else {
			$Page->Actual = "Hlavní stránka";
			
			Bundle\Events::Execute("Home");
			
			$Url = new stdclass();
			$Url->Type = "home";
			$Url->Url = "home";
		}
		
		if (file_exists("themes/" . $Page->Theme . "/layout_" . $Url->Type . ".php"))
			require("themes/" . $Page->Theme . "/layout_" . $Url->Type . ".php");
		else if (file_exists("themes/" . $Page->Theme . "/type_" . $Url->Url . ".php"))
			require("themes/" . $Page->Theme . "/layout_" . $Url->Url . ".php");
		else
			require("themes/" . $Page->Theme . "/layout.php");
	}
	
	Bundle\Events::Execute("Finish");
