<?php

/**
 * mini_Comment
 *
 * @author sukovanej
 */

namespace Bundle;  

class ArticleCategories extends DatabaseBase{
    public function __construct($ID) {
        parent::__construct($ID, "categories");
    }
    
    public static function Create($Article, $Category) {
        $connect = DB::Connect();
        $connect->query("INSERT INTO " . DB_PREFIX . "article_categories (Article, Category) VALUES (" . $Article . ", "
                . $Category . ")");
    }
}
