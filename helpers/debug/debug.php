<?php

//error_reporting(0);
function customError($errno, $errstr, $err_file, $errline, $errcontext) {
	ob_end_clean();
	require("error_template.php");
	die();
}

set_error_handler("customError");
