<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */
function dropbox_list()
{
    
   echo common('dropboxlist',array(),'index');
}
/**
 * Get the absolute path to the Dropbox "files" directory.
 *
 * @return string
 */
function dropbox_get_files_dir_path()
{
    return DROPBOX_DIR . '/files';
}

/**
 * Check if the necessary permissions are set for the files directory.
 *
 * The directory must be both writable and readable.
 *
 * @return boolean
 */
function dropbox_can_access_files_dir()
{
    $filesDir = dropbox_get_files_dir_path();
    return is_readable($filesDir) && is_writable($filesDir);
}

/**
 * Check if the necessary permissions are set for the given file.
 *
 * The file must be readable.
 *
 * @param string $filePath
 * @return boolean
 */
function dropbox_can_access_file($filePath)
{
    return is_readable($filePath);
}

/**
 * Get a list of files in the given directory.
 *
 * The files are returned in natural-sorted, case-insensitive order.
 *
 * @param string $directory Path to directory.
 * @return array Array of filenames in the directory.
 */
function dropbox_dir_list($directory)
{
    
    // create an array to hold directory list
    $filenames = array();

    $iter = new DirectoryIterator($directory);

    foreach ($iter as $fileEntry) {
        if ($fileEntry->isFile()) {
            $filenames[] = $fileEntry->getFilename();
        }
    }

    natcasesort($filenames);

    return $filenames;
}


?>
