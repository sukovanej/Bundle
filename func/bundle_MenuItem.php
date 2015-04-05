<?php

/**
 * Bundle\MenuItem
 *
 * @author sukovanej
 */
namespace Bundle;  
 
class MenuItem extends DatabaseBase {
	
	/**
	 * Vytvoøit instanci
	 *
	 * @param int $ID ID záznamu
	 *
	 */
    public function __construct($ID) {
        parent::__construct($ID, "menu");
    }
    
	/**
	 * Vytvoøit instanci tøídy Bundle\MenuItem podle data a typu záznamu
	 *
	 * @param int $data ID záznamu
	 * @param string $type Typ záznamu
	 * @return MenuItem Objekt reprezentující položku menu
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
	 * Vytvoøit instanci podle url adresy
	 *
	 * @param int $url ID záznamu URl adresy
	 * @return MenuItem Objekt reprezentující položku menu
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
	 * Vrátit podøazené položky aktuálního objektu
	 *
	 * @return array Pole objektù tøídy Bundle\MenuItem
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
	 * Posunout aktuální položku o jednu pozici nahoru
	 *
	 */	
	public function Up() {
		DB::Connect()->query("UPDATE " . DB_PREFIX . "menu SET MenuOrder = MenuOrder + 1 WHERE Parent = " 
			. $this->Parent . " AND MenuOrder = " . ($this->MenuOrder - 1));
		$this->Update("MenuOrder", $this->MenuOrder - 1);
	}
	
	/**
	 * Posunout aktuální položku o jednu pozici dolù
	 *
	 */	
	public function Down() {
		DB::Connect()->query("UPDATE " . DB_PREFIX . "menu SET MenuOrder = MenuOrder - 1 WHERE Parent = " 
			. $this->Parent . " AND MenuOrder = " . ($this->MenuOrder + 1));
		$this->Update("MenuOrder", $this->MenuOrder + 1);
	}
	
	/**
	 * Smazat položku
	 *
	 */	
	public function Delete() {
		parent::Delete();
		
		DB::Connect()->query("UPDATE " . DB_PREFIX . "menu SET MenuOrder = MenuOrder - 1 WHERE Parent = " . $this->Parent . " AND MenuOrder > " . $this->MenuOrder);
		
		foreach($this->Children() as $item)
			$item->Delete();
	}

}
