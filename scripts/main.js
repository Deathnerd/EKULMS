//Client logic goes here
var json = eval({
	"_quizName": "Binary Number System",
	"quiz": {
		"questions": {
			"question_1": {
				"prompt": "Convert the binary number 1000001 to its decimal equivalent",
				"choice_1": {
					"value": "32",
					"correct": false
					},
				"choice_2": {
					"value": "65",
					"correct": true
					},
				"choice_3": {
					"value": "97",
					"correct": false
					},
				"choice_4": {
					"value": "127",
					"correct": false
				}
			},
			"question_2": {
				"prompt": "Convert the decimal number 127 to its binary equivalent",
				"choice_1": {
					"value": "1010101",
					"correct": false
					},
				"choice_2": {
					"value": "1111111",
					"correct": true
					},
				"choice_3": {
					"value": "10000000",
					"correct": false
					},
				"choice_4": {
					"value": "11111111",
					"correct": false
				}
			},
			"question_3": {
				"prompt": "What is the sum of two binary numbers, 10101 and 10001",
				"choice_1": {
					"value": "10110",
					"correct": false
					},
				"choice_2": {
					"value": "100110",
					"correct": true
					},
				"choice_3": {
					"value": "100111",
					"correct": false
					},
				"choice_4": {
					"value": "1000110",
					"correct": false
				}
			}
		}
	}
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