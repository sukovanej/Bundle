<?php

/**
 * Bundle\MenuItem
 *
 * @author sukovanej
 */
namespace Bundle;  
 
class MenuItem extends DatabaseBase {
	
	/**
	 * Vytvo�it instanci
	 *
	 * @param int $ID ID z�znamu
	 *
	 */
    public function __construct($ID) {
        parent::__construct($ID, "menu");
    }
    
	/**
	 * Vytvo�it instanci t��dy Bundle\MenuItem podle data a typu z�znamu
	 *
	 * @param int $data ID z�znamu
	 * @param string $type Typ z�znamu
	 * @return MenuItem Objekt reprezentuj�c� polo�ku menu
	 *
	 */	
	public static function InstByData($data, $type) {
		$connect = DB::Connect();
		
			$type = $connect->real_escape_string($type);
		
		$url = $connect->query("SELECT ID FROM " . DB_PREFIX . "urls WHERE Data = " . $data . " AND Type = '" . $type . "'")->fetch_object()->ID;
		$r = $connect->query("SELECT ID FROM menu WHERE Url = '" . $url . "'");
		
		if ($r->num_rows == 0)
			return false;
			
		return new MenuItem($r->fetch_object()->ID);
	}
	
	/**
	 * Vytvo�it instanci podle url adresy
	 *
	 * @param int $url ID z�znamu URl adresy
	 * @return MenuItem Objekt reprezentuj�c� polo�ku menu
	 *
	 */	
	public static function InstByUrl($url) {
		$connect = DB::Connect();
		
			$url = $connect->real_escape_string($url);
		
		$url = Url::InstByUrl($url)->ID;
		$r = $connect->query("SELECT ID FROM " . DB_PREFIX . "menu WHERE Url = " . $url);
		
		if ($r->num_rows == 0)
			return false;
			
		return new MenuItem($r->fetch_object()->ID);
	}
	
	/**
	 * Vr�tit pod�azen� polo�ky aktu�ln�ho objektu
	 *
	 * @return array Pole objekt� t��dy Bundle\MenuItem
	 *
	 */	
	public function Children() {
		$re = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "menu WHERE Parent = " . $this->ID);
		$array = array();
		
		while($row = $re->fetch_object()) {
			$array[] = new MenuItem($row->ID);
		}
				
		return $array;
	}
	
	/**
	 * Posunout aktu�ln� polo�ku o jednu pozici nahoru
	 *
	 */	
	public function Up() {
		DB::Connect()->query("UPDATE " . DB_PREFIX . "menu SET MenuOrder = MenuOrder + 1 WHERE Parent = " 
			. $this->Parent . " AND MenuOrder = " . ($this->MenuOrder - 1));
		$this->Update("MenuOrder", $this->MenuOrder - 1);
	}
	
	/**
	 * Posunout aktu�ln� polo�ku o jednu pozici dol�
	 *
	 */	
	public function Down() {
		DB::Connect()->query("UPDATE " . DB_PREFIX . "menu SET MenuOrder = MenuOrder - 1 WHERE Parent = " 
			. $this->Parent . " AND MenuOrder = " . ($this->MenuOrder + 1));
		$this->Update("MenuOrder", $this->MenuOrder + 1);
	}
	
	/**
	 * Smazat polo�ku
	 *
	 */	
	public function Delete() {
		parent::Delete();
		
		DB::Connect()->query("UPDATE " . DB_PREFIX . "menu SET MenuOrder = MenuOrder - 1 WHERE Parent = " . $this->Parent . " AND MenuOrder > " . $this->MenuOrder);
		
		foreach($this->Children() as $item)
			$item->Delete();
	}

}
