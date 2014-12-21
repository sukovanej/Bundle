<h1>Nastavení</h1>
<div id="dynamic-content">
    <?php
        if (isset($_POST["update"])) {
            if (empty($_POST["name"]) || empty($_POST["author"])) {
                Admin::ErrorMessage("Je potřeba vyplnit všechny hodnoty.");
            } else {
				function checked($post) {
					if(isset($_POST[$post]))
						return 1;
					else
						return 0;
				}
				
                $Page->Update("Name", $_POST["name"]);
                $Page->Update("PagerMax", $_POST["pager"]);
                $Page->Update("Author", $_POST["author"]);
                $Page->Update("CommentText", $_POST["comment"]);
                $Page->Update("Icon", $_POST["icon"]);
                $Page->Update("Theme", $_POST["theme"]);
                $Page->Update("Homepage", $_POST["homepage"]);	
                $Page->Update("AllowComments", checked("allow_comments")); 
                $Page->Update("AllowRegister", checked("allowregister"));
                $Page->Update("AllowUnregistredComments", checked("allow_unregistred_comments"));
                $Page->Update("AllowUserPhoto", checked("allow_photo"));
                $Page->Update("HomeMenu", checked("home_menu"));
                $Page->Update("PackagesMenu", checked("packages_menu"));
                $Page->Update("PagesMenu", checked("pages_menu"));
                $Page->Update("ArticlesMenu", checked("articles_menu"));
                $Page->Update("UserPhotoMaxSize", $_POST["max_photo_size"]);
                $Page->Update("HomeMenuTitle", $_POST["home_title"]);
                $Page->Update("Footer", $_POST["footer_content"]);
                Admin::Message("Nastavení bylo aktualizováno.");
                $Page->InstUpdate();
            }
        }
    ?>
<form method="POST">
    <h2>Základní nastavení webu</h2>
    <table>
        <tr>
            <td>Název webu</td>
            <td><input type="text" size="50" value="<?= $Page->Name ?>" name="name" /></td>
        </tr>
        <tr>
            <td>Autor webu</td>
            <td><input type="text" size="20" value="<?= $Page->Author ?>" name="author" /></td>
        </tr>
        <tr>
            <td>Ikona webu</td>
            <td><input type="text" size="45" value="<?= $Page->Icon ?>" name="icon" 
                       onchange="changeImg()" />
                <img style="float:right; width:24px; margin-left:5px;" 
                     src="<?= ("./" . $Page->Icon) ?>" id="admin_img_icon" />
            </td>
        </tr>
        <tr>
            <td>Vzhled</td>
            <td>
                <select name="theme">
                    <?php
                        if ($handle = opendir('./themes')) {
                            while (false !== ($entry = readdir($handle))) {
                                if ($entry != "." && $entry != "..") {
                                    $selected = "";
                                    if ($entry == $Page->Theme)
                                        $selected = "selected='selected'";
                                    
                                    echo "<option value='" .$entry . "' " . $selected . ">" 
                                            . $entry . "</option>\n";
                                }
                            }
                            closedir($handle);
                        }
                    ?>
                </select>
            </td>
        </tr>
		<tr>
            <td>Text patičky</td>
            <td><textarea name="footer_content" cols="55" rows="5" class="editor" id="editor"><?= $Page->Footer ?></textarea></td>
        </tr>
    </table>
    
	<h2>Obsah</h2>
    <table>
        <tr>
            <td>Počet článků na stránku</td>
            <td><input type="text" size="3" value="<?= $Page->PagerMax ?>" name="pager" /></td>
        </tr>
		<tr>
            <td>Obsah výchozí stránky</td>
            <td>
                <select name="homepage">
                    <option value="0">Nejnovější články</option>
                    <?php
                        $pages = Bundle\DB::Connect()->query("SELECT ID, Title FROM " . DB_PREFIX . "pages ORDER BY Title");
                        
                        while($page = $pages->fetch_object()) { 
                            $selected = "";
                            
                            if ($page->ID == $Page->Homepage)
                                $selected = "selected";
                    ?>
                    <option value="<?= $page->ID ?>" <?= $selected ?>><?= $page->Title ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>		
    </table>
    
    <h2>Nastavení komentářů</h2>
    <table>
        <tr>
            <td>Povolit komentáře</td>
            <td><input type="checkbox" name="allow_comments" <?php if($Page->AllowComments == 1)
                echo "checked";?>></td>
        </tr>
        <tr>
            <td>Text označující komentář jako nevhodný</td>
            <td><textarea name="comment" id="comment_textarea"><?= $Page->CommentText ?></textarea></td>
        </tr>
        <tr>
            <td>Neregistrovaní uživatelé mohou přidávat komentáře</td>
            <td><input type="checkbox" name="allow_unregistred_comments" <?php if($Page->AllowUnregistredComments == 1)
                echo "checked";?>></td>
        </tr>
    </table>
    
    <h2>Nastavení navigačního menu</h2>
    <table>
        <tr>
            <td>Přidat položku hlavní stránky</td>
            <td><input type="checkbox" name="home_menu" <?php if($Page->HomeMenu == 1)
                echo "checked";?>></td>
        </tr>
        <tr>
            <td>Titulek hlavní stránky</td>
            <td><input type="text" name="home_title" value="<?= $Page->HomeMenuTitle ?>" /></td>
        </tr>
        <tr>
            <td>Povolit přidávání stránek</td>
            <td><input type="checkbox" name="pages_menu" <?php if($Page->PagesMenu == 1)
                echo "checked";?>></td>
        </tr>
        <tr>
            <td>Povolit přidávání balíčků</td>
            <td><input type="checkbox" name="packages_menu" <?php if($Page->PackagesMenu == 1)
                echo "checked";?>></td>
        </tr>
        <tr>
            <td>Povolit přidávání článků</td>
            <td><input type="checkbox" name="articles_menu" <?php if($Page->ArticlesMenu == 1)
                echo "checked";?>></td>
        </tr>
    </table>
    
	<h2>Nastavení uživatelských účtů</h2>
    <table>
		<tr>
            <td>Povolit registraci na web</td>
            <td><input type="checkbox" name="allowregister" <?php if($Page->AllowRegister == 1)
                echo "checked";?> /></td>
        </tr>
        <tr>
            <td>Povolit fotografie k uživatelským účtům</td>
            <td><input type="checkbox" name="allow_photo" <?php if($Page->AllowUserPhoto == 1)
                echo "checked";?> /></td>
        </tr>
        <tr>
            <td>Maximální velikost nahrané fotografie (kB)</td>
            <td><input type="text" name="max_photo_size" value="<?= $Page->UserPhotoMaxSize ?>" /></td>
        </tr>
    </table>
    
    <input type="submit" value="Uložit nastavení" name="update" id="config_submit" />
</form>
</div>
