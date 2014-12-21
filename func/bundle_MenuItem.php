<?php

/**
 * Bundle\MenuItem
 *
 * @author sukovanej
 */
namespace Bundle;  
 
class MenuItem extends DatabaseBase {
    public function __construct($ID) {
        parent::__construct($ID, "menu");
    }
    
	public static function InstByData($data, $type) {
		$url = DB::Connect()->query("SELECT ID FROM " . $conf->db_prefix . "urls WHERE Data = " . $data . " AND Type = '" . $type . "'")->fetch_object()->ID;
		$r = DB::Connect()->query("SELECT ID FROM menu WHERE Url = '" . $url . "'");
		
		if ($r->num_rows == 0)
			return false;
			
		return new MenuItem($r->fetch_object()->ID);
	}
	
	public static function InstByUrl($url) {
		$url = Url::InstByUrl($url)->ID;
		$r = DB::Connect()->query("SELECT ID FROM " . $conf->db_prefix . "menu WHERE Url = " . $url);
		
		if ($r->num_rows == 0)
			return false;
			
		return new MenuItem($r->fetch_object()->ID);
	}
	
	public function Children() {
		$re = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "menu WHERE Parent = " . $this->ID);
		$array = array();
		
		while($row = $re->fetch_object()) {
			$array[] = new MenuItem($row->ID);
		}
				
		return $array;
	}
	
	public function Up() {
		DB::Connect()->query("UPDATE " . DB_PREFIX . "menu SET MenuOrder = MenuOrder + 1 WHERE Parent = " 
			. $this->Parent . " AND MenuOrder = " . ($this->MenuOrder - 1));
		$this->Update("MenuOrder", $this->MenuOrder - 1);
	}
	
	public function Down() {
		DB::Connect()->query("UPDATE " . DB_PREFIX . "menu SET MenuOrder = MenuOrder - 1 WHERE Parent = " 
			. $this->Parent . " AND MenuOrder = " . ($this->MenuOrder + 1));
		$this->Update("MenuOrder", $this->MenuOrder + 1);
	}
	
	public function Delete() {
		parent::Delete();
		
		DB::Connect()->query("UPDATE " . DB_PREFIX . "menu SET MenuOrder = MenuOrder - 1 WHERE Parent = " . $this->Parent . " AND MenuOrder > " . $this->MenuOrder);
		
		foreach($this->Children() as $item)
			$item->Delete();
	}

}
