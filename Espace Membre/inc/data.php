<?php
	try{
		$pdo = new PDO('mysql:dbname=madb;host=127.0.0.1;charset=utf8', 'root', '');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}
	catch(Exception $e){
		die('Erreur :' . $e->getMessage());
	}
	?>
	