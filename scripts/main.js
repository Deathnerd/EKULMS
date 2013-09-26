//Client logic goes here
$.ajax({
	url: "http://www.wesgilleland.com/projects/quizzes/fetch.php",
	success: function(result) {
		alert("success!");
		//put render function here
		console.log(result);
	},
	data: "request=binary.json",
	crossDomain: true
});

var render = function(json) {
	//loop through each question
		//loop through each choice
}