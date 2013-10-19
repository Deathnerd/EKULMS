/*
 * Create.js
 * Author: Wes Gilleland, 2013
 * This will control the page to create quizzes
 */
 var prompt = 1;

 $('document').ready(function(){
 	//generate a new section to enter a question
 	var addChoice = function(question, prompt){
 		var append_multichoice_prompt_data = '<br/>
			<input type="text" name="">Enter choice</input>
			<input type="button" value="+ choice" id="add_choice"></input>';

 		$("#question_"+question).append(append_multichoice_prompt_data);
 		prompt++;
 	}
 }