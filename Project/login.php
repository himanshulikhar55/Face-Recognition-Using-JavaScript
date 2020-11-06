<?php
	session_start();
    include_once "pdo.php";

	if(isset($_POST['cancel']))
		header('Location: index.php');
    if(isset($_POST['username'])){
        $sql = $pdo->prepare('SELECT `username` FROM `user_data` WHERE (username = :username)');
        $rows = $sql->execute(array(':username' => $_POST['username']));
        if($rows){
            $_SESSION['login'] = true;
            $_SESSION['username'] = $_POST['username'];
            header('Location: index.php');
            return;
        }
        $_SESSION['error'] = true;
        header('Location: login.php');
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
    <title>Login Portal</title>
</head>
<body>
    <header class="jumbotron">
            <img src="img/logo.png">
    </header>
    <div class="container">
        <div class="row">
        	<?php
        		if(isset($_SESSION['error'])){
        			echo "<p style='color: red>Cannot find a user that matches you!</p>'";
        		}
        	?>
            <p>
                <label>Facial ID:</label>
            </p>
            <p>
                <video autoplay="true" id="videoElement"></video>
                <canvas id="canvas" style="position: absolute; left: 75px; top: 400px;"></canvas>
            </p>
            <form method="POST" id="form">
                <p>
                	<input name="username" id="username" type="hidden" value="" style="display: none;">
                	<input type="submit" name="submitForm" value="" style="display: none;">
                </p>
            </form>
        </div>
    </div>   
    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/face-api.js"></script>
    <script>
    	const labels = [""];

        $.post( "fetch.php", function(data){
			for (var i = 0, len = data.length; i < len; i++) {
		        labels[i] = data[i];
		        // console.log(labels[i]);
		    }
		});

        $(document).ready(function(){

            let video = document.querySelector("#videoElement");
            let currentStream;
            let displaySize;

            if (navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                video.srcObject = stream;
                })
                .catch(function (error) {
                console.log("Something went wrong!");
                });
            }
            
            let temp = []
            $("#videoElement").bind("loadedmetadata", function(){
                displaySize = { width:this.scrollWidth, height: this.scrollHeight }

                async function detect(){

                    const MODEL_URL = './models'

                    await faceapi.loadSsdMobilenetv1Model(MODEL_URL)
                    await faceapi.loadFaceLandmarkModel(MODEL_URL)
                    await faceapi.loadFaceRecognitionModel(MODEL_URL)

                    let canvas = $("#canvas").get(0);

                    facedetection = setInterval(async () =>{

                        let fullFaceDescriptions = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors()
                        let canvas = $("#canvas").get(0);
                        faceapi.matchDimensions(canvas, { width:300, height: 275 })

                        const fullFaceDescription = faceapi.resizeResults(fullFaceDescriptions, displaySize)
                        faceapi.draw.drawDetections(canvas, fullFaceDescriptions)

                        const labeledFaceDescriptors = await Promise.all(
                            labels.map(async label => {
                                console.log(label);

                                // fetch image data from urls and convert blob to HTMLImage element
                                const imgUrl = `./img/${label}.jpeg`
                                const img = await faceapi.fetchImage(imgUrl)
                                
                                // detect the face with the highest score in the image and compute it's landmarks and face descriptor
                                const fullFaceDescription = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor()
                                
                                if (!fullFaceDescription) {
                                throw new Error(`no faces detected for ${label}`)
                                }
                                
                                const faceDescriptors = [fullFaceDescription.descriptor]
                                return new faceapi.LabeledFaceDescriptors(label, faceDescriptors)
                            })
                        );

                        const maxDescriptorDistance = 0.6
                        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, maxDescriptorDistance)

                        const results = fullFaceDescriptions.map(fd => faceMatcher.findBestMatch(fd.descriptor))

                        results.forEach((bestMatch, i) => {
                            console.log(bestMatch.toString());
                            const box = fullFaceDescriptions[i].detection.box
                            const text = bestMatch.toString()
                            var start = text.lastIndexOf("(");
                            var end = text.lastIndexOf(")");
                            if(start!=-1){
                                var num = text.substring(
                                start + 1, end);
                                var val = parseFloat(num);
                                if(val >0.6){
                                    alert("No match found!!");
                                }
                            }
                            const drawBox = new faceapi.draw.DrawBox(box, { label: text })
                            drawBox.draw(canvas)
                            var user = "";
                            for(var i=0;i<text.length;i++){
								if(text[i]!=' ')
                            		user += text[i];
                            	else
                            		break;
                            }
                            $('#username').val(user);
                            $('#form').submit();
                        })

                    },2000);

                    console.log(displaySize)
                }
                detect()
                // console.log(this.scrollHeight);
            });   

      	})
            
        </script>
    </body>
</html>
