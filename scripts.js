function loginScript() {
	console.log("Executing login script");
	//make ajax request to user table.
	//if user/pass combo does not exist, show error message
	//if it does exist, redirect and start session.
	var username = document.getElementById('username-input').value;
	var userpass = document.getElementById('password-input').value;
	var req = new XMLHttpRequest();
	var reqdata = "loginuser=" + username + "&loginpass=" + userpass;
	req.open("POST","lookup.php", true);
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if (req.status == 200) {
				console.log("Request went through to db.")
				console.log(req.responseText);
				var jsonResponse = JSON.parse(req.responseText);
			}
		}
	}
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	req.send(reqdata);
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
				var jsonResponse = JSON.parse(req.responseText);
				console.log(jsonResponse.user);
				if (jsonResponse.status == "failed") {
					document.getElementById('errorMsg').textContent = "That username already exists.";
				}
				else if (jsonResponse.status == "ok") {
					window.location.replace("login.php?signin=true");
				}
			}
			else {
				console.log("Request was unsuccessful.");
			}
		}
	}
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	req.send(reqdata);
};