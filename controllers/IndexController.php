	<?php 

require_once MODEL_DIR.DIRECTORY_SEPARATOR.'File.php';

require_once 'Omeka/Controller/Action.php';

class Dropbox_IndexController extends Omeka_Controller_Action
{	

	public function indexAction()
	{	
		return $this->renderDropbox($item);		
	}

	public function addAction()
	{
		$files = $_POST['file'];
		
		if ($_POST) {

	 	$this->uploadAction($files);
		
		return $this->render('dropbox/add.php');
		} else {
			echo "you have improperly accessed the contents of this page";
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
				$file->moveToFileDir($oldpath, $originalName);
				
				$item = new Item;
				$item->title = $originalName;
				$item->public = $_POST['public'];
				$item->featured = $_POST['featured'];
				$item->save();
				
				$file->item_id = $item->id;
				$file->save();
				
//				$item->afterSaveForm($_POST);
				
			}catch(Exception $e) {
				$file->delete();
				throw $e;
			}
		}

	}


}
 
?>
