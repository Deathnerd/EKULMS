<?
	// header("Content-type: application/text");
	$files = glob('quizzes/*.json');

	foreach($files as $file){
		echo "<p>".$file."</p>";
	}
?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		options = $('option').splice(1,$('option').length-1);
		console.log(options);

		$.each(options, function(index){
			word = options[index].value;//extract the value
			console.log("Step 1: "+word);
			slashSplit = word.split('/'); //split by the slash
			console.log("Step 2: "+slashSplit);
			dotSplit = slashSplit[1].split('.');//split by the dot
			console.log("Step 3: "+dotSplit);
			name = dotSplit[0];//extract the quiz name
			console.log("Step 4: "+name);
			options[index].text = name;//replace the value of the current option
		})
	});
</script>
<select>
	<option value="Open Option">Open Option</option>
	<? foreach($files as $file){
		echo "<option>".$file."</option>";
	} ?>
</select>