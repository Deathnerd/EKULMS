<?php
/**
 * Created by PhpStorm.
 * User: Deathnerd
 * Date: 4/15/14
 * Time: 9:59 PM
 */
	//debug flag
	$debug = true;

	function prettyPrint($var){
		?>
		<pre>
	    <?php
			print_r($var);
	    ?>
	    </pre>
	<?php
	}
