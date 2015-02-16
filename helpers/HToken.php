<?php

class HToken {
	public static function get() {
		if (!isset($_SESSION["token"])) {
			$_SESSION["token"] = mt_rand();
		}
		
		return $_SESSION["token"];
	}
	
	public static function html() {
		$html = '<input type="hidden" name="token" value="' . self::get() . '" />';
		return $html;
	}
	
	public static function checkToken() {
		if (!isset($_POST["token"]) || $_POST["token"] != self::get())
			return false;
			
		return true;
	}
}
