//Client logic goes here
var json;

$.getJSON("http://www.wesgilleland.com/projects/quizzes/binary.json", function(returnedJSON) {
	console.log(json);
	json = reteurnedJSON;
});

var generatePage = function(json){
	console.log(json);
	for(var key in json.quiz.questions) {
		var questions = json.quiz.questions[key];
		//console.log(key+" -> "+questions);
		for (var k in questions){
			console.log(k+" -> "+questions[k]);
			var choices = questions[k].choices;
			for (var c in choices){
				console.log(c+" -> "+choices[c]);
			}
		}
	}
}

//generatePage(json);
console.log(json);
var question1=json.quiz.questions.question_1;
for (var key in question1){
	var stuff = question1[key];
	console.log("Stuff in the question_1: "+key+" -> "+stuff);
	if (stuff[key].prompt){
		console.log("yo, this is a prompt!");
	}
	console.log("Stuff in the stuff in question_1: "+key+" ->> "+stuff[key])
}