<?php
/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2011
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Dropbox
 */

/**
 * Controller for Dropbox admin pages.
 *
 * @package Dropbox
 */
class Dropbox_IndexController extends Omeka_Controller_Action
{
    /**
     * Front admin page.
     */
    public function indexAction() {}

    /**
     * Add action
     *
     * Batch creates items with Dropbox files.
     */
    public function addAction()
    {
        $fileNames = $_POST['dropbox-files'];
        $uploadedFileNames = array();
        $notUploadedFileNamesToErrorMessages = array();
        if ($fileNames) {
            try {
                $uploadedFileNames = $fileNames;
                $notUploadedFileNamesToErrorMessages = $this->_uploadFiles($fileNames);
                if ($notUploadedFileNamesToErrorMessages) {
                    $uploadedFileNames = array_diff($fileNames, array_keys($notUploadedFileNamesToErrorMessages));
                }
            } catch(Exception $e) {
                $this->flashError($e->getMessage());
                $this->redirect->goto('index');
            }
        } else {
            $this->flashError('You must select a file to upload.');
            $this->redirect->goto('index');
        }
        $this->view->assign(compact('uploadedFileNames', 'notUploadedFileNamesToErrorMessages'));
    }

    /**
     * Create a new Item for each of the given files.
     *
     * @param array $filenames
     * @return array An array of errors that occurred when creating the
     *  Items, indexed by the filename that caused the error.
     */
    protected function _uploadFiles($fileNames)
    {
        if (!dropbox_can_access_files_dir()) {
            throw new Dropbox_Exception('Please make the following dropbox directory writable: ' . dropbox_get_files_dir_path());
        }
        $notUploadedFileNamesToErrorMessages = array();
        foreach ($fileNames as $fileName) {
            $filePath = PLUGIN_DIR.DIRECTORY_SEPARATOR.'Dropbox'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$fileName;
            $item = null;
            try {
                if (!dropbox_can_access_file($filePath)) {
                    throw new Dropbox_Exception('Please make the following dropbox file readable and writable: ' . $filePath);
                }
                $itemMetadata = array(  'public'            => $_POST['dropbox-public'],
                                        'featured'          => $_POST['dropbox-featured'],
                                        'collection_id'     => $_POST['dropbox-collection-id'],
                                        'tags'              => $_POST['dropbox-tags']
                                     );
                $elementTexts = array('Dublin Core' => array('Title' => array(array('text' => $fileName, 'html' => false))));
                $fileMetadata = array('file_transfer_type' => 'Filesystem', 'files' => array($filePath));
                $item = insert_item($itemMetadata, $elementTexts, $fileMetadata);
                release_object($item);
                // delete the file from the dropbox folder
                unlink($filePath);
            } catch(Exception $e) {
                release_object($item);
                $notUploadedFileNamesToErrorMessages[$fileName] = $e->getMessage();
            }
        }
        return $notUploadedFileNamesToErrorMessages;
    }
}
