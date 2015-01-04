<h1>Upravit stránku</h1>
<?php
    $ID = @explode("-", $subrouter)[2];
    $Page = new Bundle\Page($ID);
    
    $check = "";
    
    if ($Page->Menu == 1)
        $check = "checked";
    
    if (isset($_POST["edit"])) {
		if (!HToken::checkToken()) {
			Admin::ErrorMessage("Neplatný token, zkuste formulář odeslat znovu.");
		} else if (empty($_POST["title"]) || empty($_POST["content"])) {
            Admin::ErrorMessage("Všechna pole musí být vyplněna.");
        } else if (Bundle\Url::IsDefinedUrl($_POST["url"]) && $_POST["url"] != $Page->Url) {
			Admin::ErrorMessage("Tato URL adresa nelze použít");
        } else {
            $Page->Update("Title", $_POST["title"]);
            $Page->Update("Content", $_POST["content"]);
			$Page->Update("Parent", $_POST["pages"]);
			
			$urlObj = Bundle\Url::InstByUrl($Page->Url);
			$urlObj->Update("Url",$_POST["url"]);
					
			if (isset($_POST["menu"]) && !Bundle\Menu::Exists($Page->ID, "page")) {
				Bundle\Menu::Create(Bundle\Url::InstByUrl($Page->Url)->ID);
			} else if (!isset($_POST["menu"]) && Bundle\Menu::Exists($Page->ID, "page")) {
				Bundle\MenuItem::InstByData($Page->ID, "page")->Delete();
			}
			
            $Page->InstUpdate();			
            
            $check = "";
			
			if (Bundle\Menu::Exists($Page->ID, "page"))
				$check = "checked";
            
            Admin::Message("Stránka <em>" . $Page->Title . "</em> byla úspěšně upravena.");
        }
    }
    
    $pages = Bundle\Page::ParentsOnly();
?>

<form method="POST">
	<?= HToken::html() ?>
    <table id="article_table">
        <tr>
            <td width="130">Titulek stránky</td>
            <td><input type="text" name="title" value="<?= $Page->Title ?>" /></td>
        </tr>
        <tr>
			<td>URL</td>
			<td><input type="text" class="width-long" name="url" value="<?= $Page->Url ?>" />
				&nbsp; &rarr; &nbsp; <a href="<?= $Page->Url ?>" target="_blank">Zobrazit stránka</a></td>
		</tr>
        <tr>
            <td colspan="2">
                <textarea name="content" cols="80" rows="20" class="editor" id="editor"><?=
                    $Page->Content ?></textarea>
            </td>
        </tr>
    </table>
    <h2>Ostatní nastavení</h2>
    <table id="article_table">    
        <tr>
            <td width="130">Přidat do menu</td>
            <td><input type="checkbox" value="1" name="menu" <?= $check ?> /></td>
        </tr>
        <tr>
			<td>Nadřazená stránka</td>
			<td>
				<select name="pages">
					<option value="0">Žádná nadřazená</option>
					<?php foreach($pages as $page): ?>
					<?php
						$selected = "";
						if($page->ID == $Page->Parent)
							$selected = " selected";
					?>
					<option value="<?= $page->ID ?>"<?= $selected ?>><?= $page->Title ?></option>"
					<?php endforeach; ?>
				</select>
			</td>
        </tr>
    </table>
    <input type="submit" value="Upravit stránku" name="edit" />
</form>
