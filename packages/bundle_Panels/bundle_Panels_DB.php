<?php
if(!defined("_BD"))
	die();
		
class bundle_Panels_DB {
	public $panels;
	
	public function __construct() {
		$connect = Bundle\DB::Connect();
		$result = $connect->query("SELECT ID FROM " . DB_PREFIX . "panels");
			
		$this->panels = array();
		
		while($row = $result->fetch_object()) {
			$panel = new bundle_Panel($row->ID);
			$this->panels[] = $panel;
		}
	}
	
	public static function Create($title, $content) {
		$connect = Bundle\DB::Connect();
		$connect->query("INSERT INTO " . DB_PREFIX . "panels (Title, Content) VALUES ('" . $title . "', '" . $content . "')");
	}
	
	public static function Generate() {
		$connect = Bundle\DB::Connect();
		$result = $connect->query("SELECT * FROM " . DB_PREFIX . "panels");
		
		$rresult = array();
		
		while($row = $result->fetch_object()) {
			$rresult[] = new bundle_Panel($row->ID);
		}
		
		return $rresult;
	}
	
	public static function IsEmpty() {
		$connect = Bundle\DB::Connect();
		$result = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "panels");
		
		if ($result->fetch_object()->Count == 0)
			return true;
			
		return false;
	}
}

class bundle_Panel extends Bundle\DatabaseBase {
	public function __construct($ID) {
		parent::__construct($ID, "panels");
	}
}
