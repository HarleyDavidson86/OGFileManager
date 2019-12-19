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

$documents = array();
$destPath = $_POST['destPath'];
foreach ($_FILES["documents"]["error"] as $uuid => $filename) {
    if ($filename == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["documents"]["tmp_name"][$uuid];
        // basename() may prevent filesystem traversal attacks;
        // further validation/sanitation of the filename may be appropriate
        $name = basename($_FILES["documents"]["name"][$uuid]);
        move_uploaded_file($tmp_name, $destPath.$name);
        
        chmod($destPath.$name, 0644);
        array_push($documents, $destPath.$name);
    }
}

$default = array('status'=>0, 'message'=>'Documents uploaded.');
$output = array_merge($default, array('documents'=>$documents));
exit (json_encode($output));

?>