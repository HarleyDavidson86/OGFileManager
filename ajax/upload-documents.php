<?php 
header('Content-Type: application/json');
/*
| Upload an document to a particular page
|
| @_POST['uuid']	string	Page uuid
|
| @return		array
*/

// $_POST
// ----------------------------------------------------------------------------
$uuid = empty($_POST['uuid']) ? false : $_POST['uuid'];
// ----------------------------------------------------------------------------
// Check path traversal on $uuid
if ($uuid) {
	if (mb_stripos($uuid, "/", 0, "UTF-8")) {
		$message = 'Path traversal detected.';
		Log::set($message, LOG_TYPE_ERROR);
		ajaxResponse(1, $message);
	}
}

$images = array();

foreach ($_FILES['documents']['name'] as $uuid=>$filename) {
	// Check for errors
	if ($_FILES['documents']['error'][$uuid] != 0) {
		$message = $L->g('Maximum load file size allowed:').' '.ini_get('upload_max_filesize');
		Log::set($message, LOG_TYPE_ERROR);
		ajaxResponse(1, $message);
	}

	// Convert URL characters such as spaces or quotes to characters
	$filename = urldecode($filename);

	// Check path traversal on $filename
        if (mb_stripos($filename, "/", 0, "UTF-8")) {
            
		$message = 'Path traversal detected.';
		Log::set($message, LOG_TYPE_ERROR);
		ajaxResponse(1, $message);
	}

	// Move from PHP tmp file to Bludit tmp directory
	//Filesystem::mv($_FILES['documents']['tmp_name'][$uuid], PATH_TMP.$filename);

	// Transform the image and generate the thumbnail
	//$image = transformImage(PATH_TMP.$filename, $imageDirectory, $thumbnailDirectory);

	// Delete temporary file
	//Filesystem::rmfile(PATH_TMP.$filename);

//	if ($image) {
//		chmod($image, 0644);
//		$filename = Filesystem::filename($image);
//		array_push($images, $filename);
//	} else {
//		$message = 'Error after transformImage() function.';
//		Log::set($message, LOG_TYPE_ERROR);
//		ajaxResponse(1, $message);
//	}
}

//ajaxResponse(0, 'Images uploaded.', array(
//	'images'=>$images
//));

?>