<h1>Vytvořit stránku</h1>
<?php
    if (isset($_POST["create"])) {
        if (!HToken::checkToken()) {
			Admin::ErrorMessage("Neplatný token, zkuste formulář odeslat znovu.");
		} else if (empty($_POST["title"]) || empty($_POST["content"])) {
            Admin::ErrorMessage("Všechna pole musí být vyplněna.");
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
			
            Admin::Message("Nová stránka úspěšně vytvořen. Upravit ji můžete na <a href='administrace-upravit-stranku-" . $id . "'>této stránce</a>.");
            echo('<script>$(document).ready(function() { window.location.replace("./administrace-upravit-stranku-' . $id . '"); }); </script>');
            
            $_POST["title"] = "";
            $_POST["content"]= "";
        }
    }
    
    $pages = Bundle\Page::ParentsOnly();
?>
<form method="POST">
	<?= HToken::html() ?>
	<h2>Základní informace</h2>
    <table id="article_table">
        <tr>
            <td width="110">Titulek stránky</td>
            <td><input type="text" name="title" value="<?= __POST("title") ?>" /></td>
        </tr>
        <tr>
            <td colspan="2">
                <textarea name="content" cols="80" rows="20" class="editor" id="editor"><?= __POST("content") ?></textarea>
            </td>
        </tr>
    </table>
    <h2>Ostatní nastavení</h2>
    <table id="article_table">    
        <tr>
            <td width="130">Přidat do menu</td>
            <td><input type="checkbox" value="1" name="menu" checked="" /></td>
        </tr> 
        <tr>
			<td>Nadřazená stránka</td>
			<td>
				<select name="pages">
					<option value="0">Žádná nadřazená</option>
					<?php foreach($pages as $page): ?>
					<option value="<?= $page->ID ?>"><?= $page->Title ?></option>"
					<?php endforeach; ?>
				</select>
			</td>
        </tr>
    </table>
    <input type="submit" value="Vytvořit stránku" name="create" />
   
</form>
