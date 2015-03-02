<?php
	class prism extends Bundle\PackageBase {
		public $includes;
		public $home_only;
		public $place;
		
		// Inicialize
		public function __construct() {
			$this->includes = array();
			$this->home_only = false;
			$this->place = "content";
		}
		
		public function install() {
			return true;
		}
		
		public function uninstall() { return true; }
	}
