<?php 
header('Content-Type: application/json');

// Copy of method in Sanitize class
class Helper {
    public static function pathFile($path, $file=false)
    {
            if ($file!==false){
                    $fullPath = $path.$file;
            } else {
                    $fullPath = $path;
            }

            // Fix for Windows on paths. eg: $path = c:\diego/page/subpage convert to c:\diego\page\subpages
            $fullPath = str_replace('/', DIRECTORY_SEPARATOR, $fullPath);

            $real = realpath($fullPath);

            // If $real is FALSE the file does not exist.
            if ($real===false) {
                    return false;
            }

            // If the $real path does not start with the systemPath then this is Path Traversal.
            if (strpos($fullPath, $real)!==0) {
                    return false;
            }

            return true;
    }
}

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

// Delete document
if (Helper::pathFile($path.$filename)) {
	unlink($path.$filename);
}

$default = array('status'=>0, 'message'=>'Document deleted.');
$output = array_merge($default, array());
exit (json_encode($output));
?>