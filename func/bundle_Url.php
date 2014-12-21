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
		$c->query("INSERT INTO " . DB_PREFIX . "urls (Url, Type, Data) VALUES ('" . $Url . "', '" . $Type . "', " . $Data . ")");
		return $c->insert_id;
    }
    
    public function Delete() {
		parent::Delete();
		$this->connect->query("DELETE FROM " . DB_PREFIX . "menu WHERE Url = " . $this->ID);
	}
    
    public static function IsDefinedUrl($Url) {
        if (DB::Connect()->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "urls WHERE Url = '" . $Url . "'")->fetch_object()->Count >= 1)
            return true;
            
        return false;
    }
    
    public static function InstByData($Data, $Type) {
        $r = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "urls WHERE Type = '" . $Type . "' AND Data = '" . $Data . "'");
        $_r = $r->fetch_object();
        $c = $r->num_rows;
        
        if($c == 0)
			return false;
        
        return new Url($_r->ID);
    }
    
    public static function InstByUrl($Url) {
        $r = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "urls WHERE Url = '" . $Url . "'");
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
        $count = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "urls WHERE Url LIKE '" 
                . $url . "%'")->fetch_object()->Count;
        
        if ($count != 0)
            $url .= "-" . $count;
        
        $connect->close();
        
        return $url;
    }
}
