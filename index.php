<!DOCTYPE html>
<html>
	<head>
		<title>CSC 185 Practice Exams</title>
		<meta name="description" content="Practice Exams for CSC 185">
		<meta name="author" content="Wes Gilleland">
		<meta name="published" content="TODO">
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="scripts/main.js"></script>
		<script type="text/javascript">
			//checks if the clicked radial was the correct answer
			var answer_check = function(correct, number){
				if (correct)
				{
					$("#box_"+number).text("Correct!");
					$("#box_"+number).css("background-color", "rgba(0, 255, 0, .5)");
					$("#box_"+number).css("border", "solid 1px rgba(0, 255, 0, .75)");
				}
				else 
				{
					$("#box_"+number).text("Incorrect");
					$("#box_"+number).css("background-color", "rgba(255, 0, 0, .5)");
					$("#box_"+number).css("border", "solid 1px rgba(255, 0, 0, .75)");
				}
			}
		</script>
		<link type="text/css" rel="stylesheet" href="styles/reset.css">
		<link type="text/css" rel="stylesheet" href="styles/main.css">
	</head>
	<body>
		<button type="button" id="fetch">FETCH</button>
	</body>
</html>
