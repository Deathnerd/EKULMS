/*
 * Create.js
 * Author: Wes Gilleland, 2013
 * This will control the page to create quizzes
 */

/*TODO
 * -Add radial button to the side of a question to indicate a correct answer
 * -Get the number of choices already in the div on hover and use those as values for the add/remove choice functions
 * -Oh boy... Work on function to generate json string from all the forms. Oh boy...
 * -Figure out how to remove elements inline, not just last child. Possibly $(this).remove(); followed by a renumbering of the ids?
 */

 //Globals
 var hover_id = ''; //store the current div being hovered over
 var current_choice_count = 1; //how many choices are in the current, working question?
 var current_question_count = 1; //how many questions have we created?

 $(document).ready(function(){

	//append choices to the question
	$(document).on({
		click: function(){
			current_choice_count++;
			//html for a new choice. Broken up for readability
			html = 	'<label id="choice_'+current_choice_count+'">Choice '+current_choice_count+'</label>'+
					'<input type="text" class="choice" id="choice_'+current_choice_count+'" value="Enter choice"></input><br>';
			$('#'+hover_id).append(html);
			console.log(hover_id);
		}
	}, '.choice_add');

	//get input and name elements in current question div and remove it
	$(document).on({
		click: function(){
			//if there's only one choice in the question, then we don't need to remove it
			if ($('#'+hover_id).find('.choice').length !== 1){
				$('#'+hover_id+' > #choice_'+current_choice_count).remove();
				$('#'+hover_id+' > br:last-child').remove(); //remove the break below the input
				current_choice_count--;
			}
		}
	}, '.choice_remove');

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
						'<label>Choice 1</label>'+
						'<input type="text" class="choice" id="choice_'+current_choice_count+'" value="Enter choice"></input>'+
						'<input type="button" value="+" id="add_choice" class="choice_add"></input><input type="button" value="-" id="remove_choice" class="choice_remove"></input><br>'+
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
			});
			labels = $(document).find('.question_label');
			$.each(labels, function (index){
				num = index+1;
				$(labels[index]).text("Question "+num);
			});
			current_question_count = $('.question').length;
		}
	}, '.remove_inline_question');
});