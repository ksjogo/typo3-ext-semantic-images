<?php
defined('TYPO3_MODE') || die();

$signalSlotDispatcher->connect(
    \TYPO3\CMS\Core\Resource\ResourceStorage::class,
    \TYPO3\CMS\Core\Resource\ResourceStorageInterface::SIGNAL_PostFileAdd,
    \Dkd\SemanticImages\Slots\FileUpload::class,
    \Dkd\SemanticImages\Slots\FileUpload::SIGNAL_PopulateMetadata
);