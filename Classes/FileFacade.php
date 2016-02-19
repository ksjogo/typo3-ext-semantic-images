<?php
namespace Dkd\SemanticImages;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FileFacade
 *
 */
class FileFacade extends \TYPO3\CMS\Filelist\FileFacade
{
    public function __construct(\TYPO3\CMS\Core\Resource\FileInterface $resource, $score)
    {
        $this->resource = $resource;
        $this->score = $score;
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
    }

    private $score = 0;

    public function getScore()
    {
        return $this->score;
    }
}
