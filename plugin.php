<?php
/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2011
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Dropbox
 */

define('DROPBOX_DIR', dirname(__FILE__));

// Define hooks
add_plugin_hook('before_save_form_item', 'dropbox_save_files');
add_plugin_hook('admin_append_to_items_form_files', 'dropbox_items_form_files');
add_plugin_hook('define_acl', 'dropbox_define_acl');

// Define filters
add_filter('admin_navigation_main', 'dropbox_admin_nav');

/**
 * Add Dropbox entry to admin navbar.
 *
 * @return array
 */
function dropbox_admin_nav($navArray)
{
    if (has_permission('Dropbox_Index', 'index')) {
        $navArray['Dropbox'] = uri(array('module'=>'dropbox', 'controller'=>'index', 'action'=>'index'), 'default');
    }
    return $navArray;
}

/**
 * Define ACL entry for Dropbox controller.
 */
function dropbox_define_acl($acl)
{
    $acl->loadResourceList(array('Dropbox_Index' => array('index','add')));
}

/**
 * Display the list of files in the dropbox.
 */
function dropbox_list()
{
    common('dropboxlist', array(), 'index');
}

/**
 * Display the dropbox files list on the item form.
 * This simply adds a heading to the output.
 */
function dropbox_items_form_files()
{
    echo '<h3>Add Dropbox Files</h3>';
    dropbox_list();
}

/**
 * Add files from the Dropbox to an item.
 *
 * @param Item $item
 * @param array $post
 */
function dropbox_save_files($item, $post)
{
    if (!dropbox_can_access_files_dir()) {
        throw new Dropbox_Exception('Please make the following dropbox directory writable: ' . dropbox_get_files_dir_path());
    }

    $fileNames = $_POST['dropbox-files'];
    if ($fileNames) {
        $filePaths = array();
        foreach($fileNames as $fileName) {
            $filePath = PLUGIN_DIR.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$fileName;
            if (!dropbox_can_access_file($filePath)) {
                throw new Dropbox_Exception('Please make the following dropbox file readable and writable: ' . $filePath);
            }
            $filePaths[] = $filePath;
        }

        $files = array();
        try {
            $files = insert_files_for_item($item, 'Filesystem', $filePaths);
        } catch (Omeka_File_Ingest_InvalidException $e) {
            release_object($files);
            $item->addError('Dropbox', $e->getMessage());
            return;
        } catch (Exception $e) {
            release_object($files);
            throw $e;
        }
        release_object($files);

        // delete the files
        foreach($filePaths as $filePath) {
            try {
                unlink($filePath);
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
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
