<?php 
/**
 * Package
 *
 * @author sukovanej
 */
 
namespace Bundle; 

class Package extends DatabaseBase {
	public function __construct($ID) {
		parent::__construct($ID, "packages");
		$this->connect = DB::Connect();
		
		$this->IconUrl = "packages/" . $this->Name ."/ico.png";
		$this->Config = new IniConfig("packages/" . $this->Name ."/info.conf");
		
		if (!file_exists(getcwd() . "/" . $this->IconUrl))
			$this->IconUrl = "images/Plugins.png";
		
		if(!empty($this->Title))
			$this->Url = Url::InstByData($ID, "package")->Url; 
	}
	
	public function Generate() {
		
		global ${$this->Name}; 
			
		if (($content = Content::GetByData("package", $this->ID)) == false){
			require("./packages/" . $this->Name . "/layout.php");
		} else {
			$router = @$_GET["router"];
			
			if (($content->HomeOnly && empty($router)) || ($content->HomeOnly == 0 && !empty($router)) || (!$content->HomeOnly && empty($router)))
				require("./packages/" . $this->Name . "/layout.php");
		}
	}
	
	public function GetPackageConfig() { return new IniConfig(getcwd() . "/packages/" . $this->Name . "/info.conf"); }
	
	public static function GetPackageByName($name) {
		$connect = DB::Connect();
		
			$name = $connect->real_escape_string($name);
		
		$r = $connect->query("SELECT ID FROM " . DB_PREFIX . "packages WHERE Name = '" . $name . "'");
		return new Package($r->fetch_object()->ID);
	}
	
	public static function IsActive($name) {
		$package = self::get_package_by_title($name);
		return $package->IsActive;
	}
	
	public static function GetInstalledPackages() {
		$r = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "packages");
		$result = array();
		
		while($row = $r->fetch_object())
			$result[] = new Package($row->ID);
			
		return $result;
	}
	
	public static function ParentsOnly() {
		$re = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "packages");
		$array = array();
		
		while($row = $re->fetch_object()) {
			$pack = new Package($row->ID);
			
			if(!empty($pack->Title))
				$array[] = $pack;
		}
				
		return $array;
	}	
		
	public function Children() {
		$re = DB::Connect()->query("SELECT Data FROM " . DB_PREFIX . "urls WHERE Type = '" . $this->Type . "' AND Parent = " . $this->ID);
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Package($this->ID);
				
		return $array;
	}
	
	public function Uninstall() {
		try {
			if (!empty($this->Url)) {
				MenuItem::InstByUrl($this->Url)->Delete();
				Url::InstByData($this->ID, "package")->Delete();	
			}
				
			$this->Delete();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}

class Packages {
	public function __construct() {
		$this->connect = DB::Connect();
	}
	
	public static function IsPackageInstalled($name) {
		$result = DB::Connect()->query("SELECT Name FROM " . DB_PREFIX . "packages WHERE Name = '" . $name . "'");
		
		if ($result->num_rows < 1)
			return false;
		else
			return true;
	}
	
	public static function GetPackages() {
		$dirs = glob(getcwd() . "/packages/*" , GLOB_ONLYDIR);
		$result = array();
	
		foreach($dirs as $dir) {
			$config = new IniConfig($dir . "/info.conf");
			
			$result[basename($dir)] = $config;
		}
		
		return $result;
	}
	
	public function Install($name, $title = "") {
		$menu = 0;
		
		if (!empty($title))
			$menu = 1;
		
		$this->connect->query("INSERT INTO " . DB_PREFIX . "packages (Name, IsActive, Title) VALUES ('" . $name . "', 1, '" . $title . "')");
		$id = $this->connect->insert_id;
		
		if ($menu == 1) {
			$id = Url::Create(Page::CreateUrl($title), "package", $id);
			Menu::Create($id);
		}
			
		return $id;
	}
	
	public static function get_packages_admin_menu() {
		$result = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "packages WHERE AdminMenu = 1");
		$r = array();
		
		while($row = $result->fetch_object()) {
			$r[] = new Package($row->ID);
			
		}
			
		return $r;
	}
}
