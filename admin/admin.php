<?php

/**
 * Description of admin
 *
 * @author sukovanej
 */
class Admin {
    public static function PasswordError($type) {
        if ($type == 0) 
            self::ErrorMessage("Staré heslo není správné.");
        else if ($type == 1)
            self::ErrorMessage("Nová hesla se neshodují.");
        else if ($type == 2)
            self::ErrorMessage ("Všechny údaje musí být vyplněné.");
    }
    
    public static function Message($text) {
        echo "<div id='done'><p>" . $text . "</p></div>";
    }
    
    public static function ErrorMessage($text) {
        echo "<div id='error'><p>" . $text . "</p></div>";
    }
    
    public static function WarningMessage($text) {
        echo "<div id='warning'><p>" . $text . "</p></div>";
    }
}

function __POST($var) {
	return isset($_POST[$var]) ? $_POST[$var] : null;
}

$Website = new Bundle\Template;
