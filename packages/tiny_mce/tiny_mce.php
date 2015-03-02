<?php
if(!defined("_BD"))
	die();

class tiny_mce extends Bundle\PackageBase {
	
	// Inicialize
	public function __construct() {
		parent::__construct();
	}
	
	// Install
	public function install() {
		HConfiguration::Create('tinyMCE_plugins', 
			'advlist autolink link image lists charmap print preview hr anchor pagebreak",'
			. '"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",'
			. '"save table contextmenu directionality emoticons paste textcolor');
			 
		HConfiguration::Create('tinyMCE_toolbar', 
			'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | '
			. 'link image | preview media | forecolor backcolor emoticons');
				
		Bundle\Events::Register(HPackage::getLastInstalled() + 1, "AdminHead");
		
		return true;
	}
	
	public function uninstall() {
		return true;
	}
		
	public function handle_AdminHead() {
		echo('
<script type="text/javascript" src="' . HPackage::getPath("tiny_mce") . '/editor/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({ selector: "textarea#editor", plugins: [ "' . HConfiguration::get("tinyMCE_plugins") . '"' . "\n\t\t\t" . ' ], 
		toolbar: "' . HConfiguration::get("tinyMCE_toolbar") . '",
		language : "cs", content_css : "css/custom_content.css", 
		init : function(ed) {
		  ed.onKeyDown.add(function(ed, evt) {
			  console.debug("Key up event: " + evt.keyCode);
			  if (evt.keyCode == 9){ // tab pressed
				ed.execCommand("mceInsertRawHTML", false, "\x09"); // inserts tab
				evt.preventDefault();
				evt.stopPropagation();
				return false;
			  }
		  });
		}
	});
</script>');
	}
}
