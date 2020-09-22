var width = 320; // We will scale the photo width to this
var height = 0; // This will be computed based on the input stream
    
var streaming = false;

var video = null;
var canvas = null;
var endbutton = null;
var start = null;
var photo = null;
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

// window.addEventListener('load', startup, false);
function redirectPage(){
	var username = document.getElementById('user').value;
	var email = document.getElementById('email').value;
	var pass = document.getElementById('pass').value;
    photo = document.getElementById('photo');
	if(username === null || username === "" || email === "" || email === null || pass === "" || pass === null){
		alert("Please fill all the required credentials first!");
		return false;
	}
	if(/^[0-9a-zA-Z_.-]+$/.test(username) !== true){
		alert("Username is invalid");
		return false;
	}
    if(photo.getAttribute('src') == "" || photo.getAttribute('src') == null){
        alert("Please register Facial ID");
        return false;
    }
    var canvas = document.getElementById("canvas");
    var data = canvas.toDataURL("image/jpeg");
    localStorage.setItem(username,data);
}
function cancel(){
    window.location = "index.php";
}
function customCapture(){
    while(document.getElementById('user').value == " " || document.getElementById('user').value == null){
        continue;
    }
    username = document.getElementById('user').value;
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    clearphoto();
    if (width && height) {
        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, 0, 0, width, height);
        var data = canvas.toDataURL('image/png');
    } else {
      clearphoto();
    }
    return data;
}
function runFacialRecognition(){
    startup();
    data = customCapture();
    var image = localStorage.getItem('himanshulikhar55');
    const MODEL_URL = '/cdit/img';
    faceapi.loadSsdMobilenetv1Model(MODEL_URL);
    faceapi.loadFaceLandmarkModel(MODEL_URL);
    faceapi.loadFaceRecognitionModel(MODEL_URL);

    var image1 = new Image();
    image1.src = localStorage.getItem('himanshulikhar55');
    document.body.appendChild(image1);

    var image2 = new Image();
    image2.src = data;
    document.body.appendChild(image2);

    // var fullFaceDescriptions = faceapi.detectAllFaces(image2);
    // faceapi.matchDimensions(image2, image1);
    // fullFaceDescriptions = faceapi.resizeResults(fullFaceDescriptions, image1);
    // faceapi.draw.drawDetections(image2, fullFaceDescriptions);
    // faceapi.draw.drawFaceLandmarks(image2, fullFaceDescriptions);
}