<?
	/* creates a compressed zip file */
	function create_zip($files = array(),$destination = '',$overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false}
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			//add the files
			foreach($valid_files as $file) {
				$zip->addFile($file,$file);
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
			
			//close the zip -- done!
			$zip->close();
			
			//check to make sure the file exists
			return file_exists($destination);
		}
		else {
			return false;
		}
	}

	//check to see if we can create the file
	if (create_zip(glob('quizzes/*.json'), 'quizzes.zip', true)){
		header('Cache-Control: public');
		header('Content-Descriptor: File Transfer');
		header('Content-Disposition: attachmentfilename="quizzes.zip"');
		header('Content-Type: application/zip');
		header('Content-Transfer-Encoding: binary');

		ob_clean()//clean the output buffer
		flush()//flush it too just to be sure

		readfile('quizzes/quizzes.zip');
		exit();
	}

	//if not, tell us
	header('Content-Type: application/text');
	exit('Failed to deliver file. File not found?');
 