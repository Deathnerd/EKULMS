//Client logic goes here
$('document').ready(function(){
	//set globals

	//construct the url to pass to the ajax function
	var site = function(filename){
	 	url = "http://";
	 	pathArray = window.location['href'].split('/');
	 	for(var i = 2; i < pathArray.length-1; i++){
	 		path = path+pathArray[i]+'/';
	 	}
	 	url = url+filename;
	 	return url;
	 }

	var file = "binary.json";
	//function to render the questions
	var render = function(json) {
		console.log(json);
		//create the holding div for the quiz
		$('body').append('<div id="holding_div"></div>')
		$('#holding_div').append('<p id="quiz_name">'+json._quizName+'</p>');
		var questions = json.quiz.questions; //loop through each question

		for (var question = 0; question < questions.length; question++){			
			$("#holding_div").append('<div class="question_body" id="question_'+question+'"></div>'); //create question_body div

			//create a new question div
			var question_body = $("#question_"+question);
			question_body.append("<p>"+questions[question].prompt+"</p>");

			//loop through each choice
			var choices = questions[question].choices;
			for (var choice = 0; choice < choices.length; choice++){
				//append the choices inputs to the to the question
				if (choices[choice]==null){ //skip the null values #FIX IN CREATE/POST SCRIPTS
					continue;
				}
				if (choices[choice].correct == true){
					question_body.append('<input name="question_'+question+'_choice" type="radio" onclick="answer_check(true, '+question+')">'+choices[choice].text+'</input>');
					question_body.append('<br />');
				}
				else{
					question_body.append('<input name="question_'+question+'_choice" type="radio" onclick="answer_check(false, '+question+')">'+choices[choice].text+'</input>');	
					question_body.append('<br />');
				}
			}

			//append a correct/incorrect box to the end of the question. It's set to invisible 
			//at first through the stylesheet
			$("#holding_div").append('<div class="correct_incorrect_box" id="box_'+question+'"></div>');
		}
	}

	//the request
	var request = function(){
		$.ajax({
			url: "http://www.wesgilleland.com/projects/quizzes/fetch.php",
			success: function(result) {
				render(result); //render the page using fetched JSON
			},
			data: "request="+file,
			crossDomain: true
		});
	}

	$('#fetch').click(function(){
		request();
	});
});