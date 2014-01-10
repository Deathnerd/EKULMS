<!DOCTYPE html>
<html>
	<head>
		<title>CSC 185 Practice Exams</title>
		<meta name="description" content="Practice Exams for CSC 185">
		<meta name="author" content="Wes Gilleland">
		<meta name="published" content="TODO">
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="scripts/main.js"></script>
		<link type="text/css" rel="stylesheet" href="styles/reset.css">
		<link type="text/css" rel="stylesheet" href="styles/main.css">
	</head>
	<body>
		<header id="topNav">
			<div id="logo">
				LOGO HERE
			</div>
			<div id="dropdown">
				<p>DROPDOWN HERE</p>
			</div>
			<p id="pageTitle">Quiz</p>
		</header>
		<p>Select a quiz here:</p>
		<select>
			<?
				$files = glob('quizzes/*.json'); //find all the quiz files
				//populate the dropdown
				foreach($files as $file){
					echo '<option>'.$file.'</option>';
				}
			?>
		</select>
		<button type="button" id="load">Load Quiz</button>
	</body>
</html>
