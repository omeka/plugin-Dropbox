<?php 

define('DROPBOX_PLUGIN_VERSION', 0.1);

add_plugin_hook('initialize', 'dropbox_initialize');

function dropbox_initialize()
{
	add_controllers('controllers');
	add_theme_pages('theme', 'admin');
	add_navigation('Batch Add', 'dropbox', 'archive', array('Entities','add'));
}

add_plugin_hook('install', 'dropbox_install');

function dropbox_install()
{	
		
		set_option('dropbox_plugin_version', DROPBOX_PLUGIN_VERSION);

}

add_plugin_hook('append_to_item_form_upload', 'dropbox_list');

function dropbox_list()
{
	include 'theme/dropbox/dropboxlist.php';
}  

add_plugin_hook('after_save_form_item', 'dropbox_save_files');

function dropbox_save_files($item, $post) {

		if(!empty($_POST['file'])) {
			// Handle the moving of files [DL]
			foreach( $_POST['file'] as $filename )
			{ 
				try{
					$file = new File();
					$path = BASE_DIR.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$filename;
					$file->moveToFileDir($path, $filename);
					$file->item_id = $item->id;
					$file->save();
					fire_plugin_hook('after_upload_file', $file, $item);
				}catch(Exception $e) {
					if(!$file->exists()) {
						$file->unlinkFile();
					}
				throw $e;
				}
			}	
		}
}

?>