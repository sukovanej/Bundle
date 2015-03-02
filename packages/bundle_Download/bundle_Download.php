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
			Category int(11) NOT NULL,
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
		
		public static function Create($title, $filename, $descrip, $category = -1) {
			$connect = Bundle\DB::Connect();
			$connect->escape_string($title);
			$connect->escape_string($filename);
			$connect->escape_string($descrip);
			$connect->query("INSERT INTO " . DB_PREFIX . "download (Title, Filename, Description, Category) VALUES ('" . $title . "', '" . $filename . "', '" 
				. $descrip . "', " . $category . ")");
			$id = $connect->insert_id;
			$connect->close();
			
			return $id;
		}
		
		public static function get_files($category = -1) {
			$connect = Bundle\DB::Connect();
			
			if ($category == -1)
				$result = $connect->query("SELECT ID, Filename FROM " . DB_PREFIX . "download ORDER BY Datetime DESC");
			else
				$result = $connect->query("SELECT ID, Filename FROM " . DB_PREFIX . "download WHERE Category = " . $category . " ORDER BY Datetime DESC");
			
			$array = array();
			 
			while($row = $result->fetch_object())
				if(file_exists(getcwd() . "/upload/" . $row->Filename))
					$array[] = new bundle_Download_File_DB($row->ID);
			 
			return $array;
		}
		
		public static function Count() {
			$re = Bundle\DB::Connect()->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "download")->fetch_object();
			return $re->Count;
		}
		
		public static function CreateCategory($title) {
			$id = bundle_Download_category_DB::Create($title);
		}
		
		public static function GetCategories() {
			$cats = Bundle\DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "download_categories");
			$result = array();
			
			while($c = $cats->fetch_object()) {
				$result[] = new bundle_Download_category_DB($c->ID);
			}
				
			return $result;
		}
	}
