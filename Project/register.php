<?php
	session_start();
	include_once "pdo.php";
	if(isset($_POST['email'])){
		$sql = $pdo->prepare("INSERT INTO user_data(username, email, pass) VALUES(:username, :email, :pass)");
		$sql->execute(array(':username' => $_POST['username'], ':email' => $_POST['email'], ':pass' => $_POST['pass']));
		header('Location: index.php');
		return;
	}
	if(isset($_POST['cancel'])){
		header('Location: index.php');
		return;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
	<title>Registration Portal</title>
</head>
<body onload="startup(); clearphoto();">
	<header class="jumbotron">
			<img src="img/logo.png">
	</header>
	<div class="container">
		<div class="row">
			<p>
				<form method="POST">
					<p>
						<label>Username:&nbsp;</label><input type="text" name="username" id="user" placeholder="Your username here" />
					</p>
					<p>
						<label>Email ID:&nbsp;</label><input type="email" name="email" id="email" placeholder="example@gmail.com">
					</p>
					<p>
						<label>Password:&nbsp;</label><input type="password" name="pass" id="pass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="Note: Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" />
					</p>
					<p>
						<input type="submit" value="Register" onclick="return redirectPage();" />
						&nbsp; &nbsp;
						<input type="submit" name="cancel" value="Cancel" />
					</p>
				</form>
			</p>
		</div>
		<div class="row">
				<div class="camera">
				    <video id="video">Video stream not available.</video>
				    <div class="row">
				    	<button id="start" onclick="takepicture();">Take photo</button>
				    </div>
				</div>
				<canvas id="canvas"></canvas>
				<div class="output">
					<img id="photo" alt="The screen capture will appear in this box.">
					<button id="endbutton" onclick="clearphoto();">Clear</button>
				</div>
		</div>
	</div>
</body>
<script type="text/javascript" src="js/functions.js">
</script>
</html>
