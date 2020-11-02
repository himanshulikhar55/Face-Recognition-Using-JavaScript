<?php
	session_start();
	include_once "pdo.php";
	if(isset($_POST['cancel'])){
		header('Location: index.php');
		return;
	}
	if(isset($_POST['email']) && $_POST['error'] == "1"){
		unset($_POST['error']);
		die($_POST['username']);
		$_SESSION['succ'] = true;
		$path = $_POST['username'].'.jpeg';
		$sql = $pdo->prepare('INSERT INTO `user_data` (`username`, `email`, `pass`, `path`) VALUES (:username, :email, :pass, :file_path)');
		$sql->execute(array(':username' => $_POST['username'], ':email' => $_POST['email'], ':pass' => $_POST['pass'], ':file_path' => $path));
		header('Location: index.php');
		return;
	}
	if(isset($_POST['error']) && $_POST['error'] == "0"){
		$_SESSION['error'] = $_POST['error'];
		header('Location: register.php');
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
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
	<title>Registration Portal</title>
</head>
<body onload="startup(); clearphoto();">
	<header class="jumbotron">
			<img src="img/logo.png">
	</header>
	<div class="container">
		<?php
			if(isset($_SESSION['error'])){
				if($_SESSION['error']=="0")
					echo "<p style='color: red;'> Username already taken!</p>";
				unset($_SESSION['error']);
			}
		?>
		<div class="row">
			<form method="POST" id="details">
				<p>
					<label>Username:&nbsp;</label><input type="text" name="username" id="user" placeholder="Your username here" />
					<img id="spinner" src="./img/spinner.gif" height="25" style="vertical-align: middle; display:none;">
					<img id="available" src="./img/tick.jpeg" height="25" style="vertical-align: middle; display:none;">
					<img id="unavailable" src="./img/cross.jpeg" height="25" style="vertical-align: middle; display:none;">

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
					<input type="submit" name="cancel" value="Cancel" onclick="return cancel();" />
					<input type="text" name="error" id="error" type="hidden" value="" style="display: none;">
				</p>
			</form>
		</div>
		<div class="row">
			<label>Facial ID:</label>
			
			<div class="camera">
			    <video id="video">Video stream not available.</video>
			    <div class="row">
			    	<button id="start" onclick="takepicture();">Take photo</button>
			    </div>
			</div>
			<canvas id="canvas" style="display: none;"></canvas>
			<div class="output">
				<img id="photo" alt="The screen capture will appear in this box.">
				<button id="endbutton" onclick="clearphoto();">Clear</button>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript">
	$('#user').change(function(event){
    $('#spinner').show();
    var form = $('#details');
    var txt = form.find('input[name="username"]').val();
    window.console && console.log('Checking Username...');
    $.post( "check.php", { username: $("#user").val() }, function(data){
    	// alert(data);
		if(data=='1'){
			$('#spinner').hide();
			$('#available').show();
			window.console && console.log('Username Not Taken!');
			$('#error').val(data);
		}
		else if(data=='0'){
			$('#spinner').hide();
			$('#unavailable').show();
			window.console && console.log('Username Taken!');
			$('#error').val(data);
		}
	});
  });
</script>
</html>
