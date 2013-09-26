//Client logic goes here

//function to render the questions
var render = function(json) {
	//create the holding div for the form
	$('body').append('<div id="holding_div"></div>')

	//loop through each question
	var questions = json.quiz.questions;
	for (var question in questions)
	{
		
		console.log(questions[question].prompt);
		//create new question div
		$("#holding_div").append('<div class="question_body" id="question_'+question+'"></div>');
		var question_body = $("#question_"+question);
		question_body.append("<p>"+questions[question].prompt+"</p>");

		//loop through each choice
		var choices = questions[question];
		for (var choice in choices){
			console.log(choices[choice].value);
			console.log(choices[choice].correct);

			//append the choices to the question

		}
	}
}

//the request
var request = function(){
	$.ajax({
		url: "http://www.wesgilleland.com/projects/quizzes/fetch.php",
		success: function(result) {
			alert("success!");
			render(result);
			console.log(result);
		},
		data: "request=binary.json",
		crossDomain: true
	});
}