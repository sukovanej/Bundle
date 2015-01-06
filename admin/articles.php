<h1>Přehled článků</h1>
<?php
    if (isset($_POST["article_delete"])) {
		if (!HToken::checkToken()) {
			Admin::ErrorMessage("Neplatný token, zkuste formulář odeslat znovu.");
		} else {
			$ID = $_POST["article_id"];
			$article = new Bundle\Article($ID);
			$article->Delete();
			
			if (Bundle\Menu::Exists($article->ID, "article"))
				Bundle\MenuItem::InstByUrl($article->Url)->Delete();
			
			Admin::Message("Článek <strong>" . $article->Title . "</strong> byl odstraněn.");
		}
    }
    
    $articles = Bundle\Article::GetAll();
?>

<?php if(Bundle\Article::CountAll() > 0s): ?>
<table class="table">
    <tr>
        <th>Název článku</th>
        <th class="mobile-hide">Datum vytvoření</th>
        <th class="mobile-hide">Autor</th>
        <th>Status</th>
        <th colspan="2">Upravit</th>
    </tr>
	<?php foreach($articles as $Article): ?>
		<tr>
			<td><img title="<?= $Article->StatusString ?>" class="user-role-img" src="./images/article/<?= $Article->Status ?>.png" /><a href="<?= $Article->Url ?>" target="_blank"><?= $Article->Title ?></a></td>
            <td class="mobile-hide"><?= $Article->Datetime ?></td>
            <td class="mobile-hide"><?= (new Bundle\User($Article->Author))->Username ?></td>
            <td><a href="./administrace-upravit-clanek-<?= $Article->ID ?>">Upravit</a></td>
            <td><em><?= $Article->StatusString ?></em></td>
            <td><a onclick="articleDelete('<?= $Article->ID ?>', <?= HToken::get() ?>)">Smazat</a></td></tr>
	<?php endforeach; ?>
</table>
<?php else: ?>
<em>Žádné články zatím nevytvořeny...</em>
<?php endif; ?>
