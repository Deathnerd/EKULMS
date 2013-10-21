/*
 * Create.js
 * Author: Wes Gilleland, 2013
 * This will control the page to create quizzes
 */

 //Global to store the current div being hovered over
 var hover_id = '';
 var current_choice_count = 1;

 $('document').ready(function(){
	//generate a new section to enter a question
	var addChoice = function(question, prompt){
		var append_multichoice_prompt_data = '<br/><input type="text" name="">Enter choice</input><input type="button" value="+ choice" id="add_choice"></input>';
		$("#question_"+question).append(append_multichoice_prompt_data);
		prompt++;
	};

	//Monitor for hover events over divs with the .question class
	$('.question').hover(
		function(){ //Hover-in handler
			//console.log($(this).attr('id'));
			hover_id = $(this).attr('id');
	},
		function(){ //hover-out handler
			//console.log($(this).attr('id'));
			hover_id = "";
		});

	//append choices to the question
	$('.choice_add').click(function(){
		//console.log(this);
		current_choice_count++;
		$('#'+hover_id).append('<br/><label>Choice '+current_choice_count+'</label><input type="text" name="" value="Enter choice"></input>');
	});
});