<?php 
/**
 * Content
 *
 * @author sukovanej
 */
 
namespace Bundle; 
 
class Content extends DatabaseBase {
	public function __construct($ID) {
		parent::__construct($ID, "content");
	}
	
	public static function Create($type, $data, $place, $home_only = false) {
		$connect = DB::Connect();
		
			$type = $connect->real_escape_string($type);
		
		$h_only = 0;
		if ($home_only)
			$h_only = 1;
			
		$count_re = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "content WHERE Place = '" . $place . "'")->fetch_object();
		$create_re = $connect->query("INSERT INTO " . DB_PREFIX . "content (Type, Data, HomeOnly, ContentOrder, Place) VALUES ('" . $type . "'," . $data . "," 
			. $h_only . "," . $count_re->Count . ", '" . $place . "')");
		
		return $connect->insert_id;
	}
	
	public static function GetByData($type, $data) {
		$connect = DB::Connect();
		
			$type = $connect->real_escape_string($type);
		
		$row = $connect->query("SELECT ID, COUNT(*) as Count FROM " . DB_PREFIX . "content WHERE Data = " . $data . " AND Type = '" . $type . "'")->fetch_object();
		if ($row->Count == 0)
			return false;
			
		return new Content($row->ID);
	}
	
	public static function GetByOrderPlace($place, $order) {
		$connect = DB::Connect();
		
			$place = $connect->real_escape_string($place);
		
		$row = $connect->query("SELECT ID, COUNT(*) as Count FROM " . DB_PREFIX . "content WHERE ContentOrder = " . $order . " AND Place = '" . $place . "'")->fetch_object();
		
		if ($row->Count == 0)
			return false;
			
		return new Content($row->ID);
	}
	
	public static function ListByPlace($place) {
		$connect = DB::Connect();
		
			$place = $connect->real_escape_string($place);
		
		$re = $connect->query("SELECT ID FROM " . DB_PREFIX . "content WHERE Place = '" . $place . "' ORDER BY ContentOrder");
		
		if ($re->num_rows == 0)
			return false;
		
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Content($row->ID);
			
		return $array;
	}
	
	public static function ListByType($type) {
		$connect = DB::Connect();
		
			$type = $connect->real_escape_string($type);
		
		$re = $connect->query("SELECT ID FROM " . DB_PREFIX . "content WHERE Type = '" . $type . "' ORDER BY ContentOrder");
		
		if ($re->num_rows == 0)
			return false;
		
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Content($row->ID);
			
		return $array;
	}
	
	public static function ListByData($type, $data) {
		$connect = DB::Connect();
		
			$type = $connect->real_escape_string($type);
		
		$re = $connect->query("SELECT ID FROM " . DB_PREFIX . "content WHERE Type = '" . $type . "' AND Data = " . $data . " ORDER BY ContentOrder");
		
		if ($re->num_rows == 0)
			return false;
		
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Content($row->ID);
			
		return $array;
	}
	
	public static function CountByPlace($place) {
		$connect = DB::Connect();
		
			$place = $connect->real_escape_string($place);
		
		$re = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "content WHERE Place = '" . $place . "' ORDER BY ContentOrder");
		
		return $re->fetch_object()->Count;
	}
	
	public function Delete() {
		parent::Delete();
		
		DB::Connect()->query("UPDATE " . DB_PREFIX . "content SET ContentOrder = ContentOrder - 1 WHERE Place = '" 
			. $this->Place . "' AND ContentOrder > " . $this->ContentOrder);
	}
	
	public static function CountAll() {
		$connect = DB::Connect();
			$re = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "content")->fetch_object();
			
		return $re->Count;
	}
}
