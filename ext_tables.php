<?php
if (!defined('TYPO3_MODE'))
	die('Access denied.');

if (TYPO3_MODE === 'BE')
{
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Dkd.SemanticImages',
        'file',
        'semanticimages',
        '',
        array(
            'FileList' => 'index, search',
        ),
        array(
            'access' => 'user,group',
            'icon'   => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/semanticimages.svg',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf'
        )
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Semantic Images');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
    'nodeName' => 'SemanticImages',
    'class' =>'Dkd\\SemanticImages\\Form\\Element\\SemanticImages',
    'priority' => 50
];

$GLOBALS['TCA']['sys_file_metadata']['columns']['semanticimage'] = array(
    'label' => 'Semantic Image',
    'config' => array(
        'renderType' => 'SemanticImages',
    )
);

$GLOBALS['TCA']['sys_file_metadata']['columns']['semanticimageinfo'] = array(
    'config' => array(
        'type' => 'user',
        'userFunc' => 'TYPO3\\CMS\\Core\\Resource\\Hook\\FileInfoHook->renderFileMetadataInfo'
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_file_metadata',
    ',--div--;Semantic Image, semanticimageinfo, semanticimage',
    \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE
);
