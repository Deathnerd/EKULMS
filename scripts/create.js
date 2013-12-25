/*
 * Create.js
 * Author: Wes Gilleland, 2013
 * This will control the page to create quizzes
 */

/*TODO
 * -Add radial button to the side of a question to indicate a correct answer
 * -Add functionality to the "X" buttons
 * -Oh boy... Work on function to generate json string from all the forms. Oh boy...
 * -Clean all this up from hack code to respectable code
 */

 //Globals
 var hover_id = ''; //store the current div being hovered over
 var current_choice_count = 1; //how many choices are in the current, working question?
 var current_question_count = 1; //how many questions have we created?

 var jsonReady = function(){ //ready the JSON template
 	json = new Object();
 	json["_quizName"] = "blank";
 	json["quiz"] = new Object();
 	json.quiz["questions"] = new Array();

 	return json;
 }

 $(document).ready(function(){

 	//ready the JSON template
 	var json = jsonReady();

	//append choices to the question
	$(document).on({
		click: function(){
			current_choice_count++;
			//html for a new choice. Broken up for readability
			// html = 	'<label id="choice_'+current_choice_count+'">Choice '+current_choice_count+'</label>'+
			// 		'<input type="text" class="choice" id="choice_'+current_choice_count+'" value="Enter choice"></input>'+
			// 		'<input type="button" class="remove_inline_choice" id="'+current_choice_count+'" value="X"></input><br>';
			html = 	'<tr><td width="30"><div align="center">'+current_choice_count+'</div></td>'+
					'<td width="323"><input type="text" class="choice" value="Enter choice"></td>'+
					'<td width="58"><div align="center"><input name="" type="checkbox" class="correctBox" value=""></div></td>'+
					'<td><input type="button" value="Remove Choice" class="remove_inline_choice" id="'+current_choice_count+'"></td></tr>';
			$(html).appendTo('#'+hover_id+' > table');
		}
	}, '.choice_add');

	//remove the last choice
	$(document).on({
		click: function(){
			question = $('#'+hover_id);
			//find the number of current <tr> elements in the question div and remove the last one
			rows = question.find('tr');
			if (rows.length > 2){	//if there's only one choice in the question, then we don't need to remove it
				rows[rows.length-1].remove();
			}
		}
	}, '.choice_remove');

	//remove the inline choice
	$(document).on({
		click: function(){	
			//get the number of the button and the associated choice input
			number = parseInt($(this).attr('id'));
			//remove the break after the button (there are three before we get to the choices)
			breaks = $('#'+hover_id).find('br');
			console.log("Length of breaks[]: "+breaks.length);
			console.log("Breaks.length-3: "+breaks.length-3);
			console.log("Number+3: "+number+3);
			$(breaks[number+3]).remove();
			//remove the choice and the button
			$('#choice_'+number).remove();
			$('#choice_'+number).remove();
			$(this).remove();
		}
	}, '.remove_inline_choice');

	//remove a question from the current div
	$(document).on({
		click: function(){
			$('#question_'+current_question_count).remove();
			current_question_count--;
		}
	}, '.question_remove');

	//set the hover_id to the current question being modified. Clear the hover_id when we're not in the div
	$(document).on({
		mouseenter: function(){
			hover_id = $(this).attr('id');
			current_choice_count = $(this).find('.choice').length; //find the current number of choices in the selected div
		},
		mouseleave: function(){
			hover_id = '';
		}
	}, '.question');

	//generate a new question div
	$(document).on({
		click: function(){
			current_question_count++;
			//html for a new question. Broken up for readability
			html = 	'<div class="question" id="question_'+current_question_count+'">'+
						'<p class="question_label">Question '+current_question_count+'</p><br>'+
						'<input type="button" class="remove_inline_question" value="Remove this question"></input><br>'+
						'<textarea name="prompt"+prompt cols="40" rows="5">Input some text here</textarea><br>'+
						'<input type="button" value="Add Choice" id="add_choice" class="choice_add"></input>'+
						'<input type="button" value="Remove Last Choice" id="remove_choice" class="choice_remove"></input><br>'+
						'<table width="430" border="0" cellspacing="0" cellpadding="0">'+
						'<tr>'+
						'<th width="30" scope="col">#</th>'+
						'<th width="323" scope="col">Value</th>'+
						'<th width="58" scope="col">Correct?</th>'+
						'</tr>'+
						'<tr>'+
						'<td width="30"><div align="center">1</div></td>'+
						'<td width="323"><input type="text" class="choice" value="Enter choice"></td>'+
						'<td width="58"><div align="center">'+
						'<input name="" type="checkbox" class="correctBox" value="">'+
						'</div></td>'+
						'</tr>'+
						'</table>'+
					'</div>';
			$('body').append(html);
		}
	}, '.question_add');

	//remove an inline question
	$(document).on({
		click: function(){
			$('#'+hover_id).remove(); //remove the currently hovered over div
			//reorder the divs
			divs = $(document).find('.question');
			$.each(divs, function (index){
				num = index+1;
				$(divs[index]).attr('id', 'question_'+num);
				$(divs[index]).find('.question_label').text("Question "+num);
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

	$(document).on({
		click: function(){
			var questions = $(document).find('.question');
			var choices = $()
			$.each(questions, function (index){

				//this block will create the questions objects
				//and the prompt and choices keys along with their values
				json.quiz.questions[index] = new Object();
				json.quiz.questions[index]["prompt"] = "";
				json.quiz.questions[index]["choices"] = new Array();

				//loop and insert all choices and their values into the choices array
			});

		}
	}, '#constructJSON');
	//Psuedocode:	
});