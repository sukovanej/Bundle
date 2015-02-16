<?php

/**
 * Bundle\Url
 *
 * @author sukovanej
 */
namespace Bundle;  
 
class Url extends DatabaseBase {
    public function __construct($ID) {
        parent::__construct($ID, "urls");
    }
    
    public static function Create($Url, $Type, $Data) {
		$c = DB::Connect();
			
			$Url = $c->real_escape_string($Url);
			$Type = $c->real_escape_string($Type);
		
		$c->query("INSERT INTO " . DB_PREFIX . "urls (Url, Type, Data) VALUES ('" . $Url . "', '" . $Type . "', " . $Data . ")");
		
		return $c->insert_id;
    }
    
    public function Delete() {
        if(($menu = MenuItem::InstByUrl($this->Url)) != false)
            $menu->Delete();
        
		parent::Delete();
	}
    
    public static function IsDefinedUrl($Url) {
		$connect = DB::Connect();
		
			$Url = $connect->real_escape_string($Url);
		
        if ($connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "urls WHERE Url = '" . $Url . "'")->fetch_object()->Count >= 1)
            return true;
            
        return false;
    }
    
    public static function InstByData($Data, $Type) {
		$connect = DB::Connect();
		
			$Data = $connect->real_escape_string($Data);
			$Type = $connect->real_escape_string($Type);
		
        $r = $connect->query("SELECT ID FROM " . DB_PREFIX . "urls WHERE Type = '" . $Type . "' AND Data = '" . $Data . "'");
        $_r = $r->fetch_object();
        $c = $r->num_rows;
        
        if($c == 0)
			return false;
        
        return new Url($_r->ID);
    }
    
    public static function InstByUrl($Url) {
		$connect = DB::Connect();
		
			$Ulr = $connect->real_escape_string($Url);
		
        $r = $connect->query("SELECT ID FROM " . DB_PREFIX . "urls WHERE Url = '" . $Url . "'");
        $_r = $r->fetch_object();
        $c = $r->num_rows;
        
        if($c == 0)
			return false;
        
        return new Url($_r->ID);
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

        $url = str_replace("administration", "a-administration", $url);
			
	    $url = $connect->real_escape_string($url);
			
        $count = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "urls WHERE Url LIKE '" 
                . $url . "%'")->fetch_object()->Count;
        
        if ($count != 0)
            $url .= "-" . $count;
        
        return $url;
    }
}
