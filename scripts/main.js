//Client logic goes here

//prep the AJAX request
// var req = function ajax() {
// 	//Create new AJAX Object that supports all modern browsers
// 	if (window.XMLHttpRequest) {
// 		//code for everything above IE 6
// 		request = new XMLHttpRequest;
// 	}
// 	else {
// 		//code for IE 6 & 5
// 		request = new ActiveXObject("Microsoft.XMLHTTP");
// 	}

// 	//open connection and add custom header
// 	request.open("GET", "fetch.php", true); //asynchronous
// 	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

// 	//catch the response
// 	request.onreadystatechange = function() {
// 		if (request.readystate === 4 && request.status === 200) { //4 = request finished and response ready. 200 = status is "OK"
// 			response = request.responseText; //actually catch the response

// 			//if the response is null, tell me
// 			if (response === 'null'){
// 				console.log("response empty");
// 			}
// 			else {
// 				//print the json to console *TESTING*
// 				console.log("Here's the JSON");
// 				console.log(response);
// 			}
// 		}

// 		resquest.send("binary.json");

// 		return response;
// 	};

	
// }

// var resp = req();
// console.log(resp);

//the jquery way
$.ajax({
	url: "http://www.wesgilleland.com/projects/quizzes/fetch.php",
	success: function(result) {
		alert("success!");
		console.log(result);
	},
	data: "request=binary.json",
	dataType: "jsonp",
	crossDomain: true
});