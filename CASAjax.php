<?php
/**
 * Created by PhpStorm.
 * User: Deathnerd
 * Date: 7/7/14
 * Time: 7:12 PM
 */

?>

<html>
	<head>
		<script src="scripts/jquery-2.1.0.min.js"></script>
		<script>
			$('button').click(function(){
				$.ajax({
					url: "CASAjax.php",
					data: {
					      username: $("#username").val(),
					      password: $('#password').val()
					}
				})
			});
		</script>
	</head>
	<body>
		<input type="text" id="username"/><br/>
		<input type="text" id="password"/><br/>
		<button>Submit</button><br/>
		<div id="message">
			Stuff here
		</div>
	</body>
</html>