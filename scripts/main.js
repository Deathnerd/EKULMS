//Client logic goes here
$(document).ready(function () {
	//set globals
	//construct the url to pass to the ajax function
	var site = function (file) {
		var url = "http://";
		var pathArray = window.location['href'].split('/');
		for (var i = 2; i < pathArray.length - 1; i++) {
			url = url + pathArray[i] + '/';
		}
		return url + file;
	};

	var holdingDiv = $('#holding_div');
	//fix up the selection box
	var option = $('option');
	var options = option.splice(0, option.length);
	//loop through each of the options and trim off the preceding directory name and following file extension
	$.each(options, function (index) {
		var word = options[index].value;
		var slashSplit = word.split('/');
		var dotSplit = slashSplit[1].split('.');
		options[index].text = dotSplit[0];
	});
	//function to render the questions
	var render = function (json) {
		console.log(json);
		if (holdingDiv.length !== 0) {
			holdingDiv.remove();
		}
		//create the holding div for the quiz
		$('body').append('<div id="holding_div"></div>');
		$('#holding_div').append('<p id="quiz_name">' + json._quizName + '</p>');
		var questions = json.quiz.questions; //loop through each question
		for (var question = 0; question < questions.length; question++) {
			$("#holding_div").append('<div class="question_body" id="question_' + question + '"></div>'); //create question_body div
			//create a new question div
			var question_body = $("#question_" + question);
			question_body.append("<p>" + questions[question].prompt + "</p>");
			//loop through each choice
			var choices = questions[question].choices;
			for (var choice = 0; choice < choices.length; choice++) {
				//append the choices inputs to the to the question
				if (choices[choice] === null) { //skip the null values #FIX IN CREATE/POST SCRIPTS
					continue;
				}
				if (choices[choice].correct === true) {
					question_body.append('<input name="question_' + question + '_choice" type="radio" onclick="answer_check(true, ' + question + ')">' + choices[choice].value + '</input>');
					question_body.append('<br />');
				}
				else {
					question_body.append('<input name="question_' + question + '_choice" type="radio" onclick="answer_check(false, ' + question + ')">' + choices[choice].value + '</input>');
					question_body.append('<br />');
				}
			}
			//append a correct/incorrect box to the end of the question. It's set to invisible
			//at first through the stylesheet
			question_body.append('<div class="correct_incorrect_box" id="box_' + question + '"></div>');
		}
	};
	//The function to render a test
	$('#load').click(function () {
		var value = $('select').val();
		$.ajax({
			url:         site('fetch.php'),
			success:     function (result) {
				render(result); //render the page using fetched JSON
			},
			data:        "data=" + value,
			crossDomain: true
		});
	});
	//handle login request
	$(document).on({
		click: function () {
			var userName = $('#userName').val();
			var password = $('[type=password]').val();
			var message = $('#message');
			$.ajax({
				url:         site('login.php'),
				success:     function (response) {
					console.log(typeof response);
					console.log(response);
					console.log(response === 'Success!');
					message.text(response);
					message.css('display', 'block');
					if (response === 'Success!') {
						setTimeout(function () {
							window.location = site('account.php');
						}, 2000);
					}
				},
				data:        "userName=" + userName + "&password=" + password,
				crossDomain: true
			});
		}
	}, '#loginButton');
	//handle signUp request
	$(document).on({
		click: function () {
			var userName = $('#userName').val();
			var password = $('[type=password]').val();
			var message = $('#message');
			$.ajax({
				url:         site('signupUser.php'),
				success:     function (response) {
					message.text(response);
					message.css('display', 'block');
					if (response === 'Success!') {
						setTimeout(function () {
							window.location = site('index.php');
						}, 2000);
					}
				},
				data:        "userName=" + userName + "&password=" + password,
				crossDomain: true
			});
		}
	}, '#signUpButton');
	//sign up for a course
	$(document).on({
		click: function () {
			var	userName = $("#addUserToCourse > #userName").val();
			var	courseId = $("#addUserToCourse > #courseId").val();
			var	message = $("message");
			//check for whitespace in the course id and username
			if (/\s/.test(courseId) || /\s/.test(userName)) {
				message.css("display", "block");
				message.text("Course id and Username may not contain spaces");
				return;
			}
			$.ajax({
				url:     site("courseSignup.php"),
				success: function (response) {
					var addUser = $('#addUserToCourse > div');
					addUser.text(response);
					addUser.css('display', 'block');
				},
//				data:    "action=addStudent&courseId=" + courseId + "&userName=" + userName
				data: {
				      action: 'addStudent',
				      courseId: courseId,
				      userName: userName
				}
			});
		}
	}, '#signupUser input[type="button"]');

	//user signup for course
	$(document).on({
		click: function(){
			var courseId = $("#signupCourse > #courseId").val();
			var message = $('#message');
			if(courseId === ''){
				message.css("display", "block").text("Please enter a course id");
				return;
			} else if (/\s/.test(courseId)) {
				message.css("display", "block").text("Course Id may not contain whitespaces");
				return;
			}
			$.ajax({
				url: site("courseSignup.php"),
				success: function(response){
					message.css('display', 'block').text(response);
				},
				data: "action=addStudent&courseId="+courseId
			});
		}
	}, '#signupCourse > input[type="button"]');

	//list user's courses
	$(document).on({
		click: function(){
			var table = $('#listStudentCourses > table');
			$.ajax({
				url: site('listStudentCourses.php'),
				success: function(results){
					console.log(results);
					console.log(typeof results);
					if(typeof results != 'object'){
						alert("UH OH!");
						return;
					}
					$(table).css('display', 'block');

					for(var i = 0; i < results.length; i++){
						var courseName = results[i].courseName;
						var courseId = results[i].courseId;
						var description = results[i].description;
						//append to table
						table.append("<tr id='course_row'>"+
										"<td>"+courseId+"</td>"+
										"<td>"+courseName+"</td>"+
										"<td>"+description+"</td>"+
									"</tr>");
					}
				}
			})
		}
	},'#listStudentCourses > input[type="button"]');


});
//checks if the clicked radial was the correct answer
var answer_check = function (correct, number) {
	if (correct) {
		$("#box_" + number).text("Correct!").css("background-color", "rgba(0, 255, 0, .5)").css("border", "solid 1px rgba(0, 255, 0, .75)").css('display', 'block');
	}
	else {
		$("#box_" + number).text("Incorrect").css("background-color", "rgba(255, 0, 0, .5)").css("border", "solid 1px rgba(255, 0, 0, .75)").css("display", "block");
	}
};