<?php
	class bundle_Header extends Bundle\PackageBase {
		public function __construct() {
			$this->includes = array();
			
			$this->place = "header";
			$this->home_only = false;
			
			parent::__construct();
		}
		
		public function Image() {
			return (HConfiguration::Get("BundleHeaderImageUrl"));
		}
		
		public function install() {
			HConfiguration::Create("BundleHeaderImageUrl", "./packages/bundle_Header/default_image.jpg");
			return true;
		}
		
		public function uninstall() {
			return false;
		}
	}
