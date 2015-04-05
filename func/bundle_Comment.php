<?php

/**
 * Comment
 *
 * @author sukovanej
 */
 
namespace Bundle; 
 
class Comment extends DatabaseBase{
    public function __construct($ID) {
        parent::__construct($ID, "comments");
        
        $this->Datetime = (new \DateTime($this->Datetime))->format("d. m. Y  H:i");
        
        $this->ArticleObj = new Article($this->Page);
        if ($this->Author == -1) {
			$this->AuthorObj = new \stdclass();
			$this->AuthorObj->Username = "anonym";
		} else {
			$this->AuthorObj = new User($this->Author);
		}
    }
    
	/**
	 * Vytvo�it nov� �l�nek
	 *
	 * @param string $text Obsah koment��e
	 * @param int $page Str�nka (resp. �l�nek) s koment��i
	 * @param int $author Autor koment��e
	 * @param string $ip IP autora koment��e
	 *
	 */	
    public static function Create($text, $page, $author, $ip) {
        $connect = DB::Connect();
        
			$text = $connect->real_escape_string($text);
			$ip = $connect->real_escape_string($ip);
			
        $connect->query("INSERT INTO " . DB_PREFIX . "comments (Text, Page, Author, IP) VALUES ('" . $text . "', " 
                . $page . ", " . $author . ", '" . $ip . "')");
    }
    
	/**
	 * P�ev�st speci�ln� znaky na entity
	 *
	 * @param string $text Vstup
	 * @return string V�stup
	 *
	 */	
    public static function SimpleFormat($text) {
        $result = htmlspecialchars($text);
        return nl2br($result);
    }
    
	/**
	 * Vr�tit pole objekt� t��dy Bundle\Comment
	 *
	 * @return mixed Pole s objekty Bundle\Comment
	 *
	 */	
    public static function GetList() {
		$connect = DB::Connect();
		$re = $connect->query("SELECT ID FROM " . DB_PREFIX . "comments ORDER BY Datetime DESC");
		
		$array = array();
		
		while($row = $re->fetch_object()) {
			$array[] = new Comment($row->ID);
		}
			
		return $array;
	}
    
	/**
	 * Spo��tat v�echny existuj�c� koment��e
	 *
	 * @return int Po�et existuj�ch koment���
	 *
	 */	
	public static function CountAll() {
		return count(self::GetList());
	}
}
