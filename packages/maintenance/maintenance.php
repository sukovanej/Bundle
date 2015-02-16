<?php
if(!defined("_BD"))
	die();

class maintenance extends Bundle\PackageBase {	
	// Inicialize
	public function __construct() {
		parent::__construct();
	}
	
	// Install
	public function install() {
		Bundle\Events::Register(HPackage::getAutoIncrementID(), "Boot");
		return true;
	}
	
	public function uninstall() {
		return true;	
	}
	
	// event_Boot handler
	public function handle_Boot() {
		if (self::startsWith(@$_GET["router"], "administration") && @$_GET["router"] != "login" && @$_GET["router"] != "register") {
			require(HPackage::getPath("maintenance") . "/layout.php");
			die();
		}
	}

	public static function startsWith($text, $with) {
		return substr($text, 0, strlen($with)) != $with;
	}
}
