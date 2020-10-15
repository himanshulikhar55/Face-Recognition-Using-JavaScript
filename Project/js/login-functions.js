// var width = 320; // We will scale the photo width to this
// var height = 0; // This will be computed based on the input stream
    
var streaming = false;

// var video = null;
// var canvas = null;
var endbutton = null;
var start = null;
var photo = null;
// function startup() {
//     video = document.getElementById('video');
//     canvas = document.getElementById('canvas');
//     endbutton = document.getElementById('endbutton');
//     start = document.getElementById('start');
//     photo = document.getElementById('photo');
//     navigator.mediaDevices.getUserMedia({
//             video: true,
//             audio: false
//         })
//         .then(function(stream) {
//             video.srcObject = stream;
//             video.play();
//         })
//         .catch(function(err) {
//             console.log("An error occurred: " + err);
//     });

//     video.addEventListener('canplay', function(ev) {
//         if (!streaming) {
//             height = video.videoHeight / (video.videoWidth / width);

//             if (isNaN(height)) {
//                 height = width / (4 / 3);
//             }

//             video.setAttribute('width', width);
//             video.setAttribute('height', height);
//             canvas.setAttribute('width', width);
//             canvas.setAttribute('height', height);
//             streaming = true;
//         }
//     }, false);
// }
function cancel(){
    window.location = "index.php";
}

const video = document.getElementById("video");
const isScreenSmall = window.matchMedia("(max-width: 700px)");
let predictedAges = [];

Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri("./models"),
  faceapi.nets.faceLandmark68Net.loadFromUri("./models"),
  faceapi.nets.faceRecognitionNet.loadFromUri("./models"),
  faceapi.nets.faceExpressionNet.loadFromUri("./models"),
  faceapi.nets.ageGenderNet.loadFromUri("./models")
]).then(startVideo);

function startVideo() {
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
}
function screenResize(isScreenSmall) {
  if (isScreenSmall.matches) {
    // If media query matches
    video.style.width = "320px";
  } else {
    video.style.width = "500px";
  }
}

screenResize(isScreenSmall); // Call listener function at run time
isScreenSmall.addListener(screenResize);

video.addEventListener("playing", () => {
  console.log("playing called");
  const canvas = faceapi.createCanvasFromMedia(video);
  let container = document.querySelector(".container");
  container.append(canvas);

  const displaySize = { width: video.width, height: video.height };
  faceapi.matchDimensions(canvas, displaySize);

  setInterval(async () => {
    const detections = await faceapi
      .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
      .withFaceLandmarks()
      .withFaceExpressions()
      .withAgeAndGender();

    const resizedDetections = faceapi.resizeResults(detections, displaySize);
    console.log(resizedDetections);

    canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

    faceapi.draw.drawDetections(canvas, resizedDetections);
    faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
    if (resizedDetections && Object.keys(resizedDetections).length > 0) {
      const age = resizedDetections.age;
      const interpolatedAge = interpolateAgePredictions(age);
      const gender = resizedDetections.gender;
      const expressions = resizedDetections.expressions;
      const maxValue = Math.max(...Object.values(expressions));
      const emotion = Object.keys(expressions).filter(
        item => expressions[item] === maxValue
      );
      document.getElementById("age").innerText = `Age - ${interpolatedAge}`;
      document.getElementById("gender").innerText = `Gender - ${gender}`;
      document.getElementById("emotion").innerText = `Emotion - ${emotion[0]}`;
    }
  }, 10);
});

function interpolateAgePredictions(age) {
  predictedAges = [age].concat(predictedAges).slice(0, 30);
  const avgPredictedAge =
    predictedAges.reduce((total, a) => total + a) / predictedAges.length;
  return avgPredictedAge;
}