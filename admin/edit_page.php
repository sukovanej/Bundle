<?php
    $ID = @explode("-", $subrouter)[2];
    $Page = new Bundle\Page($ID);
?>
<h1 class="page-header"><?= HLoc::l("Edit page"); ?>
    <a class="btn btn-primary pull-right" href="<?= $Page->Url ?>" target="_blank"><?= HLoc::l("View the page") ?></a>
</h1>
<?php
    $check = "";
    
    if ($Page->Menu == 1)
        $check = "checked";
    
    $pages = Bundle\Page::ParentsOnly();
?>

<form method="POST">
	<?= HToken::html() ?>
    <div class="col-md-12">
        <table class="table">
            <tr>
                <td width="130"><span class="table-td-title"><?= HLoc::l("Title") ?></span></td>
                <td><input type="text" class="form-control article-title" name="title" value="<?= $Page->Title ?>" /></td>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea name="content" cols="80" rows="20" class="editor article-content" id="editor"><?=
                        $Page->Content ?></textarea>
                </td>
            </tr>
            <tr>
                <td><span class="table-td-title"><?= HLoc::l("URL") ?></span></td>
                <td><input type="text" class="form-control article-url" class="width-long" name="url" value="<?= $Page->Url ?>" /></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <div class="well">
            <table>    
                <tr>
                    <td width="150"><?= HLoc::l("Add to the navigation") ?></td>
                    <td><input type="checkbox" class="form-control article-menu" value="1" name="menu" <?= $check ?> /></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="well">
            <table>  
                <tr>
                    <td width="150"><?= HLoc::l("Parent page") ?></td>
                    <td>
                        <select class="form-control article-parent" name="pages">
                            <option value="0">-</option>
                            <?php foreach($pages as $page): ?>
                                <?php
                                    $selected = "";

                                    if($page->ID == $Page->ID)
                                        continue;

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
    <hr />
    <p><strong><?= HLoc::l("Hint") ?></strong>: <?= HLoc::l("Store by pressing the key combination") ?> <kbd><kbd>ctrl</kbd> + <kbd>c</kbd></kbd></p>
</form>

<script type="text/javascript">
    $("form").submit(function(event) {
        var title = $(".article-title").val();
        var content = $(".article-content").val();
        var parent = $(".article-parent").val();
        var url = $(".article-url").val();
        var menu = $(".article-menu").is(':checked');

        $.ajax({
            asyc: true,
            method: "POST",
            url: "admin/ajax/edit_page.php",
            data: { id: <?= $ID ?>, token: <?= HToken::get() ?>, title: title, content: content, menu: menu, url: url, parent: parent }
        }).done(function(data) {
            $(".result-ajax").fadeIn(200).html(data).delay(2000).fadeOut(1000);
            //alert(data);
        }).fail(function(jqXHR, textStatus) {
            console.log("Nastala chyba: " + textStatus + "; " + jqXHR);
        });

        event.preventDefault();
    });
</script>