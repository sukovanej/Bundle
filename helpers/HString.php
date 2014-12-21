<?php

class HString {
	public static function startsWith($text, $with) {
		return (substr($text, 0, strlen($with)) === $with);
	}
	
	public static function endsWith($text, $with) {
		return $with === "" || strpos($text, $with, strlen($text) - strlen($with)) !== FALSE;
	}
}
