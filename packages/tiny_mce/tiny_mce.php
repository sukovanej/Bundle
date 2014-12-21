<?php

class tiny_mce extends Bundle\PackageBase {
	
	// Inicialize
	public function __construct() {
		$this->includes = array(
			"tiny_mce.php"
		);
		
		parent::__construct();
	}
	
	// Install
	public function install() {
		configuration_Helper::Create('tinyMCE_plugins', 
			'"advlist autolink link image lists charmap print preview hr anchor pagebreak",
			 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			 "save table contextmenu directionality emoticons paste textcolor"');
			 
		configuration_Helper::Create('tinyMCE_toolbar', 
			'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent 
				| link image | preview media | forecolor backcolor emoticons');
				
		Bundle\Events::Register(package_Helper::getLastInstalled() + 1, "AdminHead");
		
		return true;
	}
	
	public function uninstall() {
		return true;
	}
		
	public function handle_AdminHead() {
		echo('
		<script type="text/javascript" src="' . HPackage::getPath("tiny_mce") . '/editor/tinymce.min.js"></script>
		<script type="text/javascript">
			tinymce.init({ selector: "textarea#editor", plugins: [' . "\n" . HConfiguration::get("tinyMCE_plugins") . "\n" . '], 
				toolbar: "' . HConfiguration::get("tinyMCE_toolbar") . '",
			language : "cs", content_css : "css/custom_content.css" });
        </script>');
	}
}
