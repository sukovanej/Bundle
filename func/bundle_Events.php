<?php

/**
 * Includes
 *
 * @author sukovanej
 */
 
namespace Bundle; 
 
class Events {
	public static function Register($data, $place) {
		Content::Create("event", $data, "event_" . $place);
		return true;
	}
	
	public static function Unregister($data) {
		$list = Content::ListByData("event", $data);
		
		foreach ($list as $item)
			$item->Delete();
	}
	
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
	
	public static function BootAllPacks () {
		$contents = Content::ListByType("event");
		if ($contents)
			foreach($contents as $content) {
				$package = new Package($content->Data);
				$class = $package->Name;
				global ${$class};
				${$class} = new $class();
			}
	}
}
