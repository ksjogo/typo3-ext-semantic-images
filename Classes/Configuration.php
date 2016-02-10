<?php
namespace Dkd\SemanticImages;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Semantic Images Configuration
 *
 * Contains the master API for other code to interact
 * or acquire other interaction APIs for CMIS.
 */
class Configuration implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * Configuration array
     *
     * @var array
     */
    protected $settings = null;

    function __construct()
    {
        $this->settings =$confArray = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['semantic_images']);
    }

    /**
     * Get Annotation Host
     *
     * @return string
     */
    public function getRemoteHost()
    {
        return $this->settings['remoteHost'];
    }
}
