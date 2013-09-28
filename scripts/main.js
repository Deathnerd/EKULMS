//Client logic goes here
$('document').ready(function()
{
	//checks if the clicked radial was the correct answer
	var answer_check = function(correct, number)
	{
		if (correct)
		{
			$("#box_"+number).text("Correct!");
			$("#box_"+number).css("background-color", "rgba(0, 255, 0, .5)");
			$("#box_"+number).css("border", "solid 1px rgba(0, 255, 0, .75) ");
		}
		else 
		{
			$("#box_"+number).text("Incorrect");
			$("#box_"+number).css("background-color", "rgba(255, 0, 0, .5)");
			$("#box_"+number).css("border", "solid 1px rgba(255, 0, 0, .75) ");
		}
	}

	//function to render the questions
	var render = function(json) 
	{

		//create the holding div for the quiz
		$('body').append('<div id="holding_div"></div>')
		$('#holding_div').append('<p id="quiz_name">'+json._quizName+'</p>');

		//loop through each question
		var questions = json.quiz.questions;

		for (var question = 0; question < questions.length; question++)
		{
			
			console.log("Prompt: "+questions[question].prompt);

			//create question_body div
			$("#holding_div").append('<div class="question_body" id="question_'+question+'"></div>');

			//create a new question div
			var question_body = $("#question_"+question);
			question_body.append("<p>"+questions[question].prompt+"</p>");

			//loop through each choice
			var choices = questions[question].choices;
			console.log("Choices: "+choices);

			//BUG: Loop not running at all! WHY?!?!
			for (var choice = 0; choice < choices.length; choice++)
			{
				console.log("Choice #"+choice+": "+choices[choice]); //BUG: returning the question text. Should return the choice object
				console.log("Choice Text: "+choices[choice].text); //BUG: returning undefined. Should return the text of the choice
				console.log("Choice Value: "+choices[choice].correct); ///BUG: returning undefined. Should return either true or false

				//append the choices inputs to the to the question
				//BUG: Both returning undefined. Should return their respective values
				if (choices[choice].correct == true)
				{
					console.log("Printing true statment for choice "+choice);
					question_body.append('<input type="radio" onclick= answer_check(true, '+question+')>'+choices[choice].text+'</input>');
					question_body.append('<br />');
				}
				else 
				{
					console.log("Printing fase statment for choice "+choice);
					question_body.append('<input type="radio" onclick= answer_check(false, '+question+')>'+choices[choice].text+'</input>');	
					question_body.append('<br />');
				}

			}

			//append a correct/incorrect box to the end of the question. It's set to invisible 
			//at first through the stylesheet
			$("#holding_div").append('<div class="correct_incorrect_box" id="box_'+question+'"></div>');
		}
	}

	//the request
	var request = function()
	{
		$.ajax(
		{
			url: "http://www.wesgilleland.com/projects/quizzes/fetch.php",
			success: function(result) {
				render(result); //render the page using fetched JSON
				console.log(result);
			},
			data: "request=binary.json",
			crossDomain: true
		});
	}

	$('#fetch').click(function()
	{
		request();
	});
});