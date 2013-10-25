/*
 * Create.js
 * Author: Wes Gilleland, 2013
 * This will control the page to create quizzes
 */

/*TODO
 * -Fix event handlers to bind to dynamically generated elements (jquery.on() method needed)
 * -Add radial button to the side of a question to indicate a correct answer
 * -Oh boy... Work on function to generate json string from all the forms. Oh boy...
 */

 //Globals
 var hover_id = ''; //store the current div being hovered over
 var current_choice_count = 1; //how many choices are in the current, working question?
 var current_question_count = 1; //how many questions have we created?

 $('document').ready(function(){
	//generate a new section to enter a question
	var addChoice = function(question, prompt){
		var prompt_data = '<input type="text" name="">Enter choice</input><input type="button" value="+" id="add_choice"></input><br>';
		$("#question_"+question).append(prompt_data);
		prompt++;
	};

	//Monitor for hover events over divs with the .question class
	$('.question').hover(
		function(){ //Hover-in handler
			hover_id = $(this).attr('id');
			current_choice_count = $('#'+hover_id+' > input[type="text"]').length; //get the number of choice input elements
			console.log($(this).attr('id'));
	},
		function(){ //hover-out handler
			console.log($(this).attr('id'));
			hover_id = "";
	});

	//append choices to the question
	$('.choice_add').click(function(){
		//console.log(this);
		current_choice_count++;
		html = '<label id="choice_'+current_choice_count+'">Choice '+current_choice_count+'</label><input type="text" id="choice_'+current_choice_count+'" value="Enter choice"></input><br>';
		$('#'+hover_id).append(html);
	});

	$('.choice_remove').click(function(){
		//console.log(this);
		//get input and name elements in current question div and remove it
		$('#'+hover_id+' > #choice_'+current_choice_count).remove();
		$('#'+hover_id+' > br:last-child').remove(); //remove the break below the input
		current_choice_count--;
	});

	//generate a new question div
	$('.question_add').click(function(){
		current_choice_count = 0;
		current_question_count++;
		html = '<div class="question" id="question_'+current_question_count+'"><p class="question_label">Question '+current_question_count+'</p><br><textarea name="prompt"+prompt cols="40" rows="5">Input some text here</textarea><br><label>Choice 1</label><input type="text" id="choice_'+current_choice_count+'" value="Enter choice"></input><input type="button" value="+" id="add_choice" class="choice_add"></input><br></div>';
		$('body').append(html);
	});

	//Should be triggering for all .question class elements... hrm....
	$(document).on("hover", ".question", function(){
		console.log(this);
	});
});