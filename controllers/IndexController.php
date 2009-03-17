<?php 

class Dropbox_IndexController extends Omeka_Controller_Action
{	
	public function indexAction() {}

	public function addAction()
	{
		$files = $_POST['file'];
        if ($files) {
            try {
    	 	    $this->uploadAction($files);           
            } catch(Exception $e) {
			    throw $e;
		    }
	    }
	}

	protected function uploadAction($fileNames)
	{	
		$filePaths = array();
		foreach($fileNames as $fileName) {
		    $filePath = PLUGIN_DIR.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$fileName;
		    dropbox_check_permissions($filePath);
		    $filePaths[] = $filePath;
		}
		
		for($i = 0; $i < count($filePaths); $i++) {
		    
		    $filePath = $filePaths[$i];
		    $fileName = $fileNames[$i];
		    
			$item = null;
			try{
                $itemMetadata = array(  'public'            => $_POST['public'],
                                        'featured'          => $_POST['featured'],
                                        'collection_id'     => $_POST['collection_id'],
                                        'tags'              => $_POST['tags']
                                     );
                $elementTexts = array('Dublin Core' => array('Title' => array(array('text' => $fileName, 'html' => false))));
                $fileMetadata = array('file_transfer_type' => 'Filesystem', 'files' => array($filePath));
                
                $item = insert_item($itemMetadata, $elementTexts, $fileMetadata);
                release_object($item);
                 
			} catch(Exception $e) {
				release_object($item);
				throw $e;
			}
		}
		
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