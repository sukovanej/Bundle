<?php
/*
 * Menu
 * 
 * @author sukovanej
 */ 

namespace Bundle;  
 
class Menu {
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
	
	private static function MenuObject($Url, $Title, $Type, $Order, $Data, $ID, $Children, $Current = false) {
		$obj = new \stdclass();
		$obj->Url = $Url;
		$obj->Title = $Title;
		$obj->Type = $Type;
		$obj->Order = $Order;
		$obj->Data = $Data;
		$obj->Children = $Children;
		$obj->ID = $ID;
		
		if ($Current)
			$obj->Current = 'class="current"';
		else
			$obj->Current = '';
		
		return $obj;
	}
	
	public static function Items($parent) {
		$re = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "menu WHERE Parent = " . $parent);
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new MenuItem($row->ID);
				
		return $array;
	}		
	
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
	
	public function Menu() {
		return $this->Menu;
	}
	
	public static function ParentsOnly() {
		return self::Items(0);
	}
	
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
