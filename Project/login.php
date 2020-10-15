<?php
	session_start();
    include_once "pdo.php";

	if(isset($_POST['cancel']))
		header('Location: index.php');
    if(isset($_POST['login'])){
        $path = $_POST['username'].'.jpeg';
        $sql = $pdo->prepare('SELECT * FROM `user_data` WHERE (:username, :pass)');
        $rows = $sql->execute(array(':username' => $_POST['username'], ':email' => $_POST['email'], ':pass' => $_POST['pass'], ':file_path' => $path));
        if($rows){
            $_SESSION['succ'] = true;
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
                    unset($_SESSION['error']);
                    echo "<p style='color: red;'>Invalid Username or Password</p>";
                }
            ?>
            <form method="POST">
                <p>
                    <label>Username:&nbsp;</label><input type="text" name="username" id="user" placeholder="Your username here" />
                </p>
                <p>
                    <label>Password:&nbsp;</label><input type="password" name="pass" id="pass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  title="Note: Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" />
                </p>
                <p>
                    <label>Facial ID:</label>
                </p>
                <p>
                    <video autoplay="true" id="videoElement"></video>
                    <canvas id="canvas" style="position: absolute; left: 75px; top: 400px;"></canvas>
                </p>
                <p>
                    <input type="submit" value="Login" name="login" onclick="return redirectPage();" />
                    &nbsp; &nbsp;
                    <input type="submit" name="cancel" value="Cancel" onclick="return cancel();" />
                </p>
            </form>
        </div>
    </div>   
    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/face-api.js"></script>
    <script>
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

                        const labels = ["img/himanshu"]

                        const labeledFaceDescriptors = await Promise.all(
                            labels.map(async label => {
                                // fetch image data from urls and convert blob to HTMLImage element
                                const imgUrl = `${label}.jpeg`
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
                            // const drawBox = new faceapi.draw.DrawBox(box, { label: text })
                            // drawBox.draw(canvas)
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