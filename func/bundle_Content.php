<?php 
/**
 * Content
 *
 * @author sukovanej
 */
 
namespace Bundle; 
 
class Content extends DatabaseBase {
	/**
	 * Vytvo�it instanci
	 *
	 * @param int $ID ID obsahu
	 *
	 */	
	public function __construct($ID) {
		parent::__construct($ID, "content");
	}
	
	/**
	 * Vytvo�it nov� obsah
	 *
	 * @param int $type Typ obsahu
	 * @param int $data ID z�znamu, kter� bud obsah generovat
	 * @param string $place Oblast, ve kter� se bud obsah vykreslovat
	 * @param bool $home_only Zobrazit pouze na hlavn� str�nce?
	 * @return int ID nov�ho z�znamu s obsahem
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
	 * Vr�tit podle typu a ID z�znamu
	 *
	 * @param string $type Typ obsahu
	 * @param int $data ID generuj�c�ho z�znamu
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
	 * Vr�tit z�znam podle oblasti a po�ad�
	 *
	 * @param strign $place Oblast
	 * @param int $order Po�ad� z�znamu
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
	 * Vr�tit pole objekt� t��dy Content podle oblasti
	 *
	 * @param string $place Oblast
	 * @return array Pole objekt� t��dy Bundle\Content
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
	 * Vr�tit pole objekt� t��dy Content podle typu
	 *
	 * @param string $type Typ
	 * @return array Pole objekt� t��dy Bundle\Content
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
	 * Vr�tit pole objekt� t��dy Content podle typu a ID
	 *
	 * @param string $type Typ generuj�c�ho obsahu
	 * @param int $data ID generuj�ho z�znamu
	 * @return array Pole objekt� t��dy Bundle\Content
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
	 * Spo��tat obsahy podle oblasti vykreslov�n�
	 *
	 * @param string $place Oblast
	 * @return int Po�et nalezen�ch z�znam�
	 *
	 */	
	public static function CountByPlace($place) {
		$connect = DB::Connect();
		
		$re = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "content WHERE Place = '" . $place . "' ORDER BY ContentOrder");
		
		return $re->fetch_object()->Count;
	}
	
	/**
	 * Smazat aktu�ln� z�znam
	 *
	 *
	 */	
	public function Delete() {
		parent::Delete();
		
		DB::Connect()->query("UPDATE " . DB_PREFIX . "content SET ContentOrder = ContentOrder - 1 WHERE Place = '" 
			. $this->Place . "' AND ContentOrder > " . $this->ContentOrder);
	}
	
	/**
	 * Spo��tat v�echny oblasti
	 *
	 * @return int Po�et v�ech existuj�c�ch oblast� generov�n�
	 *
	 */	
	public static function CountAll() {
		$connect = DB::Connect();
		$re = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "content")->fetch_object();
		return $re->Count;
	}
}
