/*
 * Create.js
 * Author: Wes Gilleland, 2013
 * This will control the page to create quizzes
 */

 //Globals
 var hover_id = ''; //store the current div being hovered over
 var current_choice_count = 1; //how many choices are in the current, working question?
 var current_question_count = 1; //how many questions have we created?

 $('document').ready(function(){
	//generate a new section to enter a question
	var addChoice = function(question, prompt){
		var prompt_data = '<br/><input type="text" name="">Enter choice</input><input type="button" value="+ choice" id="add_choice"></input>';
		$("#question_"+question).append(prompt_data);
		prompt++;
	};

	//Monitor for hover events over divs with the .question class
	$('.question').hover(
		function(){ //Hover-in handler
			console.log($(this).attr('id'));
			hover_id = $(this).attr('id');
	},
		function(){ //hover-out handler
			console.log($(this).attr('id'));
			hover_id = "";
		});

	//append choices to the question
	$('.choice_add').click(function(){
		//console.log(this);
		current_choice_count++;
		html = '<br/><label>Choice '+current_choice_count+'</label><input type="text" name="" value="Enter choice"></input>';
		$('#'+hover_id).append(html);
	});

	//generate a new question
	$('.question_add').click(function(){
		current_choice_count = 0;
		current_question_count++;
		html = '<div class="question" id="question_'+current_question_count+'"><p class="question_label">Question '+current_question_count+'</p><br/><textarea name="prompt"+prompt cols="40" rows="5">Input some text here</textarea><br/><label>Choice 1</label><input type="text" name="" value="Enter choice"></input><input type="button" value="+" id="add_choice" class="choice_add"></input></div>';
		$('body').append(html);
	});
});