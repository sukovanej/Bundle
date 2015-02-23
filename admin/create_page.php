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
    <div class="col-md-8 pull-left">
        <table class="table">
            <tr>
                <td width="110"><span class="table-td-title"><?= HLoc::l("Title") ?></span></td>
                <td><input type="text" class="form-control" name="title" value="<?= __POST("title") ?>" /></td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea name="content" cols="80" rows="20" class="editor" id="editor"><?= __POST("content") ?></textarea>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-4 pull-right">
        <div class="well">
            <h4><?= HLoc::l("Options") ?></h4>
            <table>    
                <tr>
                    <td width="130"><?= HLoc::l("Add to a navigation") ?></td>
                    <td><input type="checkbox" value="1" name="menu" checked="" /></td>
                </tr> 
                <tr>
        			<td><?= HLoc::l("Parent page") ?></td>
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
