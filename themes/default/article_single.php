<article>
    <h1><a href="<?= $Article->Url ?>"><?= $Article->Title ?></a></h1>
    <div class="info-in article"><p>Vytvořeno <?php if($Article->ShowDatetime): ?><strong><?= $Article->Datetime ?></strong><?php endif; ?> uživatelem 
        <strong><?= $Author->Username ?></strong>, kategorie
        <?= $Article->CategoriesString ?>
    </p></div>
    <div class="article_content"><?= $Article->Content ?></div>
    
    
    <!-- Přidat komentář -->
    <div id="add_comment">
        <?php if ((Bundle\User::IsLogged() || $Page->AllowUnregistredComments) && $Page->AllowComments): ?>
            <form method="POST">
                <table class="nostyle">
                    <tr>
                        <td><textarea name="bundle_comment_text"></textarea></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="bundle_comment_submit" value="Přidat komentář" /></td>
                    </tr>
                </table>
            </form>
        <?php else: ?>
            <em>Nejste přihlášený, pro přidávání komentářů <a href="./prihlaseni">se přihlašte</a>
                nebo <a href="./registrace">registrujte</a>, prosím.</em>
        <?php endif; ?>
    </div>
    <!-- Komentáře -->
    
    <?php $Article->Comments(); ?>
</article>
