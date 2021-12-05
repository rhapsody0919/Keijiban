<?php
function connectDatabase(){
	$dsn = 'mysql:host=localhost; dbname=procir_terukina247; charset=utf8';
	$user = 'terukina247';
	$password = '9n9gpytkgy';
	$dbh = new PDO(
		$dsn,
		$user,
		$password,
		array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES => false,
		)
	);
	return $dbh;
}
?>
