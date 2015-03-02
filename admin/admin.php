<?php

/**
 * Description of admin
 *
 * @author sukovanej
 */
class Admin {
    public static function PasswordError($type) {
        if ($type == 0) 
            self::ErrorMessage(HLoc::l("The password is not corrent."));
        else if ($type == 1)
            self::ErrorMessage(HLoc::l("Passwords must match."));
        else if ($type == 2)
            self::ErrorMessage (HLoc::l("All information must by filled in."));
    }
    
    public static function Message($text) {
        echo "<div class='alert alert-success' role='alert'>" . $text  
        . '<button type="button" class="close pull-right" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    
    public static function ErrorMessage($text) {
        echo "<div class='alert alert-danger' role='alert'>" . $text 
        . '<button type="button" class="close pull-right" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    
    public static function WarningMessage($text) {
        echo "<div class='alert alert-warning' role='alert'>" . $text 
        . '<button type="button" class="close pull-right" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
}

function __post($var) {
	return isset($_POST[$var]) ? $_POST[$var] : null;
}

$Website = new Bundle\Template;
