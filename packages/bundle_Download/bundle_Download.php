<?php
	class bundle_Download extends Bundle\PackageBase {
		public $menu_title;
		
		// Inicialize
		public function __construct() {
			$this->menu_title = "Ke stažení";
			$this->includes = array(
				"bundle_Download_DB.php",
				"bundle_Download_category_DB.php"
			);
			
			parent::__construct();
		}
		
		// Install
		public function install() {
			if(Bundle\DB::Connect()->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "download (
			ID int(11) NOT NULL AUTO_INCREMENT,
			Title varchar(200) COLLATE utf8_czech_ci NOT NULL,
			Filename varchar(200) COLLATE utf8_czech_ci NOT NULL,
			Description varchar(300) COLLATE utf8_czech_ci NOT NULL,
			Datetime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (ID)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;") &&
			Bundle\DB::Connect()->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "download_categories (
			ID int(11) NOT NULL AUTO_INCREMENT,
			Title varchar(200) COLLATE utf8_czech_ci NOT NULL,
			PRIMARY KEY (ID)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;")) {
				return true;
			}
			
			return false;
		}
		
		public function uninstall() {
			if(Bundle\DB::Connect()->query("DROP TABLE " . DB_PREFIX . "download") &&
			Bundle\DB::Connect()->query("DROP TABLE " . DB_PREFIX . "download_categories")) {
				return true;
			}
			
			return false;
		}
	}
