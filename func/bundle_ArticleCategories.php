<?php

/**
 * mini_Comment
 *
 * @author sukovanej
 */

namespace Bundle;  

class ArticleCategories extends DatabaseBase{
	
	/**
	 * Vytvo�it instanci t��dy Bundle\ArticleCategories
	 *
	 * @param int $ID ID z datab�ze
	 *
	 */	
    public function __construct($ID) {
        parent::__construct($ID, "categories");
    }
    
	/**
	 * Vytvo�it z�znam
	 *
	 * @param int $Article ID �l�nku
	 * @param int $Category ID kategorie
	 *
	 */	
    public static function Create($Article, $Category) {
        $connect = DB::Connect();
        $connect->query("INSERT INTO " . DB_PREFIX . "article_categories (Article, Category) VALUES (" . $Article . ", ". $Category . ")");
    }
}
