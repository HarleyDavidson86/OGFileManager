<?php

header('Content-Type: application/json');

/*
  | Returns a list of documents from a particular page
  |
  | @_POST['pageNumber']	int	Page number for the paginator
  | @_POST['path']	string	abs path to docs folder
  | @_POST['uuid']	string	Page UUID
  |
  | @return	array
 */

// $_POST
// ----------------------------------------------------------------------------
// $_POST['pageNumber'] > 0
$pageNumber = empty($_POST['pageNumber']) ? 1 : (int) $_POST['pageNumber'];
$pageNumber = $pageNumber - 1;

$path = empty($_POST['path']) ? false : $_POST['path'];
$uuid = empty($_POST['uuid']) ? false : $_POST['uuid'];
// ----------------------------------------------------------------------------

include '../php/OGFMHelper.php';

// Get all files from the directory $path, also split the array by numberOfItems
// The function listFiles split in chunks
$listOfFilesByPage = OGFMHelper::listFiles($path, '*', '*', true, 5);

// Check if the page number exists in the chunks
if (isset($listOfFilesByPage[$pageNumber])) {

    // Get only the filename from the chunk
    $files = array();
    foreach ($listOfFilesByPage[$pageNumber] as $file) {
        $filename = basename($file);
        array_push($files, $filename);
    }

    // Returns the number of chunks for the paginator
    // Returns the files inside the chunk
    $default = array('status' => 0, 'message' => 'List of documents and number of chunks.');
    $output = array_merge($default, array(
        'numberOfPages' => count($listOfFilesByPage),
        'files' => $files));
    exit(json_encode($output));
}

$default = array('status' => 0, 'message' => 'List of documents and number of chunks.');
$output = array_merge($default, array(
    'numberOfPages' => 0,
    'files' => array()));
exit(json_encode($output));
?>