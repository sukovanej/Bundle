<?php

/**
 * Article
 *
 * @author sukovanej
 */

namespace Bundle;  
 
class Article extends DatabaseBase {
    public $CategoriesString;
    
    public function __construct($ID) {
        parent::__construct($ID, "articles");
        $this->Url = Url::InstByData($ID, "article")->Url;
        $this->Datetime = (new \DateTime($this->Datetime))->format("d. m. Y  H:i"); 
        $this->CategoriesString = "";
        $this->Perex = explode("<!-- pagebreak -->", $this->Content)[0];
        $this->Comments = $this->connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "comments WHERE Page = " . $this->ID)->fetch_object()->Count;
        
        if ($this->Perex == $this->Content) {
			$this->Perex = explode("[perex]", $this->Content)[0];
			$this->Content = str_replace("[perex]", "", $this->Content);
		}
        
        $this->Categories();
        
        $this->Statuses = self::getStatuses();
        $this->StatusString = $this->Statuses[$this->Status];
        
        $_conf = new Template(1);
        
        if ($_conf->AllowComments == 0)
			$this->AllowComments = 0;
    }
    
    public static function getStatuses() {
		return array(1 => "Publikován", 2 => "Koncept");
	}
    
    public static function Create($title, $content, $show_datetime, $author, $show_comments, $show_in_view, $status = 2) {
        $connect = DB::Connect();
        
			$title = $connect->escape_string($title);
			$content = $connect->escape_string($content);
			$show_datetime = (int)$show_datetime;
			$show_comments = (int)$show_comments;
			$show_in_view = (int)$show_in_view;
			$status = (int)$status;
			
        $connect->query("INSERT INTO " . DB_PREFIX . "articles (Title, Content, Author, ShowDatetime, AllowComments, ShowInView, Status) VALUES ('" . $title . "', '" 
                . $content . "', " . $author . ", " . $show_datetime . ", " . $show_comments . ", " . $show_in_view . ", " . $status . ")");
                
		$ID = $connect->insert_id;
                
        Url::Create(Url::CreateUrl($title), "article", $ID);
        return $ID;
    }
    
    public function Delete() {
        parent::Delete();
        $this->connect->query("DELETE FROM " . DB_PREFIX . "comments WHERE Page = " . $this->ID);
        $this->connect->query("DELETE FROM " . DB_PREFIX . "article_categories WHERE Article = " . $this->ID);
        $this->connect->query("DELETE FROM " . DB_PREFIX . "urls WHERE Type = 'article' AND Data = " . $this->ID);
    }
    
    public function Comments() {
        $result = $this->connect->query("SELECT ID, Author FROM " . DB_PREFIX . "comments WHERE Page = " . $this->ID . " ORDER BY Datetime DESC");
        
        while($row = $result->fetch_assoc()) {
            $Comment = new Comment($row["ID"]);

            if ($Comment->Author == -1) {
				$Author = new \stdclass();
				$Author->Username = $Comment->IP;
				$Author->ID = -1;
				$Author->Role = -1;
				$Author->RoleString = "Anonym";
				$Author->Photo = "./upload/users/no-photo.png";
			} else {
				$Author = new User($row["Author"]);
			}
			
            require("./themes/" . (new Template())->Theme . "/comment.php");
        }
    }
    
    public function Categories() {
        $result = $this->connect->query("SELECT Category FROM " . DB_PREFIX . "article_categories WHERE Article = " 
                . $this->ID);
        
        $return = array();
        
        $i = $result->num_rows;
        
        while($cat = $result->fetch_object()) {
            $r = new Category($cat->Category);
            $return[] = $r;
            
            $this->CategoriesString .= "<a href='" . $r->Url . "'>" 
                    . $r->Title . "</a>";
            
            if($i-- > 1)
                $this->CategoriesString .= ", ";
        }
        
        return $return;
    }
    
    public function DeleteCategories() {
        $this->connect->query("DELETE FROM " . DB_PREFIX . "article_categories WHERE Article = " . $this->ID);
    }
    
    public function InstUpdate() {
        parent::InstUpdate();
        $this->Url = Url::InstByData($this->ID, "article")->Url;
        $this->Datetime = (new \DateTime($this->Datetime))->format("d. m. Y  H:i");
    }
    
    public static function CountAll() {
		$connect = DB::Connect();
		$re = $connect->query("SELECT COUNT(*) AS Count FROM " . DB_PREFIX . "articles")->fetch_object();
		return $re->Count;
	}
	
	public static function GetAll($author = -1) {
		if ($author == -1)
			$result = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "articles ORDER BY Datetime DESC");
		else
			$result = DB::Connect()->query("SELECT ID FROM " . DB_PREFIX . "articles WHERE Author = " . $author . " ORDER BY Datetime DESC");
		
		$array = array();
		
		while($row = $result->fetch_object())
			$array[] = new Article($row->ID);
			
		return $array;
	}
}
