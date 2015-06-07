function loginScript() {
	console.log("Executing login script");
};

function signupScript() {
	console.log("Executing signup script");	
	//make ajax request to user table.
	//if user already exists, show error message.
	//if user does not exist, send a post request
		//to signup.php adding the user.
	var username = document.getElementById('username-input').value;
	var userpass = document.getElementById('password-input').value;
	var req = new XMLHttpRequest();
	var reqdata = "username=" + username + "&userpass=" + userpass;
	req.open("POST","lookup.php",true);

		//wait for response
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if (req.status == 200) {
				console.log("Request went through to db.")
				console.log(req.responseText);
				var jsonResponse = eval("(" + req.responseText + ")");
				console.log(jsonResponse.user);
			}
			else {
				console.log("Request was unsuccessful.");
			}
		}
	}
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	req.send(reqdata);
};