/*
 * Create.js
 * Author: Wes Gilleland, 2013
 * This will control the page to create quizzes
 */

/*TODO
 */

//Globals
var hover_id = ''; //store the current div being hovered over
var current_choice_count = 1; //how many choices are in the current, working question?
var current_question_count = 1; //how many questions have we created?
//construct the url string for the ajax request
var site = function (file) {
	url = "http://";
	pathArray = window.location['href'].split('/');
	for (var i = 2; i < pathArray.length - 1; i++) {
		url = url + pathArray[i] + '/';
	}
	return url + file;
}
//ready the JSON template
var jsonReady = function () {
	json = new Object();
	json["_quizName"] = "blank";
	json["quiz"] = new Object();
	json.quiz["questions"] = new Array();
	return json;
}
//remove those pesky nulls in the JSON
var removeNull = function (json) {
	questions = json.quiz.questions;
	for (var i = 0; i < questions.length; i++) {
		questions[i].choices.slice(1); //remove that pesky null at the beginning of the choices array
	}
	return json;
}
$(document).ready(function () {

	//fix up the selection box
	options = $('option').splice(0, $('option').length);
	//loop through each of the options and trim off the preceeding directory name and following file extension
	$.each(options, function (index) {
		word = options[index].value;
		slashSplit = word.split('/');
		dotSplit = slashSplit[1].split('.');
		name = dotSplit[0];
		options[index].text = name;
	});
	//ready the JSON template
	var json = jsonReady();
	//append choices to the question
	$(document).on({
		click: function () {
			current_choice_count++;
			//html for a new choice. Broken up for readability
			// html = 	'<label id="choice_'+current_choice_count+'">Choice '+current_choice_count+'</label>'+
			// 		'<input type="text" class="choice" id="choice_'+current_choice_count+'" value="Enter choice"></input>'+
			// 		'<input type="button" class="remove_inline_choice" id="'+current_choice_count+'" value="X"></input><br>';
			html = '<tr><td width="30"><div align="center" id="choiceLabel">' + current_choice_count + '</div></td>' +
				'<td width="323"><input type="text" class="choice" value="Enter choice"></td>' +
				'<td width="58"><div align="center"><input name="" type="checkbox" class="correctBox" value=""></div></td>' +
				'<td><input type="button" value="Remove Choice" class="remove_inline_choice" id="' + current_choice_count + '"></td></tr>';
			$(html).appendTo('#' + hover_id + ' > table');
		}
	}, '.choice_add');
	//remove the last choice
	$(document).on({
		click: function () {
			question = $('#' + hover_id);
			//find the number of current <tr> elements in the question div and remove the last one
			rows = question.find('tr');
			if (rows.length > 2) {	//if there's only one choice in the question, then we don't need to remove it
				rows[rows.length - 1].remove();
				current_choice_count--;
			}
		}
	}, '.choice_remove');
	//remove the inline choice
	$(document).on({
		click: function () {
			$(this).closest('tr').remove();	//find the closest <tr> element and remove it
			//re-number the labels
			labels = $('#' + hover_id + ' td > #choiceLabel'); //find only the labels in the current question
			val = 2; //we start numbering at 2 since we skip the first choice
			for (var x = 0; x < labels.length; x++) {
				$(labels[x]).text(val);
				val++;
			}
		}
	}, '.remove_inline_choice');
	//remove a question from the current div
	$(document).on({
		click: function () {
			$('#question_' + current_question_count).remove();
			current_question_count--;
		}
	}, '.question_remove');
	//set the hover_id to the current question being modified. Clear the hover_id when we're not in the div
	$(document).on({
		mouseenter: function () {
			hover_id = $(this).attr('id');
			current_choice_count = $(this).find('.choice').length; //find the current number of choices in the selected div
		},
		mouseleave: function () {
			hover_id = '';
		}
	}, '.question');
	//generate a new question div
	$(document).on({
		click: function () {
			current_question_count++;
			//html for a new question. Broken up for readability
			html = '<div class="question" id="question_' + current_question_count + '">' +
				'<p class="question_label">Question ' + current_question_count + '</p><br>' +
				'<input type="button" class="remove_inline_question" value="Remove this question"></input><br>' +
				'<textarea name="prompt" cols="40" rows="5">Input some text here</textarea><br>' +
				'<input type="button" value="Add Choice" id="add_choice" class="choice_add"></input>' +
				'<input type="button" value="Remove Last Choice" id="remove_choice" class="choice_remove"></input><br>' +
				'<table width="430" border="0" cellspacing="0" cellpadding="0">' +
				'<tr>' +
				'<th width="30" scope="col">#</th>' +
				'<th width="323" scope="col">Value</th>' +
				'<th width="58" scope="col">Correct?</th>' +
				'</tr>' +
				'<tr>' +
				'<td width="30"><div align="center">1</div></td>' +
				'<td width="323"><input type="text" class="choice" value="Enter choice"></td>' +
				'<td width="58"><div align="center">' +
				'<input name="" type="checkbox" class="correctBox" value="">' +
				'</div></td>' +
				'</tr>' +
				'</table>' +
				'</div>';
			$('body').append(html);
		}
	}, '.question_add');
	//remove an inline question
	$(document).on({
		click: function () {
			$('#' + hover_id).remove(); //remove the currently hovered over div
			//reorder the divs
			divs = $(document).find('.question');
			$.each(divs, function (index) {
				num = index + 1;
				$(divs[index]).attr('id', 'question_' + num);
				$(divs[index]).find('.question_label').text("Question " + num);
				// $(divs[index]+' > .question_label').text("Question "+num);
			});
			//relabel the questions
			// labels = $(document).find('.question_label');
			// $.each(labels, function (index){
			// 	num = index+1;
			// 	$(labels[index]).text("Question "+num);
			// });
			current_question_count = $('.question').length;
		}
	}, '.remove_inline_question');
	//function to construct the JSON to send to the server
	//structure: ("Key" --Type)
	//--Object
	//	"_quizName": "Value"
	//		"quiz:" --Object
	//			"questions" --Array
	//				--Object
	//					"prompt": "Value", 
	//					"choices": --Array
	//						--Object
	//							"text": "value",
	//							"correct": true/false
	//construct the json from the input fields (works first time! yay!)
	$(document).on({
		click: function () {
			json._quizName = $('#quizName').val(); //grab the quiz name
			questions = $('.question'); //find all the questions
			for (var i = 0; i < questions.length; i++) { //for all the questions in the page
				json.quiz.questions[i] = new Object(); //create a new object to contain the choices
				json.quiz.questions[i].prompt = $(questions[i]).find('textarea').val(); //there's only one textarea
				json.quiz.questions[i].choices = new Array();//create a new array for choices
				choices = $(questions[i]).find('tr'); //find all the choices within the question
				for (var j = 1; j < choices.length; j++) { //loop through the question's choices; start at 1 to skip the header
					json.quiz.questions[i].choices[j] = new Object(); //
					json.quiz.questions[i].choices[j].value = $(choices[j]).find('[type=text]').val(); //fetch the value of the choice
					if ($(choices[j]).find('[type=checkbox]:checked').length > 0) { //if the checkbox is checked set the json property to true
						json.quiz.questions[i].choices[j].correct = true;
					}
					else {
						json.quiz.questions[i].choices[j].correct = false;
					}
				}
			}
			console.log(json);
		}
	}, '#constructJSON')
	$(document).on({
		click: function () {
			$.ajax({
				dataType:    "json",
				url:         site("post.php"),
				success:     function (data) {
					if (data != "Request empty") {
						alert("Quiz saved");
					}
					else {
						alert("Quiz not saved with error" + data);
					}
					console.log(data);
				},
				data:        'data=' + JSON.stringify(json),
				crossDomain: true
			});
		}
	}, '#sendJSON');
	//function to populate the page from an existing quiz
	populate = function (json) {
		$.each($(document).find('.question'), function () { $(this).remove(); });//this will delete all questions on the page
		//Make the questions
		console.log('Making questions');
		$('#quizName').attr('value', json._quizName);
		//make the first question without the inline removal button
		html = '<div class="question" id="question_1">' +
			'<p class="question_label">Question 1</p><br>' +
			'<textarea name="prompt" cols="40" rows="5">Input some text here</textarea><br>' +
			'<input type="button" value="Add Choice" id="add_choice" class="choice_add"></input>' +
			'<input type="button" value="Remove Last Choice" id="remove_choice" class="choice_remove"></input><br>' +
			'<table width="430" border="0" cellspacing="0" cellpadding="0">' +
			'<tr>' +
			'<th width="30" scope="col">#</th>' +
			'<th width="323" scope="col">Value</th>' +
			'<th width="58" scope="col">Correct?</th>' +
			'</tr>' +
			'</table>' +
			'</div>';
		$('body').append(html);
		i = 2;
		while (i <= json.quiz.questions.length) {
			html = '<div class="question" id="question_' + i + '">' +
				'<p class="question_label">Question ' + i + '</p><br>' +
				'<input type="button" class="remove_inline_question" value="Remove this question"></input><br>' +
				'<textarea name="prompt" cols="40" rows="5">Input some text here</textarea><br>' +
				'<input type="button" value="Add Choice" id="add_choice" class="choice_add"></input>' +
				'<input type="button" value="Remove Last Choice" id="remove_choice" class="choice_remove"></input><br>' +
				'<table width="430" border="0" cellspacing="0" cellpadding="0">' +
				'<tr>' +
				'<th width="30" scope="col">#</th>' +
				'<th width="323" scope="col">Value</th>' +
				'<th width="58" scope="col">Correct?</th>' +
				'</tr>' +
				'</table>' +
				'</div>';
			$('body').append(html);
			i++;
			current_question_count++;
		}
		;
		console.log("Questions complete");
		//make the choices
		console.log("Making choices");
		$.each($(document).find('.question'), function (index) { //loop through the questions
			question = json.quiz.questions[index];
			$(this).children('textarea').val(question.prompt); //fill in the prompt
			//add choices in this question's choice table
			c = 1;
			for (var i = 0; i < question.choices.length; i++) {
				//append the html for the row
				if (question.choices[i] == null) {
					continue;
				}
				html = '<tr>' +
					'<td width="30"><div align="center">' + c + '</div></td>' +
					'<td width="323"><input type="text" class="choice" value="Enter choice"></td>' +
					'<td width="58"><div align="center">' +
					'<input name="" type="checkbox" class="correctBox" value="">' +
					'</div></td>' +
					'</tr>';
				$(this).children('table').append(html);
				//fill in the values
				console.log("Filling in Values");
				tr = $(this).find('tr'); //find all the current table rows
				td = $(tr[c]).find('td');
				$(td[0]).val(c); //fill in the question # label
				$(tr[c]).find('.choice').attr('value', question.choices[i].value);//change the value of the input box of the current choice
				$(tr[c]).find('input[type=checkbox]').prop('checked', question.choices[i].correct);//change the checkbox
				c++;
			}
			;
		});
	};
	$(document).on({
		click: function () {
			value = $('select').val();
			$.ajax({
				dataType:    'json',
				url:         site("fetch.php"),
				success:     function (data) {
					console.log(data);
					data = removeNull(data);
					console.log(data);
					populate(data);
				},
				data:        'data=' + value,
				crossDomain: true
			});
		}
	}, '#load');
});