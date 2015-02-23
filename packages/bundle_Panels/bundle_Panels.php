<?php
if(!defined("_BD"))
	die();

class bundle_Panels extends Bundle\PackageBase {
	public $includes;
	public $place;
	public $home_only;
	
	// Inicialize
	public function __construct() {
		$this->includes = array(
			"bundle_Panels_DB.php"
		);
		
		$this->place = "panel";
		$this->home_only = false;
		
		parent::__construct();
	}
	
	// Install
	public function install() {
		if(Bundle\DB::Connect()->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "panels (
			ID int(11) NOT NULL AUTO_INCREMENT,
			Content varchar(1000) COLLATE utf8_czech_ci NOT NULL,
			Title varchar(100) COLLATE utf8_czech_ci NOT NULL,
			PRIMARY KEY (ID)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;")) {
			return true;
		}
		
		return false;
	}
	
	public function Generate() {
		return bundle_Panels_DB::Generate();
	}
	
	public function uninstall() {
		if(Bundle\DB::Connect()->query("DROP TABLE " . DB_PREFIX . "panels")) {
			return true;
		}
		
		return false;
	}
}
