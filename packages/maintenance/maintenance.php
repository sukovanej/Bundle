<?php

class maintenance extends Bundle\PackageBase {	
	// Inicialize
	public function __construct() {
		parent::__construct();
	}
	
	// Install
	public function install() {
		Bundle\Events::Register(HPackage::getLastInstalled() + 1, "Boot");
		return true;
	}
	
	public function uninstall() {
		return true;	
	}
	
	// event_Boot handler
	public function handle_Boot() {
		if (substr(@$_GET["router"], 0, strlen("administrace")) != "administrace") {
			require(HPackage::getPath("maintenance") . "/layout.php");
			die();
		}
	}
}
