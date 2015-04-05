<?php 
/**
 * Content
 *
 * @author sukovanej
 */
 
namespace Bundle; 
 
class Content extends DatabaseBase {
	/**
	 * Vytvoøit instanci
	 *
	 * @param int $ID ID obsahu
	 *
	 */	
	public function __construct($ID) {
		parent::__construct($ID, "content");
	}
	
	/**
	 * Vytvoøit novı obsah
	 *
	 * @param int $type Typ obsahu
	 * @param int $data ID záznamu, kterı bud obsah generovat
	 * @param string $place Oblast, ve které se bud obsah vykreslovat
	 * @param bool $home_only Zobrazit pouze na hlavní stránce?
	 * @return int ID nového záznamu s obsahem
	 *
	 */	
	public static function Create($type, $data, $place, $home_only = false) {
		$connect = DB::Connect();
		
		$h_only = 0;
		if ($home_only)
			$h_only = 1;
			
		$count_re = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "content WHERE Place = '" . $place . "'")->fetch_object();
		$create_re = $connect->query("INSERT INTO " . DB_PREFIX . "content (Type, Data, HomeOnly, ContentOrder, Place) VALUES ('" . $type . "'," . $data . "," 
			. $h_only . "," . $count_re->Count . ", '" . $place . "')");
		
		return $connect->insert_id;
	}
	
	/**
	 * Vrátit podle typu a ID záznamu
	 *
	 * @param string $type Typ obsahu
	 * @param int $data ID generujícího záznamu
	 * @return Content Objekt s obsahem
	 *
	 */	
	public static function GetByData($type, $data) {
		$connect = DB::Connect();
		$row = $connect->query("SELECT ID, COUNT(*) as Count FROM " . DB_PREFIX . "content WHERE Data = " . $data . " AND Type = '" . $type . "'")->fetch_object();
		if ($row->Count == 0)
			return false;
			
		return new Content($row->ID);
	}
	
	/**
	 * Vrátit záznam podle oblasti a poøadí
	 *
	 * @param strign $place Oblast
	 * @param int $order Poøadí záznamu
	 * @return Content Objekt s obsahem
	 *
	 */	
	public static function GetByOrderPlace($place, $order) {
		$connect = DB::Connect();
		$row = $connect->query("SELECT ID, COUNT(*) as Count FROM " . DB_PREFIX . "content WHERE ContentOrder = " . $order . " AND Place = '" . $place . "'")->fetch_object();
		
		if ($row->Count == 0)
			return false;
			
		return new Content($row->ID);
	}
	
	/**
	 * Vrátit pole objektù tøídy Content podle oblasti
	 *
	 * @param string $place Oblast
	 * @return array Pole objektù tøídy Bundle\Content
	 *
	 */	
	public static function ListByPlace($place) {
		$connect = DB::Connect();
		
		$re = $connect->query("SELECT ID FROM " . DB_PREFIX . "content WHERE Place = '" . $place . "' ORDER BY ContentOrder");
		
		if ($re->num_rows == 0)
			return false;
		
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Content($row->ID);
			
		return $array;
	}
	
	/**
	 * Vrátit pole objektù tøídy Content podle typu
	 *
	 * @param string $type Typ
	 * @return array Pole objektù tøídy Bundle\Content
	 *
	 */
	public static function ListByType($type) {
		$connect = DB::Connect();
		
		$re = $connect->query("SELECT ID FROM " . DB_PREFIX . "content WHERE Type = '" . $type . "' ORDER BY ContentOrder");
		
		if ($re->num_rows == 0)
			return false;
		
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Content($row->ID);
			
		return $array;
	}
	
	/**
	 * Vrátit pole objektù tøídy Content podle typu a ID
	 *
	 * @param string $type Typ generujícího obsahu
	 * @param int $data ID generujího záznamu
	 * @return array Pole objektù tøídy Bundle\Content
	 *
	 */
	public static function ListByData($type, $data) {
		$connect = DB::Connect();
		
		$re = $connect->query("SELECT ID FROM " . DB_PREFIX . "content WHERE Type = '" . $type . "' AND Data = " . $data . " ORDER BY ContentOrder");
		
		if ($re->num_rows == 0)
			return false;
		
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Content($row->ID);
			
		return $array;
	}
	
	/**
	 * Spoèítat obsahy podle oblasti vykreslování
	 *
	 * @param string $place Oblast
	 * @return int Poèet nalezenıch záznamù
	 *
	 */	
	public static function CountByPlace($place) {
		$connect = DB::Connect();
		
		$re = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "content WHERE Place = '" . $place . "' ORDER BY ContentOrder");
		
		return $re->fetch_object()->Count;
	}
	
	/**
	 * Smazat aktuální záznam
	 *
	 *
	 */	
	public function Delete() {
		parent::Delete();
		
		DB::Connect()->query("UPDATE " . DB_PREFIX . "content SET ContentOrder = ContentOrder - 1 WHERE Place = '" 
			. $this->Place . "' AND ContentOrder > " . $this->ContentOrder);
	}
	
	/**
	 * Spoèítat všechny oblasti
	 *
	 * @return int Poèet všech existujících oblastí generování
	 *
	 */	
	public static function CountAll() {
		$connect = DB::Connect();
		$re = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "content")->fetch_object();
		return $re->Count;
	}
}
