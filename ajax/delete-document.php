<?php

header('Content-Type: application/json');

/*
  | Delete an document
  |
  | @_POST['filename']	string	Name of the file to delete
  | @_POST['uuid']	string	Page UUID
  |
  | @return	array
 */

// $_POST
// ----------------------------------------------------------------------------
$filename = isset($_POST['filename']) ? $_POST['filename'] : false;
$path = isset($_POST['path']) ? $_POST['path'] : false;
$uuid = empty($_POST['uuid']) ? false : $_POST['uuid'];
// ----------------------------------------------------------------------------

include '../php/OGFMHelper.php';

// Delete document
if (OGFMHelper::pathFile($path . $filename)) {
    unlink($path . $filename);
}

$default = array('status' => 0, 'message' => 'Document deleted.');
$output = array_merge($default, array());
exit(json_encode($output));
?>