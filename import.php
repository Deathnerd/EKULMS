<?
/**
 * Created by PhpStorm.
 * User: Deathnerd
 * Date: 4/16/14
 * Time: 7:09 PM
 */

if(!is_file('requires/Test.php')){
	echo "Test.php not found. Check your installation.";
	exit("Required file not found");
}

if(!is_file('imports/*.json')){
	echo "Import folder empty. Exiting";
	exit();
}
