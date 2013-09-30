<?php

/**
 * Print the list of files in the Dropbox.
 */
function dropbox_list()
{
    echo common('dropboxlist', array(), 'index');
}
/**
 * Get the absolute path to the Dropbox "files" directory.
 *
 * @return string
 */
function dropbox_get_files_dir_path()
{
    return DROPBOX_DIR . DIRECTORY_SEPARATOR . 'files';
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

/**
 * Check if the given file can be uploaded from the dropbox.
 *
 * @throws Dropbox_Exception
 * @return string Validated path to the file
 */
function dropbox_validate_file($fileName)
{
    $dropboxDir = dropbox_get_files_dir_path();
    $filePath = $dropboxDir .DIRECTORY_SEPARATOR . $fileName;
    $realFilePath = realpath($filePath);
    // Ensure the path is actually within the dropbox files dir.
    if (!$realFilePath
        || strpos($realFilePath, $dropboxDir . DIRECTORY_SEPARATOR) !== 0) {
        throw new Dropbox_Exception(__('The given path is invalid.'));
    }
    if (!file_exists($realFilePath)) {
        throw new Dropbox_Exception(__('The file "%s" does not exist or is not readable.', $fileName));
    }
    if (!is_readable($realFilePath)) {
        throw new Dropbox_Exception(__('The file "%s" is not readable.', $fileName));
    }
    return $realFilePath;
}
