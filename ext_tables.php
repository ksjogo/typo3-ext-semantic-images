<?php
if (!defined('TYPO3_MODE'))
	die('Access denied.');

if (TYPO3_MODE === 'BE')
{
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Dkd.' . $_EXTKEY,
        'file',
        'semanticimages',
        '',
        array(
            'FileList' => 'index, search',
        ),
        array(
            'access' => 'user,group',
            'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
            'labels' => 'LLL:EXT:lang/locallang_mod_file_list.xlf'
        )
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Semantic Images');
