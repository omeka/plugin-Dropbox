<?php 
require_once 'File.php';

class Dropbox_IndexController extends Omeka_Controller_Action
{	
	public function indexAction() {}

	public function addAction()
	{
		$files = $_POST['file'];
        if ($files) {
            try {
    	 	    $this->uploadAction($files);           
            }catch(Exception $e) {
			    throw $e;
		    }
	    }
	}

	protected function uploadAction($files)
	{	
		foreach ($files as $originalName) {
			try{
				$file = new File();
				$oldpath = PLUGIN_DIR.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$originalName;
				$this->checkPermissions($oldpath);
				
                $path = $file->moveFileToArchive($oldpath, $filename, false);
                $file->setDefaults($path);
                $file->original_filename = $originalName;
                $file->createDerivativeImages($path);
                $file->extractMimeMetadata($path);

                $itemMetadata = array(  'public'            => $_POST['public'],
                                        'featured'          => $_POST['featured'],
                                        'collection_id'     => $_POST['collection_id']);
				
                $elementTexts = array('Dublin Core' => array('Title' => array(array('text' => $originalName, 'html' => false))));
                $item = dropbox_insert_item($itemMetadata, $elementTexts);
                
                // Add the tags for the given user.
                $item->addTags($_POST['tags'], current_user()->Entity);
                
				// associate the file with the new Item ID
                 $file->item_id = $item->id;
                 $file->save();
			}catch(Exception $e) {
				$file->delete();
				throw $e;
			}
		}
	}
	
	protected function checkPermissions($path)
	{
		$filesdir = PLUGIN_DIR.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files';
		if (is_readable($path) && is_writable($filesdir)) {
		} else {
			echo ('<h1>Whoops!</h1><p>Check that the dropbox files folder is readable, and individual files are writable.  More information is on the Omeka Codex <a href="http://omeka.org/codex/dropbox_plugin">http://omeka.org/codex/dropbox_plugin</a>');
			die;		
		}
	}
}