<?php
namespace Dkd\SemanticImages\Controller;

use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Type\Bitmask\JsConfirmation;
use TYPO3\CMS\Core\Resource\FolderInterface;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use \ZipArchive;
use Dkd\SemanticImages\Utility;
use Dkd\SemanticImages\FileFacade;
use Dkd\SemanticImages\Controller\RemoteController;

/**
 * Semantic Images FileListController Wrapper
 *
 * @category    Controller
 */
class FileListController extends \TYPO3\CMS\Filelist\Controller\FileListController
{
    /**
     * The name of the module
     *
     * @var string
     */
    protected $moduleName = 'file_semanticimages';

    /**
     * Initialize the view
     *
     * @param ViewInterface $view The view
     * @return void
     */
    public function initializeView(ViewInterface $view)
    {
        $property = new \ReflectionProperty($view, 'templateView');
        $property->setAccessible(TRUE);
        $templateView = $property->getValue($view);

        $templateView->setTemplateRootPaths(array(
            ExtensionManagementUtility::extPath('semantic_images', 'Resources/Private/Templates/'),
            ExtensionManagementUtility::extPath('filelist', 'Resources/Private/Templates/'),
        ));
        $templateView->setLayoutRootPaths(array(
            ExtensionManagementUtility::extPath('semantic_images', 'Resources/Private/Layouts/'),
            ExtensionManagementUtility::extPath('filelist', 'Resources/Private/Layouts/'),
        ));
        $templateView->setPartialRootPaths(array(
            ExtensionManagementUtility::extPath('semantic_images', 'Resources/Private/Partials/'),
            ExtensionManagementUtility::extPath('filelist', 'Resources/Private/Partials/'),
        ));

        $view->assign('headline', 'ZEED');

        /** @var BackendTemplateView $view */
        parent::initializeView($view);
    }

    /**
     * Adapted behaviour from FileListController
     * Disable all buttons
     */
    protected function registerDocHeaderButtons()
    {
    }

    /**
     * Adapted behaviour from FileListController
     * Disable all buttons
     * @return array All available buttons as an assoc. array
     */
    protected function registerButtons()
    {
        return [];
    }

    /**
     * Adapted behaviour from FileListController
     * Overwrite Headline
     * @return void
     */
    public function indexAction()
    {
        parent::indexAction();

        $headline = 'ZEED in ' . $this->getModuleHeadline();
        $this->view->assign('headline', $headline);
    }

    /**
     * Adapted behaviour from FileListController
     * Semantically search for files by name and pass them with a facade to fluid
     * @param string $searchWord
     * @return void
     */
    public function searchAction($searchWord = '')
    {
        if (empty($searchWord))
            $this->forward('index');

        $temp = $this->createZipArchiveForCurrentFolder();
        $text = Utility::createPublicTempFile('text','.txt');
        file_put_contents($text['name'], $searchWord);

        $remoteController = GeneralUtility::makeInstance(RemoteController::class);
        $mapping = $remoteController->search($temp, $text);

        Utility::removePublicTempFile($temp);
        Utility::removePublicTempFile($text);

        $fileFacades = [];
        if (empty($mapping))
        {
            $this->controllerContext->getFlashMessageQueue('core.template.flashMessages')->addMessage(
                new FlashMessage("Nothing found!", '', FlashMessage::INFO)
            );
        }
        else
        {
            foreach($mapping as $uid => $score)
                $fileFacades[] = new FileFacade(Utility::uid2file($uid), $score);
        }

        $pageRenderer = $this->view->getModuleTemplate()->getPageRenderer();
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Filelist/FileList');
        $this->view->assign('searchWord', $searchWord);
        $this->view->assign('files', $fileFacades);
    }

    /**
     * Build zip for current folder
     * only supporting fileadmin for now
     * @return void
     */
    private function createZipArchiveForCurrentFolder()
    {
        $temp = Utility::createPublicTempFile($this->folderObject->getName(),'.zip');
        $zip = new ZipArchive();
        $zip->open($temp['name'], ZipArchive::CREATE);
        $this->addToZip($this->folderObject, $zip);
        $zip->close();
        return $temp;
    }

    private function addToZip($fileOrFolder, $zip)
    {
        if ($fileOrFolder instanceof FolderInterface)
        {
            foreach($fileOrFolder->getFiles() as $file)
                $this->addToZip($file, $zip);
            foreach($fileOrFolder->getSubfolders() as $folder)
                $this->addToZip($folder, $zip);
        }
        else if ($fileOrFolder instanceof FileInterface && Utility::isImage($fileOrFolder))
        {
            $path = $fileOrFolder->getUid() . '.' . $fileOrFolder->getExtension();
            $zip->addFromString($path, $fileOrFolder->getContents());
        }
    }
}
