<?php
namespace Dkd\SemanticImages\Slots;
use Dkd\SemanticImages\Service\CalculateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Core\Resource\Driver\AbstractDriver;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Resource\File;

class FileUpload
{
    const SIGNAL_PopulateMetadata = 'populateMetadata';

    public function populateMetadata(\TYPO3\CMS\Core\Resource\FileInterface $file, \TYPO3\CMS\Core\Resource\Folder $folder)
    {

        $qualities = GeneralUtility::makeInstance(CalculateService::class)->quality($file->getUid());
        $message = '';
        foreach($qualities as $key => $value)
            $message .=  $key . ' => ' . $value . "\n";

        $this->flash($message);
    }

    public function flash($message, $severity = \TYPO3\CMS\Core\Messaging\FlashMessage::OK)
{
    $flashMessage = GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Messaging\FlashMessage::class,
        $message,
        '',
        $severity,
        true
    );
    $flashMessageService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
    $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
    $defaultFlashMessageQueue->enqueue($flashMessage);
}
}