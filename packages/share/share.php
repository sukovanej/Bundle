<?php
	if(!defined("_BD"))
		die();
		
	class share extends Bundle\PackageBase {
		public function __construct() {
			$this->includes = array();
			
			parent::__construct();
		}

		public function handle_Article($params) {
			$Article = $params[0];

			$Url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

			ob_start();
				require(HPackage::getPath("share") . "/_layout.php");
				$content = ob_get_contents();
			ob_end_clean();

			$Article->Content .= $content;
		}
		
		public function install() {
			Bundle\Events::Register(HPackage::getAutoIncrementID(), "Article");

			return true;
		}
		
		public function uninstall() {
			return false;
		}
	}
