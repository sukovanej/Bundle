<?php

/**
* Comment
*
* @author sukovanej
*/

namespace Bundle; 

class Category extends DatabaseBase{
	public $childs;
	
	public function __construct($ID) {
		parent::__construct($ID, "categories");
		$this->Url = Url::InstByData($ID, "category")->Url;
	}
	
	public static function Create($name, $parent = 0) {
		$connect = DB::Connect();
		
			$name = htmlspecialchars($connect->escape_string($name));
			
		$connect->query("INSERT INTO " . DB_PREFIX . "categories (Title, Parent) VALUES ('" . $name . "', " . $parent . ")");
		$ID = $connect->insert_id;
		
		Url::Create(Url::CreateUrl($name), "category", $ID);
	}
	
	public static function ParentsOnly() {
		$re = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "categories WHERE Parent = 0 ORDER BY Title");
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Category($row->ID);
				
		return $array;
	}
	
	public function Children() {
		$re = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "categories WHERE Parent = " . $this->ID);
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Category($row->ID);
				
		return $array;
	}

	public function Delete() {
		parent::Delete();
		$this->connect->query("DELETE FROM " . DB_PREFIX . "article_categories WHERE Category = " . $this->ID);
		$this->connect->query("DELETE FROM " . DB_PREFIX . "urls WHERE Type = 'category' AND Data = " . $this->ID);
		$this->connect->query("UPDATE " . DB_PREFIX . "categories SET Parent = 0 WHERE Parent = " . $this->ID);
	}
	
	public static function CountAll() {
		$connect = DB::Connect();
		$re = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "categories")->fetch_object();
		return $re->Count;
	}
}
