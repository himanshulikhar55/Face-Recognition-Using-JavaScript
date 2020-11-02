<?php
	include_once "pdo.php";
	$taken = false;
	if ( !isset($_POST['username']) ){
		echo '0';
	}
	else{
		$sql = $pdo->query('SELECT `username` FROM `user_data`');
		$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rows as $row){
			if (!strcmp($_POST['username'],$row['username'])){
				echo '0';
				$taken = true;
				break;
			}
		}
		if($taken == false)
			echo '1';
	}
?>