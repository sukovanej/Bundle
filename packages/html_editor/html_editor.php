<?php

class html_editor extends Bundle\PackageBase {
	
	// Inicialize
	public function __construct() {
		$this->includes = array(
			"html_editor.php"
		);
		
		parent::__construct();
	}
	
	public function handle_AdminFooter() {
		echo('
		<script>
			var obj = $("textarea#editor");
			obj.css("display", "none");
			obj.removeAttr("id");
			obj.after("<pre style=\'width:" + obj.width() + "; height:" + obj.height() + "px; border:3px solid #1E90FF; border-radius:3px;\' id=\'editor\'>" + obj.html() + "</pre>");
			
			var editor = ace.edit("editor");
			editor.setTheme("ace/theme/' . HConfiguration::get("html_editor_theme") . '");
			editor.getSession().setMode("ace/mode/html");
			
			$("form").submit(function() {
				obj.html(editor.getSession().getValue());
				event.preventDefault();
			});
		</script>');
	}	
	
	// Install
	public function install() {
		configuration_Helper::Create('html_editor_theme', 'monokai');
			 
		Bundle\Events::Register(HPackage::getLastInstalled() + 1, "AdminFooter");
		
		return true;
	}
	
	public function uninstall() {
		return true;
	}
}
