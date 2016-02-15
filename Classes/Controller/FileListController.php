<?php
namespace Dkd\SemanticImages\Controller;

use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Type\Bitmask\JsConfirmation;

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

        $view->assign('headline', 'Semantic Search');

        /** @var BackendTemplateView $view */
        parent::initializeView($view);
	}

    /**
     * Registers the Icons into the docheader
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function registerDocHeaderButtons()
    {
    }

    /**
     * Create the panel of buttons for submitting the form or otherwise perform operations.
     *
     * @return array All available buttons as an assoc. array
     */
    protected function registerButtons()
    {
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        parent::indexAction();

        $headline = 'Semantic Search in ' . $this->getModuleHeadline();
        $this->view->assign('headline', $headline);
    }

    /**
     * Search for files by name and pass them with a facade to fluid
     *
     * @param string $searchWord
     */
    public function searchAction($searchWord = '')
    {
        if (empty($searchWord)) {
            $this->forward('index');
        }

        $fileFacades = [];
        $files = $this->fileRepository->searchByName($this->folderObject, $searchWord);

        if (empty($files)) {
            $this->controllerContext->getFlashMessageQueue('core.template.flashMessages')->addMessage(
                new FlashMessage("Nothing found!", '', FlashMessage::INFO)
            );
        } else {
            foreach ($files as $file) {
                $fileFacades[] = new \TYPO3\CMS\Filelist\FileFacade($file);
            }
        }

        $pageRenderer = $this->view->getModuleTemplate()->getPageRenderer();
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Filelist/FileList');

        $this->view->assign('searchWord', $searchWord . "123");
        $this->view->assign('files', $fileFacades);
        $this->view->assign('settings', [
            'jsConfirmationDelete' => $this->getBackendUser()->jsConfirmation(JsConfirmation::DELETE)
        ]);
    }
}
