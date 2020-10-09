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
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

	<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
	<script src="js/face-api.js"></script>
	<script src="js/login-functions.js"></script>

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
					<div class="row">
					<label>Please look at the camera:&nbsp;</label>
					
					<div class="camera">
					    <video id="video">Video stream not available.</video>
					</div>
					<canvas id="canvas"></canvas>
					<img id="photo" alt="The screen capture will appear in this box." style="display: none;">
					</div>
					<p>
						<input type="submit" name="login" value="Login" />
						&nbsp;
						<input type="submit" name="cancel" value="Cancel" />
					</p>
					</div>
				</form>
			</p>
	</div>
</body>
</html>
<script type="text/javascript">
	function clearphoto(){
	    var context = canvas.getContext('2d');
	    context.fillStyle = "#AAA";
	    context.fillRect(0, 0, canvas.width, canvas.height);
	    // var data = canvas.toDataURL('image/png');
	    photo.setAttribute('src', "");
	}
	function startup() {
	    video = document.getElementById('video');
	    canvas = document.getElementById('canvas');
	    endbutton = document.getElementById('endbutton');
	    start = document.getElementById('start');
	    photo = document.getElementById('photo');
	    navigator.mediaDevices.getUserMedia({
	            video: true,
	            audio: false
	        })
	        .then(function(stream) {
	            video.srcObject = stream;
	            video.play();
	        })
	        .catch(function(err) {
	            console.log("An error occurred: " + err);
	    });

	    video.addEventListener('canplay', function(ev) {
	        if (!streaming) {
	            height = video.videoHeight / (video.videoWidth / width);

	            if (isNaN(height)) {
	                height = width / (4 / 3);
	            }

	            video.setAttribute('width', width);
	            video.setAttribute('height', height);
	            canvas.setAttribute('width', width);
	            canvas.setAttribute('height', height);
	            streaming = true;
	        }
	    }, false);
	}
	var width = 320; // We will scale the photo width to this
	var height = 0; // This will be computed based on the input stream
	    
	var streaming = false;

	var video = document.getElementById('video');
	var canvas = document.getElementById('canvas');
    var endbutton = document.getElementById('endbutton');
    var start = document.getElementById('start');
    var photo = document.getElementById('photo');
    startup();
	function takepicture(){
	    var context = canvas.getContext('2d');
	    clearphoto();
	    if (width && height) {
	        canvas.width = width;
	        canvas.height = height;
	        context.drawImage(video, 0, 0, width, height);
	        var data = canvas.toDataURL('image/png');
	        photo.setAttribute('src', data);
	    } else {
	      clearphoto();
	    }
	}
	$('#video').ready(takepicture());
	
</script>