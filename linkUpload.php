<?php
	session_start();
	array_push($_SESSION["LINKS"], $_POST["link"]);
	echo($_POST["link"]);
	exit;
?>