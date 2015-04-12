<?php

define("EN_GB", "en_gb");

class HLoc {
    private static $instance = false;
    public $default = EN_GB;
    public $Array;
    public $lang;

    private function __construct(){}

    public static function getInstance(){
		if(self::$instance == false){
			self::$instance = new HLoc;

			$inst = self::$instance;
			$inst->Array = self::languageArray($inst->default);
			$inst->lang_a = self::languageArray(HConfiguration::get("Localization"));
		}

		return self::$instance;
    }

    public static function setDefault($lan) {
    	$inst = self::getInstance();

    	$inst->default = $len;
    	$inst->Array = self::languageArray($inst->default);
    }

	public static function l($text) {
		$inst = self::getInstance();

		$default = $inst->default;
		$lang = HConfiguration::get("Localization");

		if (!isset($inst->lang_a[$text]) || $inst->default == $lang)
			return trim($text);
		else if ($default == EN_GB)
			return trim($inst->lang_a[$text]);
		else
			return trim($inst->lang_a[array_search($text, $inst->Array)]);
	}

	public static function languageArray($lang) {
		$result = array();

		$url = "localization/" . $lang;

		// úprava kvůli AJAXovým požadavkům
        if (!file_exists($url) && file_exists("../../localization/" . $lang)) {
        	$url = "../../localization/" . $lang;
        }

        if (!file_exists($url)) {
			throw new Exception(self::l("File doesn't exist."));
		} else { 
			$handle = @fopen($url, "r");
			
			if ($handle) {
				while (($buffer = fgets($handle, 4096)) !== false) {
					$foreach_array = explode("=", $buffer);
					if (count($foreach_array) > 1)
						$result[$foreach_array[0]] = $foreach_array[1];
				}
			}
			
			fclose($handle);
		}  

		return $result;
	}
}