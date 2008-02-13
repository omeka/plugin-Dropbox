	<?php 

require_once MODEL_DIR.DIRECTORY_SEPARATOR.'File.php';

require_once 'Omeka/Controller/Action.php';

class DropboxController extends Omeka_Controller_Action
{	

	public function indexAction()
	{	
		return $this->renderDropbox($item);		
	}

	public function addAction()
	{
		$files = $_POST['file'];
		
		if ($_POST && $files) {
	 	$this->uploadAction($files);
		
		return $this->render('dropbox/add.php');
		} else {
			echo "<h2>Whoa there!</h2>  You have submitted the Dropbox form without selecting any items.  Go back and try again";
		}
	}
	
	protected function renderDropbox($item)
	{
		return $this->render('dropbox/index.php', compact('item'));
	}

	protected function uploadAction($files)
	{	
		foreach ($files as $originalName) {
			
			try{
				$file = new File();
				$oldpath = PLUGIN_DIR.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$originalName;
				$this->checkPermissions($oldpath);
				
				$file->moveToFileDir($oldpath, $originalName);
				
				$item = new Item;
				$item->title = $originalName;
				$item->public = $_POST['public'];
				$item->featured = $_POST['featured'];
				$item->save();
				
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
