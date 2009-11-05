<?php 
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Dropbox
 */

// Define hooks
add_plugin_hook('after_save_form_item', 'dropbox_save_files');
add_plugin_hook('admin_append_to_items_form_files', 'dropbox_list');
add_plugin_hook('define_acl', 'dropbox_define_acl');

// Define filters
add_filter('admin_navigation_main', 'dropbox_admin_nav');

function dropbox_admin_nav($navArray)
{
    if (has_permission('Dropbox_Index', 'index')) {
        $navArray['Dropbox'] = uri(array('module'=>'dropbox', 'controller'=>'index', 'action'=>'index'), 'default');
    }
    return $navArray;
}

function dropbox_define_acl($acl)
{
    $acl->loadResourceList(array('Dropbox_Index' => array('index','add')));
}

function dropbox_list()
{
	common('dropboxlist', array(), 'index');
}  

function dropbox_save_files($item, $post) 
{
    $fileNames = $_POST['dropbox-files'];
	if ($fileNames) {
	    $filePaths = array();
		foreach($fileNames as $fileName) { 
			$filePath = PLUGIN_DIR.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$fileName; 
			dropbox_check_permissions($filePath);
			$filePaths[] = $filePath;                  
		}
		
		$files = array();
		try {
			$files = insert_files_for_item($item, 'Filesystem', $filePaths);
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

function dropbox_check_permissions($filePath)
{
	$filesDir = PLUGIN_DIR.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files';
	if (!(is_readable($filePath) && is_writable($filesDir))) {
		echo '<h1>Whoops!</h1><p>Check that the dropbox files folder is readable, and individual files are writable.  More information is on the Omeka Codex <a href="http://omeka.org/codex/dropbox_plugin">http://omeka.org/codex/dropbox_plugin</a>';
		die;		
	}
}

function dropbox_dir_list($directory) 
{
    // create an array to hold directory list
    $fileNames = array();

    // create a handler for the directory
    $handler = opendir($directory);

    // keep going until all files in directory have been read
    while ($fileName = readdir($handler)) {

        // if $file isn't this directory or its parent, 
        // add it to the results array
		$isdir = is_dir($fileName);
        if (($fileName != '.') && ($fileName != '..') && ($fileName != '.svn') && ($isdir != '1'))
            $fileNames[] = $fileName;
    }

    // tidy up: close the handler
    closedir($handler);

    return $fileNames;
}