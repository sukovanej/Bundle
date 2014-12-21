<h1>Přehled</h1>
<?php
	$n_version = @file_get_contents('http://bundle-cms.cz/upload/sys_ver.txt');
	
	if(!$n_version)
		$n_version = "<span class='red'>data nedostupná</span>";
	
	if (trim($n_version) == $Page->Bundle)
		$act = "<span class='green'>aktuální</span>";
	else
		$act = "<span class='red'>starší</span>";
		
	$size_files = 0;
	$size_system = 0;
	
	$upload_1 = array_diff(scandir(getcwd() . "/upload/"), array('..', '.'));
	$upload_2 = array_diff(scandir(getcwd() . "/upload/users/"), array('..', '.'));
	$upload_count = count($upload_1) + count($upload_2) - 1;

	foreach($upload_1 as $file)
		$size_files += (filesize(getcwd() . "/upload/" . $file) / 1000000);
?>
<?php Bundle\Events::Execute("AdminHome"); ?>
<h2>Systém</h2>
<?php if(!isset($_SESSION["info_text"])): ?>
<div id="done">
		<p>Ahoj <strong><?= $User->Username ?></strong>, přihlášení do adminstrace proběhlo úspěšně. Nezapomeň, že nebudeš odhlášen do konce
			sezení nebo ručního odhlášení.</p>
</div>
<?php endif; ?>
<?php $_SESSION["info_text"] = "_"; ?>
<table>
	<tr>
		<td>Aktualizace</td>
		<td><strong><?= $act ?></strong></td>
		<td></td>
		<td>Verze</td>
		<td><strong><?= $Page->Bundle ?> &rarr; <span title="oficiální nejnovější">{ <?= $n_version ?> }</span></strong></td>
	</tr>
	<tr>
		<td>Systém</td>
		<td><strong>Bundle</strong></td>
		<td></td>
		<td>Doména</td>
		<td><strong><?= $_SERVER['SERVER_NAME'] ?></strong></td>
</table>
<h2>Obsah</h2>
<table>
	<tr>
		<td>Článků celkem</td>
		<td><strong><?= Bundle\Article::CountAll() ?></strong></td>
		<td></td>
		<td>Nainstalovaných balíčků</td>
		<td><strong><?= count(Bundle\Package::GetInstalledPackages()) ?></strong></td>
	</tr>
	<tr>
		<td>Stránek celkem</td>
		<td><strong><?= Bundle\Page::CountAll() ?></strong></td>
		<td></td>
		<td>Všechny balíčky</td>
		<td><strong><?= count(Bundle\Packages::GetPackages()) ?></strong></td>
	</tr>
	<tr>
		<td>Komentářů celkem</td>
		<td><strong><?= Bundle\Comment::CountAll() ?></strong></td>
		<td></td>
	</tr>
	<tr>
		<td>Kategorií celkem</td>
		<td><strong><?= Bundle\Category::CountAll() ?></strong></td>
		<td></td>
		<td>Obsahových generátorů</td>
		<td><strong><?= Bundle\Content::CountAll() ?></strong></td>
	</tr>
	<tr>
		<td>Celková velikost databáze</td>
		<td><strong> &#8776; <?= Bundle\DB::Size() ?> MB</strong></td>
	</tr>
</table>
<h2>Uživatelé</h2>
<table>
	<tr>
		<td>Celkem uživatelů</td>
		<td><strong><?= Bundle\User::Count()  ?></strong></td>
	</tr>
	<tr>
		<td>Administrátorů</td>
		<td><strong><?= Bundle\User::Count(0)  ?></strong></td>
	</tr>
	<tr>
		<td>Redaktorů</td>
		<td><strong><?= Bundle\User::Count(1)  ?></strong></td>
	</tr>
	<tr>
		<td>Uživatelů</td>
		<td><strong><?= Bundle\User::Count(2)  ?></strong></td>
	</tr>
</table>
<h2>Soubory</h2>
<table>
	<tr>
		<td>Celkem nahraných souborů</td>
		<td><strong><?= $upload_count ?></strong></td>
	</tr>
	<tr>
		<td>Velikost nahraných souborů</td>
		<td><strong> &#8776; <?= number_format($size_files, 2) ?> MB</strong></td>
	</tr>
</table>
