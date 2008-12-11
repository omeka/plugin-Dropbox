<?php 
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2008
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Dropbox
 */

// Define the plugin version.
define('DROPBOX_PLUGIN_VERSION', 0.3);

add_plugin_hook('install', 'dropbox_install');
add_plugin_hook('after_save_form_item', 'dropbox_save_files');
add_plugin_hook('append_to_item_form_upload', 'dropbox_list');
add_plugin_hook('add_routes', 'dropbox_routes');
add_plugin_hook('define_acl', 'dropbox_define_acl');

add_filter('admin_navigation_main', 'dropbox_admin_nav');

function dropbox_admin_nav($navArray)
{
    if (has_permission('Dropbox_Dropbox', 'index')) {
        $navArray = $navArray + array('Dropbox' => uri('dropbox'));
    }
    return $navArray;
}

function dropbox_install()
{
	set_option('dropbox_plugin_version', DROPBOX_PLUGIN_VERSION);
}

function dropbox_routes($router) {
    $router->addRoute(
        'dropbox_default', 
        new Zend_Controller_Router_Route(
            'dropbox/', 
            array(
                'controller' => 'dropbox', 
                'action'     => 'index', 
                'module'     => 'dropbox'
            )
        )
    );
    $router->addRoute(
        'dropbox_actions', 
        new Zend_Controller_Router_Route(
            'dropbox/' . ':action', 
            array(
                'controller' => 'dropbox', 
                'module'     => 'dropbox'
            )
        )
    );
}

function dropbox_define_acl($acl)
{
    $acl->loadResourceList(array('Dropbox_Dropbox' => array('index','add','upload')));
}

function dropbox_list()
{
	include 'views/admin/dropbox/dropboxlist.php';
}  

function dropbox_save_files($item, $post) {

		if(!empty($_POST['file'])) {
			foreach( $_POST['file'] as $filename )
			{ 
				try{
					$file = new File();
					$oldpath = PLUGIN_DIR.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$filename;

                    $path = $file->moveFileToArchive($oldpath, $filename, false);
                    $file->setDefaults($path);
                    $file->original_filename = $name;
                    $file->createDerivativeImages($path);
                    $file->extractMimeMetadata($path);

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