<?php	
	header("Vary: X-Requested-With");
	header("Content-Type: text/html; charset=utf-8");

	require("loader.php");

	if ($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest" && $_POST["token"] == HToken::get() && Bundle\User::IsLogged()
		&& Bundle\User::CurrentUser()->Role < 2) { // zjistit, jestli se jedná o AJAX

		$Page = new Bundle\Page($_POST["id"]);	

		if (empty($_POST["title"]) || empty($_POST["content"])) {
            Admin::ErrorMessage(HLoc::l("You must complete all fields"));
        } else if (Bundle\Url::IsDefinedUrl($_POST["url"]) && $_POST["url"] != $Page->Url) {
			Admin::ErrorMessage(HLoc::l("The URL is already used") . ".");
        } else {
            $Page->Update("Title", $_POST["title"]);
            $Page->Update("Content", $_POST["content"]);
			$Page->Update("Parent", $_POST["parent"]);
			
			$urlObj = Bundle\Url::InstByUrl($Page->Url);
			$urlObj->Update("Url", $_POST["url"]);
					
			if ($_POST["menu"] == "true" && !Bundle\Menu::Exists($Page->ID, "page")) {
				Bundle\Menu::Create(Bundle\Url::InstByUrl($Page->Url)->ID);
			} else if ($_POST["menu"] != "true" && Bundle\Menu::Exists($Page->ID, "page")) {
				Bundle\MenuItem::InstByUrl($Page->Url)->Delete();
			}
			
            $Page->InstUpdate();			
            
            $check = "";
			
			if (Bundle\Menu::Exists($Page->ID, "page"))
				$check = "checked";
            
            Admin::Message(HLoc::l("Page has been updated") . "!");
        }
	}
?>