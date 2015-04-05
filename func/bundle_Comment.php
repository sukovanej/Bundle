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
	 * Vytvoøit novı èlánek
	 *
	 * @param string $text Obsah komentáøe
	 * @param int $page Stránka (resp. èlánek) s komentáøi
	 * @param int $author Autor komentáøe
	 * @param string $ip IP autora komentáøe
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
	 * Pøevést speciální znaky na entity
	 *
	 * @param string $text Vstup
	 * @return string Vıstup
	 *
	 */	
    public static function SimpleFormat($text) {
        $result = htmlspecialchars($text);
        return nl2br($result);
    }
    
	/**
	 * Vrátit pole objektù tøídy Bundle\Comment
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
	 * Spoèítat všechny existující komentáøe
	 *
	 * @return int Poèet existujích komentáøù
	 *
	 */	
	public static function CountAll() {
		return count(self::GetList());
	}
}
