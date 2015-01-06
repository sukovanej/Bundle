<h1>Přehled podstránek</h1>
<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST" && !HToken::checkToken()) {
		Admin::ErrorMessage("Neplatný token, zkuste formulář odeslat znovu.");
	} else if (isset($_POST["page_delete"])) {
        $ID = $_POST["page_id"];
        $page = new Bundle\Page($ID);
        
        if (Bundle\Menu::Exists($ID, "page"))
			Bundle\MenuItem::InstByUrl($page->Url)->Delete();
			
        $page->Delete();
        Admin::Message("Stránka <strong>" . $page->Title . "</strong> byla odstraněna.");
    }
    
    $parents = Bundle\Page::ParentsOnly();
?>

<?php if(Bundle\Page::CountAll() > 0): ?>
<table class="table">
    <tr>
        <th>Titulek stránky</th>
        <th>Autor</th>
        <th colspan="2">Upravit</th>
    </tr>
    <?php foreach($parents as $Page): ?>
		<tr>
			<td><img src="./images/page-document.png" class="user-role-img" /><a href="./<?= $Page->Url ?>"><?= $Page->Title ?></a></td>
			<td><?= (new Bundle\User($Page->Author))->Username ?></td>
			<td><a href="./administrace-upravit-stranku-<?= $Page->ID ?>">Upravit</a></td>
			<td><a onclick="pageDelete('<?= $Page->ID ?>', '<?= HToken::get() ?>')">Smazat</a></td>
		</tr>
		<?php foreach($Page->Children() as $PageChild): ?>
			<tr class="menu-table-sub">
				<td class="menu-table-sub-td"><img src="./images/page-document.png" class="user-role-img" /> 
					<a href="./<?= $PageChild->Url ?>"><?= $PageChild->Title ?></a></td>
				<td><?= (new Bundle\User($PageChild->Author))->Username ?></td>
				<td><a href="./administrace-upravit-stranku-<?= $PageChild->ID ?>">Upravit</a></td>
				<td><a onclick="pageDelete('<?= $PageChild->ID ?>', '<?= HToken::get() ?>')">Smazat</a></td>
			</tr>
		<?php endforeach; ?>
    <?php endforeach; ?>
</table>
<?php else: ?>
<em>Žádná stránka zatím nevytvořena</em>
<?php endif; ?>
