<?php

class ckeditor extends Bundle\PackageBase {
	public function __construct() {
		parent::__construct();
	}
	
	public function install() {
		Bundle\Events::Register(HPackage::getAutoIncrementID(), "AdminHead");
		Bundle\Events::Register(HPackage::getAutoIncrementID(), "AdminFooter");
			
		return true;
	}
	
	public function uninstall() {
		return true;
	}
	
	public function handle_AdminHead() {
		include(HPackage::getPath("ckeditor") . "/admin_header.php");
	}
	
	public function handle_AdminFooter() {
		include(HPackage::getPath("ckeditor") . "/admin_footer.php");
	}
}
