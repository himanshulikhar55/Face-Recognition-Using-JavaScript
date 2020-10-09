var base64ToImage = require[('base64-to-image')];
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
    runFacialRecognition();
}
function cancel(){
    window.location = "index.php";
}
function runFacialRecognition(){
    startup();
    customCapture();
    var image = localStorage.getItem('himanshulikhar55');
    const MODEL_URL = '/cdit/img';
    faceapi.loadSsdMobilenetv1Model(MODEL_URL);
    faceapi.loadFaceLandmarkModel(MODEL_URL);
    faceapi.loadFaceRecognitionModel(MODEL_URL);

    var base64Str = image;
    var path = '/home/himanshulikhar55';
    var optionalObj = {'fileName':'trial','type':'png'};
    var imageInfo = base64ToImage(base64Str,path,optionalObj);

    // var fullFaceDescriptions = faceapi.detectAllFaces(image2);
    // faceapi.matchDimensions(image2, image1);
    // fullFaceDescriptions = faceapi.resizeResults(fullFaceDescriptions, image1);
    // faceapi.draw.drawDetections(image2, fullFaceDescriptions);
    // faceapi.draw.drawFaceLandmarks(image2, fullFaceDescriptions);
}