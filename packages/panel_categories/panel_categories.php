<?php
if(!defined("_BD"))
	die();

class panel_categories extends Bundle\PackageBase {
	public $includes;
	public $place;
	public $home_only;
	
	// Inicialize
	public function __construct() {
		$this->place = "panel";
		$this->home_only = false;
		$this->DB = Bundle\DB::Connect();
		
		parent::__construct();
	}
		
	public function Generate() {
		return Bundle\Category::ParentsOnly();
	}

	public function CountArticlesByCategories($category) {
		return $this->DB->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "article_categories WHERE Category = " 
			. (int)$category)->fetch_object()->Count;
	}

	// Install
	public function install() {
		return true;
	}
	
	public function uninstall() {
		return true;
	}
}
