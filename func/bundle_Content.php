<?php 
/**
 * bundle_Content
 *
 * @author sukovanej
 */
class bundle_Content extends bundle_DatabaseBase {
	public function __construct($ID) {
		parent::__construct($ID, "bundle_content");
	}
	
	public static function Create($type, $data, $place, $home_only = false) {
		$connect = bundle_DB::Connect();
		
		$h_only = 0;
		if ($home_only)
			$h_only = 1;
			
		$count_re = $connect->query("SELECT COUNT(*) AS Count FROM bundle_content WHERE Place = '" . $place . "'")->fetch_object();
		$create_re = $connect->query("INSERT INTO bundle_content (Type, Data, HomeOnly, ContentOrder, Place) VALUES ('" . $type . "'," . $data . "," 
			. $h_only . "," . $count_re->Count . ", '" . $place . "')");
		
		return $connect->insert_id;
	}
	
	public static function GetByData($type, $data) {
		$connect = bundle_DB::Connect();
		$row = $connect->query("SELECT ID, COUNT(*) as Count FROM bundle_content WHERE Data = " . $data . " AND Type = '" . $type . "'")->fetch_object();
		if ($row->Count == 0)
			return false;
			
		return new bundle_Content($row->ID);
	}
	
	public static function GetByOrderPlace($place, $order) {
		$connect = bundle_DB::Connect();
		$row = $connect->query("SELECT ID, COUNT(*) as Count FROM bundle_content WHERE ContentOrder = " . $order . " AND Place = '" . $place . "'")->fetch_object();
		
		if ($row->Count == 0)
			return false;
			
		return new bundle_Content($row->ID);
	}
	
	public static function ListByPlace($place) {
		$connect = bundle_DB::Connect();
		
		$re = $connect->query("SELECT ID FROM bundle_content WHERE Place = '" . $place . "' ORDER BY ContentOrder");
		
		if ($re->num_rows == 0)
			return false;
		
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new bundle_Content($row->ID);
			
		return $array;
	}
	
	public static function CountByPlace($place) {
		$connect = bundle_DB::Connect();
		
		$re = $connect->query("SELECT COUNT(*) AS Count FROM bundle_content WHERE Place = '" . $place . "' ORDER BY ContentOrder");
		
		return $re->fetch_object()->Count;
	}
	
	public function Delete() {
		parent::Delete();
		
		bundle_DB::Connect()->query("UPDATE bundle_content SET ContentOrder = ContentOrder - 1 WHERE Place = '" 
			. $this->Place . "' AND ContentOrder > " . $this->ContentOrder);
	}
	
	public static function CountAll() {
		$connect = bundle_DB::Connect();
		$re = $connect->query("SELECT COUNT(*) AS Count FROM bundle_content")->fetch_object();
		return $re->Count;
	}
}
