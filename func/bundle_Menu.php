<?php
/*
 * Menu
 * 
 * @author sukovanej
 */ 

namespace Bundle;  
 
class Menu {
	/**
	 * Vytvo�iti nstanci
	 *
	 * @param int $parent Nad�azen� polo�ka (v�choz�: 0)
	 *
	 */	
	public function __construct($parent = 0) {
		$this->Menu = array();
		$config = new Template(1);
		
		if ($parent == 0)
			if ($config->HomeMenu)
				if (empty($_GET['router']))
					$this->Menu[] = self::MenuObject("./", $config->HomeMenuTitle, "page", 0, 0, 0, array(), true);
				else
					$this->Menu[] = self::MenuObject("./", $config->HomeMenuTitle, "page", 0, 0, 0, array());
		
		$words_config = array(
			"article" => "Articles",
			"page" => "Pages",
			"category" => "Categories",
			"package" => "Packages"
		);
		
		$menu_items = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "menu WHERE Parent = " . $parent . " ORDER BY MenuOrder");
		
		while($item = $menu_items->fetch_object()) {
			$menu_item = new MenuItem($item->ID);
			$url = new Url($menu_item->Url);
			
			if ($config->{$words_config[trim($url->Type)] . "Menu"}) {
				$class = "Bundle\\" . ucfirst($url->Type);
				
				$refl = new \ReflectionClass($class);
				$content = $refl->newInstanceArgs(array($url->Data));
				
				$children = array();
				$menu_item = new MenuItem($item->ID);
				
				if(count($menu_item->Children()) > 0) {
					$children_menu = new Menu($menu_item->ID);
					$children = $children_menu->Menu();
				}
				
				$add = true;
				
				if ($url->Type == "package") {
					$package = new Package($url->Data);
					
					if ($package->IsActive == 0)
						$add = false;
				}
				
				if ($add)
					if ($content->Url == @$_GET["router"])
						$this->Menu[] = self::MenuObject($content->Url, $content->Title, $url->Type, $menu_item->MenuOrder, $url->Data, $menu_item->ID, $children, true);
					else
						$this->Menu[] = self::MenuObject($content->Url, $content->Title, $url->Type, $menu_item->MenuOrder, $url->Data, $menu_item->ID, $children);
			}
		}
	}
	
	/**
	 * Nov� objekt (polo�ka) menu
	 *
	 * @param strign $Url URL adresa polo�ky
	 * @param string $Title Titulek
	 * @param string $Type Typ z�znamu
	 * @param int $Order Po�ad�
	 * @param int $Data ID z�znamu
	 * @param int $ID ID
	 * @param array $Children Pod�azen� polo�ky
	 * @param bool $Current Je pr�v� zobrazovan� polo�ka?
	 * @return object Nov� objekt
	 *
	 */	
	private static function MenuObject($Url, $Title, $Type, $Order, $Data, $ID, $Children, $Current = false) {
		$obj = new \stdclass();
		$obj->Url = $Url;
		$obj->Title = $Title;
		$obj->Type = $Type;
		$obj->Order = $Order;
		$obj->Data = $Data;
		$obj->Children = $Children;
		$obj->ID = $ID;
		$obj->HasChildren = (count($obj->Children) > 0);
		
		$obj->Current = "";

		if ($Current)
			$obj->Current = "active";
		
		return $obj;
	}
	
	/**
	 * Vr�tit polo�ky menu
	 *
	 * @param int $parent Nad�azen� polo�ka (v�choz�: 0)
	 * @return array Pole objekt� t��dy MenuItem
	 *
	 */	
	public static function Items($parent = 0) {
		$re = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "menu WHERE Parent = " . $parent);
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new MenuItem($row->ID);
				
		return $array;
	}		
	
	/**
	 * Vytvo�it novou polo�ka
	 *
	 * @param int $url URL
	 * @param int $parent Nad�azen� polo�ka (v�choz�: 0)
	 * @param int $order Po�ad� (v�choz�: -1)
	 * @return int ID z�znamu
	 *
	 */	
	public static function Create($url, $parent = 0, $order = -1) {
		$connect = DB::Connect();
		
		if ($order == -1) {
			$count = count(self::Items($parent));
			$order = $count;
		}
		
			$url = $connect->real_escape_string($url);
		
		$re = $connect->query("INSERT INTO " . DB_PREFIX . "menu (Url, Parent, MenuOrder) VALUES (" . $url . ", " . $parent . ", " . $order . ")");
		return $connect->insert_id;
	}
	
	/**
	 * Vr�tit cel� menu
	 *
	 * @return array Pole s polo�ky navigace
	 *
	 */	
	public function Menu() {
		return $this->Menu;
	}
	
	/**
	 * Vr�tit pouze rodi�ovsk� prvky (" + WHERE Parent = 0")
	 *
	 * @return array Rodi�ovsk� polo�ky menu
	 *
	 */	
	public static function ParentsOnly() {
		return self::Items(0);
	}
	
	/**
	 * Existuje polo�ka?
	 *
	 * @param itn $data ID z�znamu
	 * @param string $type Typ z�znamu
	 * @return bool Existuje Polo�ka?
	 *
	 */	
	public static function Exists($data, $type) {
		$connect = DB::Connect();
		
			$type = $connect->real_escape_string($type);
		
		$url = $connect->query("SELECT ID, COUNT(*) AS Count FROM " . DB_PREFIX . "urls WHERE Data = " . $data . " AND Type = '" . $type . "'")->fetch_object();
		if ($url->Count == 0)
			return false;
		
		$r = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "menu WHERE Url = '" . $url->ID . "'")->fetch_object();
		
		if ($r->Count == 0)
			return false;
			
		return true;
	}
}
?>
