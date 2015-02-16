<h1 class="page-header"><?= HLoc::l("Configuration") ?></h1>
<div id="dynamic-content">
    <?php
        if (isset($_POST["update"])) {
			if (empty($_POST["name"]) || empty($_POST["author"])) {
				Admin::ErrorMessage(HLoc::l("You must complete all fields"));
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
                
                if ($Page->Theme != $_POST["theme"] && file_exists("themes/" . $_POST["theme"] . "/install.php")) {
					require("themes/" . $_POST["theme"] . "/install.php");
					(new InstallTheme())->Install();
				}
				
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
                $Page->Update("Localization",  $_POST["localization"]);
                Admin::Message(HLoc::l("Changes have been saved") . ".");
                $Page->InstUpdate();
            }
        }
    ?>
<form method="POST">
<script>
    $(document).ready(function() {
        $(".nav-tabs-items").hide();

        $(".nav-tabs li").click(function() {
            $(".nav-tabs li").each(function() {
                var obj = $(this);
                obj.removeClass("active");

                $("#" + obj.attr("data")).hide();
            });

            var n_obj = $(this);
            n_obj.addClass("active");

            $("#" + n_obj.attr("data")).show();
        });
    });
</script>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active" data="package-default"><a href="#"><?= HLoc::l("Default") ?></a></li>
    <li role="presentation" data="package-content"><a href="#"><?= HLoc::l("Content") ?></a></li>
    <li role="presentation" data="package-comments"><a href="#"><?= HLoc::l("Comments") ?></a></li>
    <li role="presentation" data="package-navigation"><a href="#"><?= HLoc::l("Navigation") ?></a></li>
    <li role="presentation" data="package-users"><a href="#"><?= HLoc::l("Users") ?></a></li>
</ul>
<div id="package-default">
	<?= HToken::html() ?>
    <table class="table table-hover">
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Name") ?></span></td>
            <td><input type="text" class="form-control" size="50" value="<?= $Page->Name ?>" name="name" /></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Author") ?></span></td>
            <td><input type="text" class="form-control" size="20" value="<?= $Page->Author ?>" name="author" /></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Icon") ?> <img style="width:20px; margin-left:5px;" src="<?= ($Page->Icon) ?>" id="admin_img_icon" /></span></td>
            <td><input type="text" class="form-control" size="45" value="<?= $Page->Icon ?>" name="icon" 
                       onchange="changeImg()" />
            </td>
        </tr>
        <tr class="info">
            <td><span class="table-td-title"><?= HLoc::l("Template") ?></span></td>
            <td>
                <select class="form-control" name="theme">
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
            <td><span class="table-td-title"><?= HLoc::l("Language") ?></span></td>
            <td>
                <select class="form-control" name="localization">
                    <?php if ($handle = opendir('./localization')): ?>
                        <?php while (false !== ($entry = readdir($handle))): ?>
                            <?php if ($entry != "." && $entry != "..") : $selected = (($entry == $Page->Localization) ? "selected='selected'" : "") ?>
                                <option value="<?= $entry ?>" <?= $selected ?>><?= $entry ?></option>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php closedir($handle); endif; ?>
                </select>
            </td>
        </tr>
		<tr>
            <td><span class="table-td-title"><?= HLoc::l("Footer text") ?></span></td>
            <td><textarea class="form-control" name="footer_content" cols="55" rows="5" class="editor" id="editor"><?= $Page->Footer ?></textarea></td>
        </tr>
    </table>
</div>
<div id="package-content" class="nav-tabs-items">
    <table class="table table-hover">
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Articles on the page") ?></span></td>
            <td><input type="text" class="form-control" size="3" value="<?= $Page->PagerMax ?>" name="pager" /></td>
        </tr>
		<tr>
            <td><span class="table-td-title"><?= HLoc::l("Main page") ?></span></td>
            <td>
                <select class="form-control" name="homepage">
                    <option value="0"><?= HLoc::l("Latest articles") ?></option>
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
</div>
<div id="package-comments" class="nav-tabs-items">
    <table class="table table-hover">
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Allow comments") ?></span></td>
            <td><input type="checkbox" class="form-control" name="allow_comments" <?php if($Page->AllowComments == 1)
                echo "checked";?>></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Inappropriate comment") ?></span></td>
            <td><textarea name="comment" class="form-control" id="comment_textarea"><?= $Page->CommentText ?></textarea></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Unregistered users can add comment") ?></span></td>
            <td><input type="checkbox" class="form-control" name="allow_unregistred_comments" <?php if($Page->AllowUnregistredComments == 1)
                echo "checked";?>></td>
        </tr>
    </table>
</div>
<div id="package-navigation" class="nav-tabs-items">
    <table class="table table-hover">
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Enable homepage") ?></span></td>
            <td><input type="checkbox" class="form-control" name="home_menu" <?php if($Page->HomeMenu == 1)
                echo "checked";?>></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Title of the main page") ?></span></td>
            <td><input type="text" class="form-control" name="home_title" value="<?= $Page->HomeMenuTitle ?>" /></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Enable pages") ?></span></td>
            <td><input type="checkbox" class="form-control" class="form-control" class="form-control" class="form-control" class="form-control" class="form-control" name="pages_menu" <?php if($Page->PagesMenu == 1)
                echo "checked";?>></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Enable packages") ?></span></td>
            <td><input type="checkbox" class="form-control" class="form-control" class="form-control" class="form-control" class="form-control" name="packages_menu" <?php if($Page->PackagesMenu == 1)
                echo "checked";?>></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Enable articles") ?></span></td>
            <td><input type="checkbox" class="form-control" class="form-control" class="form-control" class="form-control" name="articles_menu" <?php if($Page->ArticlesMenu == 1)
                echo "checked";?>></td>
        </tr>
    </table>
</div>
<div id="package-users" class="nav-tabs-items">
    <table class="table table-hover">
		<tr>
            <td><span class="table-td-title"><?= HLoc::l("Enable registration") ?></span></td>
            <td><input type="checkbox" class="form-control" class="form-control" class="form-control" name="allowregister" <?php if($Page->AllowRegister == 1)
                echo "checked";?> /></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Enable profile photos") ?></span></td>
            <td><input type="checkbox" class="form-control" class="form-control" name="allow_photo" <?php if($Page->AllowUserPhoto == 1)
                echo "checked";?> /></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Max profile photos size (kB)") ?></span></td>
            <td><input type="text" class="form-control" name="max_photo_size" value="<?= $Page->UserPhotoMaxSize ?>" /></td>
        </tr>
    </table>
</div>
<input type="submit" value="<?= HLoc::l("Save") ?>" class="btn btn-primary btn-block" name="update" id="config_submit" />
</form>
</div>
