<?php

class bundle_Panels {
	public $panels;
	
	public function __construct() {
		$connect = Bundle\DB::Connect();
		$result = $connect->query("SELECT ID FROM Bundle\panels");
			
		$this->panels = array();
		
		while($row = $result->fetch_object()) {
			$panel = new bundle_Panel($row->ID);
			$this->panels[] = $panel;
		}
	}
	
	public static function Create($title, $content) {
		$connect = Bundle\DB::Connect();
		$connect->query("INSERT INTO Bundle\panels (Title, Content) VALUES ('" . $title . "', '" . $content . "')");
	}
	
	public static function Generate() {
		$connect = Bundle\DB::Connect();
		$result = $connect->query("SELECT * FROM Bundle\panels");
		
		while($row = $result->fetch_object()) {
			$panel = new bundle_Panel($row->ID);
		
			echo('<div class="panel"><h1 class="panel_title">' . $panel->Title . '</h1>');
			echo('<div class="panel_content">' . $panel->Content . '</div></div>');
		}
	}
	
	public static function IsEmpty() {
		$connect = Bundle\DB::Connect();
		$result = $connect->query("SELECT COUNT(*) AS Count FROM Bundle\panels");
		
		if ($result->fetch_object()->Count == 0)
			return true;
			
		return false;
	}
}

class bundle_Panel extends Bundle\DatabaseBase {
	public function __construct($ID) {
		parent::__construct($ID, "Bundle\panels");
	}
}
