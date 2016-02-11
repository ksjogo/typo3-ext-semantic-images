<?php
namespace Dkd\SemanticImages\Controller;

use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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
			ExtensionManagementUtility::extPath('filelist', 'Resources/Private/Templates/'),
			ExtensionManagementUtility::extPath('semantic_images', 'Resources/Private/Templates/'),
		));
		$templateView->setLayoutRootPaths(array(
			ExtensionManagementUtility::extPath('filelist', 'Resources/Private/Layouts/'),
			ExtensionManagementUtility::extPath('semantic_images', 'Resources/Private/Layouts/'),
		));
		$templateView->setPartialRootPaths(array(
			ExtensionManagementUtility::extPath('filelist', 'Resources/Private/Partials/'),
			ExtensionManagementUtility::extPath('semantic_images', 'Resources/Private/Partials/'),
		));
		/** @var BackendTemplateView $view */
		parent::initializeView($view);
	}

}
