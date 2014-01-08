<?
	$files = glob('quizzes/*.json');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Quiz Creation Page</title>
		<meta name="description" content="Quiz Creation ">
		<meta name="author" content="Wes Gilleland">
		<meta name="published" content="TODO">
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="scripts/create.js"></script>
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
			<p id="pageTitle">PAGE TITLE HERE</p>
		</header>
		<p class="label">Quiz name</p>
		<input type="text" value="Quiz 1" id="quizName">
		<input type="button" value="Add question" class="question_add">
		<input type="button" value="Remove question" class="question_remove">
		<input type="button" value="Construct JSON" id="constructJSON">
		<input type="button" value="Send AJAX" id="sendJSON">
		<select>
			<?
				foreach($files as $file){
					echo '<option>'.$file.'</option>';
				} 
			?>
		</select>
		<div class="question" id="question_1">
		<!-- Begin question prompt and buttons -->
		<p class="question_label">Question 1</p>
		<textarea name="prompt" cols="40" rows="5">Input question here</textarea>
		<br>
		<input type="button" value="Add Choice" id="add_choice" class="choice_add">
		<input type="button" value="Remove Last Choice" id="remove_choice" class="choice_remove"><br>
		<!-- End question prompt and buttons -->
		<!-- Begin choices table -->
		<table width="430" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th width="30" scope="col">#</th>
				<th width="323" scope="col">Value</th>
				<th width="58" scope="col">Correct?</th>
			</tr>
			<tr>
				<td width="30"><div align="center">1</div></td>
				<td width="323"><input type="text" class="choice" value="Enter choice"></td>
				<td width="58"><div align="center">
					<input name="" type="checkbox" class="correctBox" value="">
				</div></td>
				<!-- <td><input type="button" value="Remove Choice" class="remove_inline_choice"></td> -->
			</tr>
		</table>
		<!-- End choices table -->
		</div>
	</body>
</html>