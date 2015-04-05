<?php

/**
 * Includes
 *
 * @author sukovanej
 */
 
namespace Bundle; 
 
class Events {
	/**
	 * Registrovat událost
	 *
	 * @param int $data ID záznanmu
	 * @param string $place Oblast odchycení události
	 * @return true
	 *
	 */	
	public static function Register($data, $place) {
		Content::Create("event", $data, "event_" . $place);
		return true;
	}
	
	/**
	 * Zrušit odchycení události
	 *
	 * @param int $data ID záznamu
	 *
	 */	
	public static function Unregister($data) {
		$list = Content::ListByData("event", $data);
		
		if ($list != false)
			foreach ($list as $item)
				$item->Delete();
	}
	
	/**
	 * Zavolat událost
	 *
	 * @param string $place Oblast spuštìní události
	 * @param array $params Libovolné parametry (èím více, tím lépe)
	 *
	 */	
	public static function Execute($place, $params = array()) {
		$_place = "event_" . $place;
		$contents = Content::ListByPlace($_place);
		
		if($contents != false) {
			foreach($contents as $content) {
				if ($content->Type == "event") {
					$package = new Package($content->Data);
					
					if(class_exists($package->Name) && $package->IsActive) {
						global ${$package->Name};
						${$package->Name}->{"handle_" . $place}($params);
					}
				}
			}
		}
	}
	
	/**
	 * Zavést všechny balíèky spouštìné v událostech
	 *
	 *
	 */	
	public static function BootAllPacks () {
		$packs = (false != Content::ListByType("package")) ? Content::ListByType("package") : array();
		$events = (false != Content::ListByType("event")) ? Content::ListByType("event") : array();
		
		$contents = array_merge($events, $packs);
		if ($contents)
			foreach($contents as $content) {
				$package = new Package($content->Data);
				$class = $package->Name;
				global ${$class};
				${$class} = new $class();
			}
	}
}
