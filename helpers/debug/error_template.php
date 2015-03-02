<!DOCTYPE html>
<html>
	<head>
		<title>Nastala chyba</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" href="admin/reset.css" />
		<link href='http://fonts.googleapis.com/css?family=Inconsolata:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<style>
			body {font-family:'Inconsolata';}
			header {background-color:#A52A2A; color:#fff; padding:10px; border-bottom:1px solid #601717;}
			header > h1 {display:block; font-size:30px; margin-bottom:25px;}
			content {display:block; padding:10px;}
			content > h1 {font-size:25px; color:#A52A2A; margin-bottom:10px;}
			content > code {border:1px solid #FFA500; display:block; background-color:#FFDB99; margin-bottom:10px; overflow:auto;}
			content > code p {padding:4px;}
			content > code p.selected {background-color:#FF9A9A; color:#751212;}
			
			strong {font-weight:bold;}
			em {font-style:italic;}
			.code_line {font-weight:bold; color:#A52A2A}
			.type {color:#0000FF; font-style:italic;}
			ul li {margin-bottom:10px; border:1px dashed #999; background-color:#eee; padding:4px; word-break:break-all; border-radius:5px;}
			ul li pre {max-width:100%; word-wrap: break-word; overflow:auto;}
			.pre-content {display:none;}
			a {color:#1E90FF; cursor:pointer;}
			a:hover {color:#0E5CA9;}
		</style>
		<script type="text/javascript" src="./admin/jquery.js"></script>
		<script>
			var i = 0;
			function toggle(name) {
				$(".pre-content-" + name).toggle(200);
				
				if (i++ % 2 == 0)
					$(".close_open_" + name).text("Zavřít");
				else
					$(".close_open_" + name).text("Otevřít");
			}
		</script>
	</head>
	<body>
		<header>
			<h1>Chyba [<?= $errno ?>], soubor <strong><?= $err_file ?></strong> na řádku <strong><?= $errline ?></strong></h1>
			<h2><?= $errstr ?></h2>
		</header>
		<content>
			<h1>Zdrojový kód</h1>
			<code>
				<pre><?php
					$file = file($err_file);
					if (isset($file[$errline - 4])) { echo "<p><span class='code_line'>" . ($errline - 3) . "</span>" . htmlspecialchars($file[$errline - 4]) . "</p>"; }
					if (isset($file[$errline - 3])) { echo "<p><span class='code_line'>" . ($errline - 2) . "</span>" . htmlspecialchars($file[$errline - 3]) . "</p>"; }
					if (isset($file[$errline - 2])) { echo "<p><span class='code_line'>" . ($errline - 1) . "</span>" . htmlspecialchars($file[$errline - 2]) . "</p>"; }
					if (isset($file[$errline - 1])) { echo "<p class='selected'><span class='code_line'>" . $errline . "</span>" . htmlspecialchars($file[$errline - 1]) . "</p>"; }
					if (isset($file[$errline])) { echo "<p><span class='code_line'>" . ($errline + 1) . "</span>" . htmlspecialchars($file[$errline ]) . "</p>"; }
					if (isset($file[$errline + 1])) { echo "<p><span class='code_line'>" . ($errline + 2) . "</span>" . htmlspecialchars($file[$errline +1]) . "</p>"; }
					if (isset($file[$errline + 2])) { echo "<p><span class='code_line'>" . ($errline + 3) . "</span>" . htmlspecialchars($file[$errline +2]) . "</p>"; }
				?></pre>
			</code>
			
			<h1>Proměnné</h1>
			<ul>
				<?php foreach($errcontext as $key => $array):  ?>
					<li><a onclick="toggle('<?= $key ?>')"><?= $key ?> (<span class="close_open_<?= $key ?>">Otevřít</span>)</a><pre><span class="pre-content pre-content-<?= $key ?>"><?php htmlspecialchars(print_r($array)); ?></span></pre></li>
				<?php endforeach; ?>
			</ul>
		</content>
	</body>
</html>
