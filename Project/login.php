<?php
	session_start();
	if(isset($_POST['cancel']))
		header('Location: index.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
	<title>
		Credential Registration Page
	</title>
</head>
<body>
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
						<label>Password:&nbsp;</label><input type="password" name="pass" id="pass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="Note: Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" />
					</p>
					<p>
						<input type="submit" name="login" value="Login" />
						&nbsp;
						<input type="submit" name="cancel" value="Cancel" />
					</p>
				</form>
			</p>
		</div>
	</div>
</body>
</html>