<?php

/**
 * Includes
 *
 * @author sukovanej
 */
 
namespace Bundle; 
 
class Events {
	/**
	 * Registrovat ud�lost
	 *
	 * @param int $data ID z�znanmu
	 * @param string $place Oblast odchycen� ud�losti
	 * @return true
	 *
	 */	
	public static function Register($data, $place) {
		Content::Create("event", $data, "event_" . $place);
		return true;
	}
	
	/**
	 * Zru�it odchycen� ud�losti
	 *
	 * @param int $data ID z�znamu
	 *
	 */	
	public static function Unregister($data) {
		$list = Content::ListByData("event", $data);
		
		if ($list != false)
			foreach ($list as $item)
				$item->Delete();
	}
	
	/**
	 * Zavolat ud�lost
	 *
	 * @param string $place Oblast spu�t�n� ud�losti
	 * @param array $params Libovoln� parametry (��m v�ce, t�m l�pe)
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
	 * Zav�st v�echny bal��ky spou�t�n� v ud�lostech
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
