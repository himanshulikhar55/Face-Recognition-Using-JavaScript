var width = 320; // We will scale the photo width to this
var height = 0; // This will be computed based on the input stream

var streaming = false;

var video = null;
var canvas = null;
var endbutton = null;
var startbutton = null;

function startup() {
	document.getElementById('video-div').innerHTML = '\
        <div class="camera">\
            <video id="video">Video stream not available.</video>\
        </div>\
        <div><button id="startbutton">Take photo</button> &nbsp;\
        <button id="endbutton" onclick="clearphoto();">Take Again</button></div>\
        <canvas id="canvas"></canvas>\
        <div class="output">\
        </div>\
    </div>\
        ';
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    endbutton = document.getElementById('endbutton');
    startbutton = document.getElementById('startbutton');
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

    startbutton.addEventListener('click', function(ev) {
        takepicture();
        ev.preventDefault();
    }, false);
    clearphoto();
}


function clearphoto(){
    var context = canvas.getContext('2d');
    context.fillStyle = "#AAA";
    context.fillRect(0, 0, canvas.width, canvas.height);
}

function takepicture() {
    var context = canvas.getContext('2d');
    clearphoto();
    if (width && height) {
        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, 0, 0, width, height);
    }
}

// window.addEventListener('load', startup, false);
function redirectPage(){
	var username = document.getElementById('user').value;
	var email = document.getElementById('email').value;
	var pass = document.getElementById('pass').value;
	if(username === null || username === "" || email === "" || email === null || pass === "" || pass === null){
		alert("Please fill all the required credentials first!");
		return false;
	}
	if(/^[0-9a-zA-Z_.-]+$/.test(username) !== true){
		alert("Username is invalid");
		return false;
	}
}