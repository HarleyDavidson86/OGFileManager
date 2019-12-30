<?php
class OGFMHelper {
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
    
    // Copy of method in Sanitize class
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
