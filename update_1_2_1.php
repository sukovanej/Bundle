<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Bundle 1.2.1 update</title>
        <link rel="stylesheet" href="admin/reset.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap core CSS -->
        <link href="admin/css/bootstrap.min.css" rel="stylesheet">
        <link href="admin/css/bootstrap-theme.min.css" rel="stylesheet">
        <script type="text/javascript" src="admin/jquery.js"></script>

        <style type="text/css">
        	content {display:block; padding:20px 50px;}
        	ul li {list-style-type: disc; list-style-position:inside; padding-left:10px;}
        </style>
    </head>
    <body>
    	<content>
    		<h1 class="page-header">Update to Bundle 1.2.1</h1>
    		
    		<?php if (isset($_POST["update"])): ?>
    			<?php
					define("_BD", "bundle");
					session_start();
					require("func/bundle_Loader.php");
					define("DB_PREFIX", (new Bundle\IniConfig("config.ini"))->db_prefix);

					$mysqli = Bundle\DB::Connect();
					$mysqli->query("INSERT INTO " . DB_PREFIX . "config (Name, Value) VALUES('Localization', 'en_gb')");
    			?>
    				<div class="alert alert-success">System has been successfully updated. Now <strong>remove this file</strong> and continue to the homepage.</div>
    		<?php endif; ?>

    		<h2>Changes</h2>
    		<ul>
    			<li>New administration style</li>
    			<li>New default theme</li>
    			<li>New packages</li>
    			<li>Improved system for packages</li>
    		</ul>
    		<form method="POST">
    			<button class="btn btn-primary btn-block" name="update">Update now</button>
    		</form>
    	</content>

        <script src="admin/js/bootstrap.min.js"></script>
    </body>
</html>
