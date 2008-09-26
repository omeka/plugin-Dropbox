<?php 
require_once MODEL_DIR.DIRECTORY_SEPARATOR.'File.php';

class Dropbox_DropboxController extends Omeka_Controller_Action
{	
    public function init() {}

	public function indexAction() {}

	public function addAction()
	{
		$files = $_POST['file'];

		if ($_POST && $files) {
	 	$this->uploadAction($files);

		} else {
			echo "<h2>Whoa there!</h2>  You have submitted the Dropbox form without selecting any items.  Go back and try again";
		}
	}

	protected function uploadAction($files)
	{	
		foreach ($files as $originalName) {
			
			try{
				$file = new File();
				$oldpath = PLUGIN_DIR.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$originalName;
				$this->checkPermissions($oldpath);
				
                // formerly contained in moveToFileDir()
                $path = $file->moveFileToArchive($oldpath, $filename, false);
                $file->setDefaults($path);
                $file->original_filename = $originalName;
                $file->createDerivativeImages($path);
                $file->extractMimeMetadata($path);
				
				$item = new Item;
				$item->public = $_POST['public'];
				$item->featured = $_POST['featured'];
				$item->collection_id = $_POST['collection_id'];
				$item->save();
				
				$elementText = new ElementText;
				$elementText->record_id = $item->id;
				$elementText->record_type_id = 1;
				$elementText->element_id = 53;
				$elementText->text = $originalName;
				$elementText->save();
				
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
 
?>
