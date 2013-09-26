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
class Dropbox_IndexController extends Omeka_Controller_AbstractActionController
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
        if ($fileNames) {
            try {
                $uploadedFileNames = $fileNames;
                $fileErrors = $this->_uploadFiles($fileNames);
                if ($fileErrors) {
                    $message = 'Some files were not uploaded. Specific errors for each file follow below:';
                    foreach ($fileErrors as $fileName => $errorMessage) {
                       $message .= "\n- $fileName: $errorMessage";
                    }
                    $this->_helper->flashMessenger($message, 'error');
                    $uploadedFileNames = array_diff($fileNames, array_keys($fileErrors));
                }
            } catch(Exception $e) {
                $this->_helper->flashMessenger($e->getMessage());
                $this->_helper->redirector('index');
            }
        } else {
            $this->_helper->flashMessenger('You must select a file to upload.');
            $this->_helper->redirector('index');
        }
        if ($uploadedFileNames) {
            $message = 'The following files were uploaded:';
            foreach ($uploadedFileNames as $fileName) {
                $message .= "\n- $fileName";
            }
            $this->_helper->flashMessenger($message, 'success');
        }    
        $this->_helper->redirector('index');
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
            throw new Dropbox_Exception('The Dropbox files directory must be both readable and writable.');
        }
        $fileErrors = array();
        foreach ($fileNames as $fileName) {
            $item = null;
            try {
                $filePath = dropbox_validate_file($fileName);
                $itemMetadata = array(
                    'public' => $_POST['dropbox-public'],
                    'featured' => $_POST['dropbox-featured'],
                    'collection_id' => $_POST['dropbox-collection-id']
                        ? $_POST['dropbox-collection-id']
                        : null,
                    'tags' => $_POST['dropbox-tags']
                );
                $elementTexts = array(
                    'Dublin Core' => array(
                        'Title' => array(
                            array('text' => $fileName, 'html' => false)
                        )
                    )
                );
                $fileMetadata = array(
                    'file_transfer_type' => 'Filesystem',
                    'file_ingest_options' => array(
                        'ignore_invalid_files' => false
                    ),
                    'files' => array($filePath)
                );
                $item = insert_item($itemMetadata, $elementTexts, $fileMetadata);
                release_object($item);
                // delete the file from the dropbox folder
                unlink($filePath);
            } catch(Exception $e) {
                release_object($item);
                $fileErrors[$fileName] = $e->getMessage();
            }
        }
        return $fileErrors;
    }
}
