<h1 class="page-header"><?= HLoc::l("Edit page"); ?></h1>
<?php
    $ID = @explode("-", $subrouter)[2];
    $Page = new Bundle\Page($ID);
    
    $check = "";
    
    if ($Page->Menu == 1)
        $check = "checked";
    
    if (isset($_POST["edit"])) {
		if (empty($_POST["title"]) || empty($_POST["content"])) {
            Admin::ErrorMessage(HLoc::l("You must complete all fields"));
        } else if (Bundle\Url::IsDefinedUrl($_POST["url"]) && $_POST["url"] != $Page->Url) {
			Admin::ErrorMessage(HLoc::l("The URL is already used") . ".");
        } else {
            $Page->Update("Title", $_POST["title"]);
            $Page->Update("Content", $_POST["content"]);
			$Page->Update("Parent", $_POST["pages"]);
			
			$urlObj = Bundle\Url::InstByUrl($Page->Url);
			$urlObj->Update("Url", $_POST["url"]);
					
			if (isset($_POST["menu"]) && !Bundle\Menu::Exists($Page->ID, "page")) {
				Bundle\Menu::Create(Bundle\Url::InstByUrl($Page->Url)->ID);
			} else if (!isset($_POST["menu"]) && Bundle\Menu::Exists($Page->ID, "page")) {
				Bundle\MenuItem::InstByUrl($Page->Url)->Delete();
			}
			
            $Page->InstUpdate();			
            
            $check = "";
			
			if (Bundle\Menu::Exists($Page->ID, "page"))
				$check = "checked";
            
            Admin::Message(HLoc::l("Page has been created") . ": <strong>" . $Page->Title . "</strong>.");
        }
    }
    
    $pages = Bundle\Page::ParentsOnly();
?>

<form method="POST">
	<?= HToken::html() ?>
    <div class="col-md-8 pull-left">
        <table class="table">
            <tr>
                <td width="130"><span class="table-td-title"><?= HLoc::l("Title") ?></span></td>
                <td><input type="text" class="form-control" name="title" value="<?= $Page->Title ?>" /></td>
            </tr>
            <tr>
    			<td><span class="table-td-title"><?= HLoc::l("URL") ?></span></td>
    			<td><input type="text" class="form-control" class="width-long" name="url" value="<?= $Page->Url ?>" /></td>
    		</tr>
            <tr>
                <td colspan="2">
                    <textarea name="content" cols="80" rows="20" class="editor" id="editor"><?=
                        $Page->Content ?></textarea>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-4 pull-right">
        <div class="well">
            <h4><?= HLoc::l("Options") ?></h4>
            <a class="btn btn-primary btn-block" href="<?= $Page->Url ?>" target="_blank"><?= HLoc::l("View the page") ?></a>
            <table>    
                <tr>
                    <td width="130"><?= HLoc::l("Add to the navigation") ?></td>
                    <td><input type="checkbox" value="1" name="menu" <?= $check ?> /></td>
                </tr> 
                <tr>
                    <td><?= HLoc::l("Parent page") ?></td>
                    <td>
                        <select class="form-control" name="pages">
                            <option value="0">-</option>
                            <?php foreach($pages as $page): ?>
                                <?php
                                    $selected = "";
                                    if($page->ID == $Page->Parent)
                                        $selected = " selected";
                                ?>
                                <option value="<?= $page->ID ?>" <?= $selected ?>><?= $page->Title ?></option>"
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="clearfix"></div>
    <input type="submit" class="btn btn-block btn-lg btn-primary" value="<?= HLoc::l("Save") ?>" name="edit" />
</form>
