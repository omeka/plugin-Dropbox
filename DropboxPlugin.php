<?php

/**
 * @version $Id$
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2010
 * @package Dropbox
 */

/**
 * Dropbox plugin class
 *
 * @copyright Center for History and New Media, 2010
 * @package Dropbox
 */
define('DROPBOX_DIR',dirname(__FILE__));

require_once DROPBOX_DIR.'/helpers/DropboxFunctions.php';

class DropboxPlugin extends Omeka_Plugin_AbstractPlugin
{
    // Define Hooks
    protected $_hooks = array(
        'after_save_item',
        'admin_items_form_files',
        'define_acl',
    );
    
    //Define Filters
    protected $_filters = array(
        'admin_navigation_main',
    );
    
    /**
     * Dropbox admin navigation filter
     */
    public function filterAdminNavigationMain($nav)
    {
        
        $nav[] = array(
            'label'   => __('Dropbox'),
            'uri'     => url(
                    array(
                        'module'=>'dropbox',
                        'controller'=>'index',
                        'action'=>'index',
                        ), 'default'
                    ),
            'visible' => true
        );
        
        return $nav;
    }
    
    /*
     * Define ACL entry for Dropbox controller.
     */
    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        $acl->addResource('Dropbox_index');
    }
    
    /*
     *  Display the list of files
     */
    protected function dropbox_list()
    {
        echo common('dropboxlist',array(),'index');
    }
    /**
     * Display the dropbox files list on the  itemf form.
     * This simply adds a heading to the output
     */
    public function hookAdminItemsFormFiles()
    {
        echo '<h3>Add Dropbox Files</h3>';
        dropbox_list();
    }
    
    public function hookAfterSaveItem($args)
    {
        $item = $args['record'];
        $post = $args['post'];
        if (!dropbox_can_access_files_dir()) {
            Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->addMessage(__('Please make the following dropbox directory writable: ' . dropbox_get_files_dir_path()), 'warning');
        }
    
        $fileNames = $post['dropbox-files'];
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
                $files = insert_files_for_item($item, 'Filesystem', $filePaths, array('file_ingest_options'=> array('ignore_invalid_files'=>false)));
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
}
