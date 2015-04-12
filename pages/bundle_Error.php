<!DOCTYPE html>
<html>
    <head>
        <title>Error</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="icon" type="image/png" href="./images/icon.png" />
        <link rel="stylesheet" href="./admin/reset.css" />
        <link href="admin/css/bootstrap.min.css" rel="stylesheet">
        <link href="admin/css/bootstrap-theme.min.css" rel="stylesheet">
        <script type="text/javascript" src="admin/jquery.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
            $("a.trace-info-toggle").click(function() { 
                $("pre.trace-info").toggle(); 
            });
        });
        </script>
    </head>
    <body style="padding:50px">
        <div class="alert alert-danger">
            <h4>Nastala chyba:</h4>
            <p><strong>Zdroj: </strong> <?= $e->getFile() ?> (na řadku <?= $e->getLine() ?>)</p>
            <p><strong>Trasa: <a class="trace-info-toggle" href="#toggle_code">Zobrazit/skrýt</a></strong> 
                <pre class="trace-info" style="display:none"><?php print_r($e->getTrace()) ?></pre> 
            </p>
            <p><?= $e->getMessage() ?></p>
        </div>
    </body>
</html>
