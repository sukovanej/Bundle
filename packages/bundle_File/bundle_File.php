<?php
	if(!defined("_BD"))
		die();
		
	class bundle_File extends Bundle\PackageBase {
		// Inicialize
		public function __construct() {
			$this->includes = array();
			
			parent::__construct();
		}
		
		// Install
		public function install() {
			return true;
		}
		
		public function uninstall() {
			return true;
		}
	}
