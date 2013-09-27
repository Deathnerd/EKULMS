//Client logic goes here
$('document').ready(function(){
	//function to render the questions
	var render = function(json) {
		//create the holding div for the form
		$('body').append('<div id="holding_div"></div>')
		$('#holding_div').append('<p id="quiz_name">'+json._quizName+'</p>');

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
				render(result); //render the page using fetched JSON
				console.log(result);
			},
			data: "request=binary.json",
			crossDomain: true
		});
	}

	$('#fetch').click(function(){
		request();
	});
});