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
            'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
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
    //	'displayCond' => 'USER:Dkd\\SemanticImages\\UserFunction\\TcaFieldUserFunction->isFieldEnabled',
	'config' => array(
        'renderType' => 'SemanticImages',
        //		'userFunc' => 'Dkd\\SemanticImages\\UserFunction\\TcaFieldUserFunction->renderField'
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file_metadata', ',--div--;Semantic Image,semanticimage', '', '');
