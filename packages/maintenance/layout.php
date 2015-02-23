<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?= HConfiguration::Get("Name") ?> - údržba webu</title>
		<style>
			div {max-width:1000px; margin:0 auto; margin-top:100px; background-color:#eee; padding:10px; border-radius:10px; border:1px solid #ccc;}
			div img {float:left;}
		</style>
	</head>
	<body>
		<div>
			<img src="<?= HPackage::getPath("maintenance") ?>/ico.png" />
			<h1>Režim údržby</h1>
			<p>Omlouváme se, momentálně probíhá na webu údržba. Zkuste se vrátit za chvíli!</p>
		</div>
	</body>
</html>
