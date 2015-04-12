<?php

	// ajax load files

	define("_BD", "bundle");

	/** uncomment to enable PHP errors **/
	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);
	/**/

	// load kernel files
	session_start();
	require("../../func/bundle_Loader.php");

	define("DB_PREFIX", (new Bundle\IniConfig("../../config.ini"))->db_prefix);

	require("../../helpers/HConfiguration.php");
	require("../../helpers/HLoc.php");
	require("../../helpers/HToken.php");

	class Admin {
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