function redirectPage(){
	var username = document.getElementById('username').value;
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
}
function cancel(){
    window.location = "index.php";
}
