<?php
defined('TYPO3_MODE') || die();

\Dkd\CmisService\Execution\Cmis\IndexExecution::addEventListener(
	\Dkd\CmisService\Execution\Cmis\IndexExecution::EVENT_STREAM_SAVED,
	\Dkd\SemanticImages\EventListener\ContentStreamUploadedEventListener::class
);
