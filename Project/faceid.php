<?php
	session_start();
	include_once "pdo.php";
	if(!isset($_SESSION['username'])){
		die("Unauthorized Action!");
	}
	if(isset($_POST['cancel'])){
		header('Location: index.php');
		return;
	}
	if(isset($_POST['imgSrc'])){
		$img = $_POST['imgSrc'];
		$img = str_replace('data:image/jpeg;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$imageData = base64_decode($img);
		$source = imagecreatefromstring($imageData);
		$fileName = './img/' . $_SESSION['username'] . '.jpeg';
		$imageSave = imagejpeg($source,$fileName,100);
		if($imageSave==false)
			die("Please give permission to save files!");
		$path = $_SESSION['username'].'.jpeg';
		$sql = $pdo->prepare('INSERT INTO `user_data` (`username`, `email`, `pass`, `path`) VALUES (:username, :email, :pass, :file)');	
		$success = $sql->execute(array(':username' => $_SESSION['username'], ':email' => $_SESSION['email'], ':pass' => $_SESSION['pass'], ':file' => $path));
		if($success == false)
			die("ERROR!");
		unset($_SESSION['username']);
		unset($_SESSION['email']);
		unset($_SESSION['pass']);
		$_SESSION['register'] = true;
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
		<p>
				<h5>Step 2/2: Register Facial ID</h5>
			</p>
		<div class="row" style="margin-left: 50px">
			<div class="camera">
			    <video id="video" autoplay="true">Video stream not available.</video>
			    <div class="row">
			    	<input type="button" id="start" onclick="takepicture()" value="Take photo" style="position: absolute; left: 20%">
			    </div>
			</div>
			<canvas id="canvas" style="display: none;"></canvas>
			<div class="output">
				<img id="photo" alt="The screen capture will appear in this box." >
				<p></p>
				<input type="button" id="endbutton" onclick="clearphoto()" value="Clear" style="position:relative; left: 30%"></input>
			</div>
		</div>
		<div class="row">
			<form method="POST" id="form">
				<input type="text" name="imgSrc" id="imgSrc" type="hidden" value="" style="display: none;">
				<input type="submit" name="submitCred" value="Register" style="position: absolute; left: 40%" onclick="return redirectPage();">
				<input type="submit" name="cancel" value="Cancel" style="position: absolute; left: 30%">
			</form>
		</div>
		<br>
		<br>
	</div>
</body>
<script type="text/javascript">
	var width = 320; // We will scale the photo width to this
	var height = 0; // This will be computed based on the input stream

	var streaming = false;
    let video = document.querySelector("#video");
    let currentStream;
    let displaySize;
    var canvas = document.getElementById('canvas');
    var endbutton = document.getElementById('endbutton');
    var start = document.getElementById('start');
    var photo = document.getElementById('photo');
	$(document).ready(function(){
        if (navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(function (stream) {
            	video.srcObject = stream;
            	video.play();
            })
            .catch(function (error) {
            console.log("Something went wrong!");
            });
        }
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
    });
    function clearphoto(){
	    var context = canvas.getContext('2d');
	    context.fillStyle = "#AAA";
	    context.fillRect(0, 0, canvas.width, canvas.height);
	    // var data = canvas.toDataURL('image/png');
	    photo.setAttribute('src', "");
	}

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
	
	function redirectPage(){
		var data = canvas.toDataURL('image/jpeg');
		$('#imgSrc').val(data);
		$('#form').submit();
		return true;
	}
</script>