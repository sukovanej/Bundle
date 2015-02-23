<?php

/**
 * Bundle\Page
 *
 * @author sukovanej
 */
 
namespace Bundle; 

class Page extends DatabaseBase {
    public function __construct($ID) {
        parent::__construct($ID, "pages");
        $this->Url = Url::InstByData($ID, "page");
        $this->Url = $this->Url->Url;
        $this->Menu = 0;        
        
        if (Menu::Exists($this->ID, "page")) {
			$this->Menu = 1;
		}
    }
    
    public static function Create($title, $content, $menu, $author, $parent) {
        $connect = DB::Connect();
        
			$title = $connect->escape_string($title);
			$content = $connect->escape_string($content);
			
        $connect->query("INSERT INTO " . DB_PREFIX . "pages (Title, Content, Author) VALUES "
                . "('" . $title . "', '" . $content . "', " . $author . ")");
                
        $id = $connect->insert_id;
        
        Url::Create(self::CreateUrl($title), "page", $id, $parent, $menu);
        return $id;
    }
    
    public static function CreateUrl($str) {
        $url = $str;
			$_old = array("ě", "š", "č", "ř", "ž", "ý", "á", "í", "é", "ď", "ť", "ů", "ú", "ň", "ó");
			$_new = array("e", "s", "c", "r", "z", "y", "a", "i", "e", "d", "t", "u", "u", "n", "o");
			
        $url = str_replace($_old, $_new, $url);
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
        $url = trim($url, "-");
        setlocale(LC_CTYPE, 'en_US.UTF-8');
        $url = iconv("UTF-8","ASCII//TRANSLIT", $url);
        $url = strtolower($url) ;
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
        $connect = DB::Connect();
			
			$url = $connect->real_escape_string($url);
        
        $count = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "urls WHERE Url LIKE '" 
                . $url . "%'")->fetch_object()->Count;
        
        if ($count != 0)
            $url .= "-" . $count;
        
        return $url;
    }
    
    public function Delete() {
        parent::Delete();
        $url = Url::InstByData($this->ID, "page");
        $url->Delete();
    }
    
	public static function CountAll() {
		$re = DB::Connect()->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "pages")->fetch_object();
		return $re->Count;
	}
	
	public function Children() {
		$re = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "pages WHERE Parent = " . $this->ID);
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Page($row->ID);
				
		return $array;
	}
	
	public static function ParentsOnly() {
		$re = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "pages WHERE Parent = 0");
		$array = array();
		
		while($row = $re->fetch_object())
			$array[] = new Page($row->ID);
				
		return $array;
	}	
	
	public function InstUpdate() {
		parent::InstUpdate();
		$this->Url = Url::InstByData($this->ID, "page")->Url;
	}
}
