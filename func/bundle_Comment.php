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
    
    public static function Create($text, $page, $author, $ip) {
        $connect = DB::Connect();
        $connect->escape_string($text);
        $connect->query("INSERT INTO " . DB_PREFIX . "comments (Text, Page, Author, IP) VALUES ('" . $text . "', " 
                . $page . ", " . $author . ", '" . $ip . "')");
        $connect->close();
    }
    
    public static function SimpleFormat($text) {
        $result = htmlspecialchars($text);
        return nl2br($result);
    }
    
    public static function GetList() {
		$connect = DB::Connect();
		$re = $connect->query("SELECT ID FROM " . DB_PREFIX . "comments");
		
		$array = array();
		
		while($row = $re->fetch_object()) {
			$array[] = new Comment($row->ID);
		}
			
		return $array;
	}
    
	public static function CountAll() {
		return count(self::GetList());
	}
}
