<?php	
	header("Vary: X-Requested-With");
	header("Content-Type: text/html; charset=utf-8");

	require("loader.php");

	if ($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest" && $_POST["token"] == HToken::get() && Bundle\User::IsLogged()
		&& Bundle\User::CurrentUser()->Role < 2) { // zjistit, jestli se jedná o AJAX

		$Article = new Bundle\Article($_POST["id"]); // objekt článku

		if (empty($_POST["title"]) || empty($_POST["content"])) {
			Admin::ErrorMessage(HLoc::l("You must complete all fields"));
		} else if (Bundle\Url::IsDefinedUrl($_POST["url"]) && $_POST["url"] != $Article->Url) {
			Admin::ErrorMessage(HLoc::l("The URL is already used") . ".");
		} else {
			$show_datetime = 0;
			$show_comments = 0;
			$show_in_view = 0;
			
			$urlObj = Bundle\Url::InstByUrl($Article->Url);
			$urlObj->Update("Url", $_POST["url"]);
			
			if ($_POST["show_datetime"] == "true") { $show_datetime = 1; }
			if ($_POST["show_comments"] == "true") { $show_comments = 1; }
			if ($_POST["show_in_view"] == "true") { $show_in_view = 1; }
			
			// pokud se změní status z "koncept" na "publikován", vygenerovat aktuální datum
			if ($Article->Status == 2 && $_POST["status"] == 1)
				$Article->Update("Datetime", date('Y-m-d H:i:s'));
				
			$Article->Update("ShowDatetime", $show_datetime);
			$Article->Update("Title", $_POST["title"]);
			$Article->Update("Content", $_POST["content"]);
			$Article->Update("AllowComments", $show_comments);
			$Article->Update("ShowInView", $show_in_view);
			$Article->Update("Status", $_POST["status"]);
			$Article->DeleteCategories();
			
			$Article->InstUpdate();
		
			if (isset($_POST["categories"])) {
				foreach($_POST["categories"] as $cat) {
					Bundle\ArticleCategories::Create($Article->ID, $cat);
				}
			}
		
			$Article->InstUpdate();
			Admin::Message(HLoc::l("Article has been updated") . "!");
		}
	}
?>