<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Instalace systému Bundle</title>
        <link rel="stylesheet" href="admin/reset.css" />
        <link rel="stylesheet" href="install/style.css" />
        <?php require("install/func.php"); ?>
    </head>
    <body>
		<h1>Instalace redakčního systému <strong>BUNDLE</strong> 1.2</h1> 
        <div id="page">
			<content>
				<?php require("install/controller.php"); ?>
				<?php if (!file_exists("config.ini")): ?>
					<h2 id="error">Konfigurační soubor nenalezen</h2>
					<p>Nebyl nalezen konfigurační soubor <em>config.ini</em>. Stáhněte si nejnovější systém Bundle ze stránek <a href="http://bundle-cms.cz">
					www.bundle-cms.cz</a>, nebo nový soubor config.ini vytvořte ručně v kořenovém aresáři systému. Poté instalaci opakujte.</p>
				<?php elseif (!isset($done) && @filesize("config.ini") == 0): ?>
					<form method="POST">
						<p>Vítejte na stránce instalátoru redakčního systému <strong>Bundle 1.2</strong>. Vyplňte,
						 prosím, následující údaje, které jádro systému potřebuje pro vytvoření konfiguračního
						souboru, nastavení základních informací webu a vytvoření defaultního administrátorského 
						účtu. Po úspěšné instalaci <strong>si přečtěte pečlivě informace o stavu stránek</strong>.
						Bude potřeba odstranit tento instalační soubor, aby v budoucnu nedošlo k bezpečnostním
						potížím.</p>
						
						<?php if ($error != ""): ?>
						<h2 id="error">Chyba</h2>
							<div id="error">
								<p><?= $error ?></p>
							</div>
						<?php endif; ?>
						<table style="margin:0 auto;">
							<tr>
								<td>
									<h2>Nastavení databáze</h2>
									<table>
										<tr>
											<td>Uživatelské jméno</td>
											<td><input type="text" name="data_name" value="<?= @$_POST["data_name"] ?>" /></td>
										</tr>
										<tr>
											<td>Heslo</td>
											<td><input type="password" name="data_password" value="<?= @$_POST["data_password"] ?>" /></td>
										</tr>
										<tr>
											<td>Host</td>
											<td><input type="text" name="data_host" value="<?= @$_POST["data_host"] ?>" /></td>
										</tr>
										<tr>
											<td>Databáze</td>
											<td><input type="text" name="data_db" value="<?= @$_POST["data_db"] ?>" /></td>
										</tr>
										<tr>
											<td>Prefix tabulek</td>
											<td><input type="text" name="data_db_prefix" value="bundle_" /></td>
										</tr>
									</table>
								</td>
								<td>
									<h2>Nastavení webu</h2>
									<table>
										<tr>
											<td>Název webu</td>
											<td><input type="text" name="web_name" value="<?= @$_POST["web_name"] ?>" /></td>
										</tr>
										<tr>
											<td>Autor webu</td>
											<td><input type="text" name="web_author" value="<?= @$_POST["web_author"] ?>" /></td>
										</tr>
									</table>
									
									<p>Ujistěte se, prosím, že má soubor <em>config.ini</em> nastavená
									práva pro zápis!</p>
								</td>
							</tr>
							<tr>
								<td>
									<h2>Vytvoření výchozího administrátorského účtu</h2>
									<table>
										<tr>
											<td>Uživatelské jméno</td>
											<td><input type="text" name="admin_name" value="<?= @$_POST["admin_name"] ?>" /></td>
										</tr>
										<tr>
											<td>Heslo</td>
											<td><input type="password" name="admin_password" value="<?= @$_POST["admin_password"] ?>" /></td>
										</tr>
										<tr>
											<td>Heslo znovu</td>
											<td><input type="password" name="admin_password_again" value="<?= @$_POST["admin_password_again"] ?>" /></td>
										</tr>
										<tr>
											<td>Email</td>
											<td><input type="text" name="admin_email" value="<?= @$_POST["admin_email"] ?>" /></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<hr />
						<h2>Výběr výchozích balíčků</h2>
						
						<?php $scanned_directory = array_diff(scandir("packages"), array('..', '.')); ?>
						<table class="table">
							<tr>
								<th></th>
								<th>Balíček</th>
								<th>Popis</th>
								<th>Autor</th>
								<th>Definice</th>
							</tr>
							<?php require("func/bundle_IniConfig.php"); ?>
							<?php foreach($scanned_directory as $file): ?>
								<?php if(is_dir("packages/" . $file)): ?>
								<?php $config = new Bundle\IniConfig("packages/" . $file . "/info.conf") ?>
								<tr>
									<td><input type="checkbox" name="packages[]" value="<?= $file ?>" checked /></td>
									<td><strong><?= $config->name ?></strong></td>
									<td><?= $config->description ?></td>
									<td><?= $config->author ?></td>
									<td><em><?= $file ?></em></td>
								</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						</table>
						
						<input type="submit" value="Pokračovat" name="submit_1" />
					</form>
				<?php elseif(isset($_POST["submit_1"])): ?>
					<h2>Instalace byla dokončena</h2>
					<div id="result-script">
						<?= $result; ?>
					</div>
					<p>Po otevření kořenové adresy webu v prohlížeči by se měl zobrazit již fungující webu se
						systémem Bundle. Bezprostředně po dokončení instalace odstrante soubor install.php a složku install.</p>
					<p>Příjemnou práci se systémem Bundle Vám přeje <strong>Milan Suk</strong>, autor projektu.</p>
					<p><a href="./">Přejít na web!</a></p>
				<?php elseif(filesize("config.ini") != 0): ?>
					<p>Konfigurační soubor je už nastaven, takže se redakční systém považuje za nainstalovaný. Odstraňte soubor install.php a složku install, aby
						v budoucnu nedošlo k bezpečnostním problémům.</p>
					<p>Pokud jste instalaci ještě neprovedli, odstraňte konfigurační soubor <em>config.ini</em> a vytvořte nový tak, aby byl úplně
						prázdný. Při dalším pokusu o instalaci by se již měl zobrazit instalační formulář namísto tohoto textu!</p>
				<?php endif; ?>
			</content>
        </div>
    </body>
</html>
