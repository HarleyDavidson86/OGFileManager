<?php 
header('Content-Type: application/json');

class Filesystem {

    // Returns an array with the list of files with the absolute path
    // $sortByDate = TRUE, the first file is the newer file
    // $chunk = amount of chunks, FALSE if you don't want to chunk
    public static function listFiles($path, $regex='*', $extension='*', $sortByDate=false, $chunk=false)
    {
        $files = glob($path.$regex.'.'.$extension);

        if (empty($files)) {
                return array();
        }

        if ($sortByDate) {
                usort($files,
                        function($a, $b) {
                                return filemtime($b) - filemtime($a);
                        }
                );
        }

        // Split the list of files into chunks
        // http://php.net/manual/en/function.array-chunk.php
        if ($chunk) {
                return array_chunk($files, $chunk);
        }
        return $files;
    }
}

/*
| Returns a list of docuemnts from a particular page
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
$pageNumber = empty($_POST['pageNumber']) ? 1 : (int)$_POST['pageNumber'];
$pageNumber = $pageNumber - 1;

$path = empty($_POST['path']) ? false : $_POST['path'];
$uuid = empty($_POST['uuid']) ? false : $_POST['uuid'];
// ----------------------------------------------------------------------------

// Get all files from the directory $path, also split the array by numberOfItems
// The function listFiles split in chunks
$listOfFilesByPage = Filesystem::listFiles($path, '*', '*', true, 5);

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
        $default = array('status'=>0, 'message'=>'List of documents and number of chunks.');
        $output = array_merge($default, array(
		'numberOfPages'=>count($listOfFilesByPage),
		'files'=>$files));
        exit (json_encode($output));
}

$default = array('status'=>0, 'message'=>'List of documents and number of chunks.');
$output = array_merge($default, array(
        'numberOfPages'=>0,
        'files'=>array()));
exit (json_encode($output));

?>