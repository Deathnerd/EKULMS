//Client logic goes here

//construct the url to pass to the ajax function

$(document).ready(function () {
	//set globals
	var courseId = "";
	var quizName = "";

	var site = function (file) {
		var url = "http://";
		var pathArray = window.location['href'].split('/');
		for (var i = 2; i < pathArray.length - 1; i++) {
			url = url + pathArray[i] + '/';
		}
		return url + file;
	};

	function reporting_selection_actions() {
		var selection = $("#report_course").val();
		selection = selection.split(" -- ");
		var courseId = selection[0];
		var courseName = selection[1];

		$.ajax({
			url: site("populateTestsDropdownForReporting.php"),
			data: {
				courseId: courseId,
				courseName: courseName
			},
			success: function (response) {
				var testsDropdown = $('#reporting_selection').find("#tests");
				var fetchByTest = $('#fetch_by_test');
				try {
					var responseJSON = JSON.parse(response);
				} catch (e) {
					if (response == "Error getting tests") {
						$(testsDropdown).empty().append("<option>No tests for this course</option>");
						$(fetchByTest).prop('disabled', true);
						return;
					}
					$(testsDropdown).empty().hide();
					alert("Server responded with an error: " + e);
					console.log(response);
					return;
				}
				/*
				 * Success! Let's populate the dropdown
				 */

				$(testsDropdown).empty().show();
				$(fetchByTest).prop('disabled', false);
				for (var i = 0; i < responseJSON.length; i++) {
					$(testsDropdown).append("<option>" + responseJSON[i]['testName'] + "</option>");
				}
			}
		});
	}


	//function to render the questions
	var render = function (json) {
		console.log(json);
		json = JSON.parse(json);
		_courseId = json.courseId;
		$('#holding_div').empty(); //clear out the holding div
		//create the holding div for the quiz
		var body = $('body');
		body.append('<div id="holding_div"></div>');
		$('#holding_div').append('<p id="quiz_name">' + json._quizName + '</p>');
		var questions = json.quiz.questions; //loop through each question
		for (var question = 0; question < questions.length; question++) {
			$('#holding_div').append('<div class="question_body" id="question_' + question + '"></div>'); //create question_body div
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
					question_body.append('<input name="question_' + question + '_choice" type="radio" onclick="answer_check(true, ' + question + ')">' + choices[choice].value + '</input><br />');
				}
				else {
					question_body.append('<input name="question_' + question + '_choice" type="radio" onclick="answer_check(false, ' + question + ')">' + choices[choice].value + '</input><br />');
				}
			}
			//append a correct/incorrect box to the end of the question. It's set to invisible
			//at first through the stylesheet
			question_body.append('<div class="correct_incorrect_box" id="box_' + question + '"></div>');
		}
		$('#holding_div').append('<input type="button" value="Submit Test" id="submit_test">');
	};
	//The function to render a test
	$('#load').click(function () {
		var value = $('select').val();
		$.ajax({
			url: site('fetch.php'),
			success: function (result) {
				render(result); //render the page using fetched JSON
			},
			data: {
				data: value
			},
			crossDomain: true
		});
	});
	//handle login request
	$(document).on({
		click: function () {
			var userName = $('#userName').val();
			var password = $('input[type=password]').val();
			var message = $('#message');
			$.ajax({
				url: site('login.php'),
				success: function (response) {
					message.text(response).css('display', 'block');
					if (response.indexOf('Success') != -1) {
						setTimeout(function () {
							window.location = site('account.php');
						}, 2000);
					}
				},
				data: {
					userName: userName,
					password: password
				},
				crossDomain: true
			});
		}
	}, '#loginButton');
	//handle signUp request
	$(document).on({
		click: function () {
			var userName = $('#userName').val();
			var password = $('input[type="password"]').val();
			var message = $('#message');
			var email = $('#email').val();
			$.ajax({
				url: site('signupUser.php'),
				success: function (response) {
					message.text(response);
					message.css('display', 'block');
					if (response === 'logged_in') {
						window.location = site('index.php');
					}
					if (response === 'Success!') {
						setTimeout(function () {
							window.location = site('index.php');
						}, 2000);
					}
				},
				data: {
					userName: userName,
					password: password,
					email: email
				},
				crossDomain: true
			});
		}
	}, '#signUpButton');
	//sign up for a course
	$(document).on({
		click: function () {
			var addUserToCourse = $("#addUserToCourse");
			var userName = addUserToCourse.find('#userName').val();
			var courseId = addUserToCourse.find('#courseId').val();
			var message = $("message");
			//check for whitespace in the course id and username
			if (/\s/.test(courseId) || /\s/.test(userName)) {
				message.css("display", "block").text("Course id and Username may not contain spaces");
				return;
			}
			$.ajax({
				url: site("courseSignup.php"),
				success: function (response) {
					var addUser = $('#addUserToCourse > div');
					addUser.css('display', 'block').text(response);
				},
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
		click: function () {
			var courseId = $('#courseSignupDropdown').val();
			var message = $('#message');
			if (courseId === '') {
				message.css("display", "block").text("Please enter a course id");
				return;
			} else if (/\s/.test(courseId)) {
				message.css("display", "block").text("Course Id may not contain whitespaces");
				return;
			}
			$.ajax({
				url: site("courseSignup.php"),
				success: function (response) {
					message.css('display', 'block').text(response);
				},
				data: {
					action: 'addStudent',
					courseId: courseId
				}
			});
		}
	}, '#signupCourse > #addUser');
	//list user's courses
	$(document).on({
		click: function () {
			var table = $('#listStudentCourses > table');
			$.ajax({
				url: site('listStudentCourses.php'),
				success: function (results) {
					console.log(results);
					console.log(typeof results);
					if (typeof results != 'object') {
						alert("UH OH!");
						return;
					}
					$(table).css('display', 'block');
					for (var i = 0; i < results.length; i++) {
						var courseName = results[i].courseName;
						var courseId = results[i].courseId;
						var description = results[i].description;
						//append to table
						table.append("<tr id='course_row'>" +
						"<td>" + courseId + "</td>" +
						"<td>" + courseName + "</td>" +
						"<td>" + description + "</td>" +
						"</tr>");
					}
				}
			})
		}
	}, '#listStudentCourses > input[type="button"]');
	/*$('#getStats').click(function () {
	 var courses = $('#courses');
	 var testName = courses.val();
	 var courseSplit = courses.

	 $.ajax({
	 url: site('reports.php'),
	 data: {
	 action: 'all',


	 }
	 })
	 });*/

	$('#courses').change(function () {
			var testsDropdown = $('#tests');
			// ajax to get tests by course id
			testsDropdown.show();

		}
	);
	$(document).on({
		click: function () {
			if ($('input:checked').length < $('.question_body').length) {
				alert("Please make sure you have answered every question before submitting");
				return;
			}
			$.ajax({
				url: site('submit.php'),
				data: {
					payload: JSON.stringify(populateSubmit()),
					action: 'submit'
				},
				success: function (results) {
					console.log(results);
					if (results.indexOf("error") == -1) {
						$('#holding_div').replaceWith(results);
					} else {
						alert(results);
					}
				}
			})
		}
	}, '#submit_test');
	var populateSubmit = function () {
		var json = {
			_testName: $('select').val(),
			_courseId: courseId,
			answers: []
		};
		var questions = $('.question_body'); //get all the questions
		var selectionLetters = ['A', 'B', 'C', 'D'];
		for (var i = 0; i < questions.length; i++) {
			json.answers[i] = {
				text: "",
				correct: false,
				selectionLetter: ""
			};
			var question = questions[i]; //current question
			json.answers[i].text = $(question).find('p').text(); //get the question text
			var choices = $(question).find('input'); //get the choices
			for (var j = 0; j < choices.length; j++) {
				if ($(choices[j]).is(':checked')) {
					json.answers[i].selectionLetter = selectionLetters[j]; //what option did they select?
					//is it correct or no?
					json.answers[i].correct = $(choices[j]).attr('onclick').search('true') != -1;
				}
			}
		}
		return json;
	};
	$("#resetButton").click(function () {
		var email = $('#email').val();
		var action = "send_email";
		$.ajax({
			url: site('reset.php'),
			data: {
				email: email,
				action: action
			},
			success: function (results) {
				var message = $('#message');
				if (results !== "Success") {
					message.text(results);
					return;
				}
				message.text("An email has been sent to " + email + " with further instructions");
			}
		})
	});

	/**
	 * Handle the account fetching of the tests
	 */
	$('#reporting_selection').find('#report_course').change(function () {
		reporting_selection_actions();
	});

	/**
	 * TODO: Make another function to handle rendering and fetching the results to the account page
	 */

	$('#fetch_by_test').click(function () {
		var testName = $('#reporting_selection').find('#tests').val();
		$.ajax({
			url: site("get_test_report.php"),
			data: {
				testName: testName
			},
			success: function (response) {
				var table = $('#statsResultsTable');
				table.empty();
				console.log(response);
				try {
					var responseJSON = JSON.parse(response);
				} catch (e) {
					if (response == "No results found") {
						alert("It appears you have not taken this test");
						return;
					}
					return;
				}

				table.append("<tr>" +
				"<th class='small_padding'>Attempt</th>" +
				"<th class='small_padding'>Grade</th>" +
				"<th class='small_padding'>Number Correct</th>" +
				"<th class='small_padding'>Number Incorrect</th>" +
				"<th class='small_padding'>Percentage</th>" +
				"<th class='small_padding'>Date submitted</th>" +
				"<th class='small_padding'>Time submitted</th>" +
				"</tr>");
				for (var i = 0; i < responseJSON.length; i++) {
					var attempt = responseJSON[i]['attempt'];
					var grade = responseJSON[i]['grade'];
					var submitted = responseJSON[i]['submitted'];
					var num_correct = responseJSON[i]['number_correct'];
					var num_incorrect = responseJSON[i]['number_incorrect'];
					var percentage = responseJSON[i]['percentage'];
					var date_submitted = submitted.split(' ')[0];
					var time_submitted = submitted.split(' ')[1];

					// Split timestamp into [ Y, M, D, h, m, s ]
					var t = submitted.split(/[- :]/);

					// Apply each element to the Date function
					var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
					console.log(d);

					table.append("<tr>" +
					"<td>" + attempt + "</td>" +
					"<td>" + grade + "</td>" +
					"<td>" + num_correct + "</td>" +
					"<td>" + num_incorrect + "</td>" +
					"<td>" + numeral(percentage).format("0.00%") + "</td>" +
					"<td>" + date_submitted + "</td>" +
					"<td>" + time_submitted + "</td>" +
					"</tr>");

				}
				$('#statsResults').show();
			}
		})
	});

	if (location.pathname == "/account.php") {
		console.log("doing stuff");
		reporting_selection_actions();
	}
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