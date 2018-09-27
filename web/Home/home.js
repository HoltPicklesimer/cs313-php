/* Enlarge the image when the mouse is over it */
function enlarge() {
	document.getElementById("pic").style.width = "35%";
	document.getElementById("pic").style.height = "35%";
}

/* Shrink the image when the mouse has left */
function shrink() {
	document.getElementById("pic").style.width = "25%";
	document.getElementById("pic").style.height = "25%";
}

/* Clear errors from the form */
function clearErrors() {
	document.getElementById("passError").style.display = "none";
	document.getElementById("userError").style.display = "none";
	document.getElementById("secret").style.display = "none";
	document.getElementById("pass").style.backgroundColor = "white";
	document.getElementById("user").style.backgroundColor = "white";
}

/* Displays the secret message if the user and pass are correct */
function display() {

	// Clean up leftover errors
	clearErrors();

	user = document.getElementById("user").value;
	pass = document.getElementById("pass").value;

	error = false;

	// Find errors and point any out
	if (user != "hello"){
		error = true;
		document.getElementById("user").style.backgroundColor = "salmon";
		document.getElementById("userError").style.display = "inline";
	}

	if (pass != "world"){
		error = true;
		document.getElementById("pass").style.backgroundColor = "salmon";
		document.getElementById("passError").style.display = "inline";
	}

	// Display the secret message if there are no errors
	if (!error){
		document.getElementById("secret").style.display = "inline";
	}
}