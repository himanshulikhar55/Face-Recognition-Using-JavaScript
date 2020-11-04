<?php
	session_start();
	include_once "pdo.php";
	header('Content-Type: application/json; charset=utf-8');
	$sql = $pdo->query('SELECT `username` FROM `user_data`');
	$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
	$retval = array();
	foreach( $rows as $row ){
	  $retval[] = $row['username'];
	}
	echo( json_encode($retval, JSON_PRETTY_PRINT));
?>