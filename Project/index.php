<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
	<title>
		Credential Registration Page
	</title>
</head>
<body>
	<header class="jumbotron">
			<?php
				if(isset($_SESSION['username'])){
					echo "<a href='logout.php'>Logout</a>";
				}
			?>
			<img src="img/logo.png">
	</header>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h3>
					Welcome to C-DIT's portal!
				</h3>
			</div>
		<div>
			<?php
				if($_GET['logout']){
					echo "<p style='color: green;'>You have successfully logged out!</p>";
					unset($_GET['succ']);
				}
				if(isset($_SESSION['register'])){
					echo "<p style='color: green;'>You have successfully registered!</p>";
					unset($_SESSION['succ']);
				}
				if(isset($_SESSION['login'])){
					echo "<p style='color: green;'>Welcome, " . $_SESSION['username'] . "!</p>";
					unset($_SESSION['login']);
				}
			?>
		<?php
			if(!isset($_SESSION['username'])){
				echo '<div class="row">
			<div class="col-12">
				<p>
					If you have already registered, <a href="login.php">Login here</a>.
				</p>
				<p>
					Else, <a href="register.php">Register here</a>.
				</p>
			</div>
		</div>';
			}
		?>
	</div>
</body>
</html>