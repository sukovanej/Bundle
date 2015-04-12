<h1 class="page-header"><?= HLoc::l("New page") ?></h1>
<?php
    if (isset($_POST["create"])) {
        if (empty($_POST["title"]) || empty($_POST["content"])) {
            Admin::ErrorMessage(HLoc::l("You must complete all fields"));
        } else {
            $menu = 0;
            if (isset($_POST["menu"]))
                $menu = 1;
                
            $id = Bundle\Page::Create($_POST["title"], $_POST["content"], $menu, $User->ID, $_POST["pages"]);
            
            $new_page = new Bundle\Page($id);
            
			if (isset($_POST["menu"]) && !Bundle\Menu::Exists($new_page->ID, "page")) {
				Bundle\Menu::Create(Bundle\Url::InstByUrl($new_page->Url)->ID);
			} else if (!isset($_POST["menu"]) && Bundle\Menu::Exists($new_page->ID, "page")) {
				Bundle\MenuItem::InstByData($Page->ID, "page")->Delete();
			}
			
            Admin::Message(HLoc::l("New page has been created") . "...");
            echo('<script>$(document).ready(function() { window.location.replace("./administration-edit-page-' . $id . '"); }); </script>');
            
            $_POST["title"] = "";
            $_POST["content"]= "";
        }
    }
    
    $pages = Bundle\Page::ParentsOnly();
?>
<form method="POST">
	<?= HToken::html() ?>
    <div class="col-md-12">
        <table class="table">
            <tr>
                <td><input type="text" class="form-control" name="title" value="<?= __POST("title") ?>" placeholder="<?= HLoc::l("Title") ?>..." /></td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea name="content" cols="80" rows="20" class="editor" id="editor"><?= __POST("content") ?></textarea>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-6 pull-right">
        <div class="well">
            <table>    
                <tr>
                    <td width="150"><?= HLoc::l("Add to a navigation") ?></td>
                    <td><input type="checkbox" value="1" name="menu" class="form-control" checked="" /></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-6 pull-left">
        <div class="well">
            <table>
                <tr>
                    <td width="160"><?= HLoc::l("Parent page") ?></td>
                    <td>
                        <select class="form-control" name="pages">
                            <option value="0"> - </option>
                            <?php foreach($pages as $page): ?>
                            <option value="<?= $page->ID ?>"><?= $page->Title ?></option>"
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="clearfix"></div>
    <input type="submit" class="btn btn-lg btn-success btn-block" value="<?= HLoc::l("Save") ?>" name="create" />
   
</form>
