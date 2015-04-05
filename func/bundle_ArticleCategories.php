<?php

/**
 * mini_Comment
 *
 * @author sukovanej
 */

namespace Bundle;  

class ArticleCategories extends DatabaseBase{
	
	/**
	 * Vytvoøit instanci tøídy Bundle\ArticleCategories
	 *
	 * @param int $ID ID z databáze
	 *
	 */	
    public function __construct($ID) {
        parent::__construct($ID, "categories");
    }
    
	/**
	 * Vytvoøit záznam
	 *
	 * @param int $Article ID èlánku
	 * @param int $Category ID kategorie
	 *
	 */	
    public static function Create($Article, $Category) {
        $connect = DB::Connect();
        $connect->query("INSERT INTO " . DB_PREFIX . "article_categories (Article, Category) VALUES (" . $Article . ", ". $Category . ")");
    }
}
