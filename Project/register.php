<?php
	session_start();
	if(isset($_POST['cancel'])){
		header('Location: index.php');
		return;
	}
	if(isset($_POST['email']) && $_POST['error'] == "1"){
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['pass'] = $_POST['pass'];
		header('Location: faceid.php');
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
<body>
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
				<h5>
					Step 1/2 : Register Credentials
				</h5>
				<br>
				<p>
					<label>Username:&nbsp;</label><input type="text" name="username" id="username" placeholder="Your username here" />
					<img id="spinner" src="./img/spinner.gif" height="25" style="vertical-align: middle; display:none;">
					<img id="available" src="./img/tick.jpeg" height="25" style="vertical-align: middle; display:none;">
					<img id="unavailable" src="./img/cross.jpeg" height="25" style="vertical-align: middle; display:none;">

				</p>
				<p>
					<label>Email ID:&nbsp;</label><input type="email" name="email" id="email" placeholder="example@gmail.com">
				</p>
				<p>
					<label>Password:&nbsp;</label><input type="password" name="pass" id="pass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="Note: Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Password123" />
				</p>
				<p>
					<input type="submit" value="Proceed" onclick="return redirectPage();" />
					&nbsp; &nbsp;
					<input type="submit" name="cancel" value="Cancel" onclick="return cancel();" />
					<input type="text" name="error" id="error" type="hidden" value="" style="display: none;">
				</p>
			</form>
		</div>
	</div>
</body>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript">
	$('#username').change(function(event){
		$('#available').hide();
		$('#unavailable').hide();
	    $('#spinner').show();
	    var form = $('#details');
	    var txt = form.find('input[name="username"]').val();
	    window.console && console.log('Checking Username...');
	    $.post( "check.php", { username: $("#username").val() }, function(data){
	    	var name = $("#username").val();
	    	if(name==""){
	    		$('#available').hide();
				$('#unavailable').hide();
			    $('#spinner').hide();
			    window.console && console.log('No username entered!');
	    	}
			else if(data=='1'){
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
