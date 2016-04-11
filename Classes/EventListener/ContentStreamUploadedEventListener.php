<?php
namespace Dkd\SemanticImages\EventListener;

use Dkd\CmisService\Constants;
use Dkd\CmisService\Execution\Cmis\IndexExecution;
use Dkd\CmisService\Execution\EventListenerInterface;
use Dkd\CmisService\Execution\ExecutionInterface;
use Dkd\CmisService\Factory\ObjectFactory;
use Dkd\CmisService\Task\TaskInterface;
use Dkd\PhpCmis\Data\DocumentInterface;
use Dkd\PhpCmis\PropertyIds;
use Dkd\PhpCmis\SessionInterface;
use Dkd\SemanticImages\Service\CalculateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ContentStreamUploadedEventListener
 */
class ContentStreamUploadedEventListener implements EventListenerInterface
{
	/**
	 * @param string $event
	 * @param ExecutionInterface $execution
	 * @param TaskInterface $task
	 * @param array $data
	 * @return void
	 */
	public function event($event, ExecutionInterface $execution, TaskInterface $task = NULL, $data = array())
	{
		if ($event === IndexExecution::EVENT_STREAM_SAVED) {
			if ($data['object'] instanceof DocumentInterface) {
				$secondaryTypes = $data['object']->getSecondaryTypes();
				foreach ($secondaryTypes as $secondaryType) {
					if ($secondaryType->getId() === 'dkd:typo3:aspect:assessable') {
						$this->performAnalysisOnContentStreamOfDocument($data['object']);
						break;
					}
				}
			}
		}
	}

	/**
	 * @param DocumentInterface $document
	 * @return void
	 */
	protected function performAnalysisOnContentStreamOfDocument(DocumentInterface $document)
	{
		$session = $this->getCmisSession();
		$score = $this->getCalculationService()->quality($document->getPropertyValue(Constants::CMIS_PROPERTY_TYPO3UID));
		$concepts = $this->getCalculationService()->concepts($document->getPropertyValue(Constants::CMIS_PROPERTY_TYPO3UID));
		$document->updateProperties(array(
			'dkd:typo3:assessable:quality' => $score[0],
			'dkd:typo3:assessable:blur' => $score[1],
			'dkd:typo3:assessable:contrast' => $score[2],
			'dkd:typo3:assessable:darkness' => $score[3],
			'dkd:typo3:assessable:noise' => $score[4],
		));
		// flush all image concept items
		foreach ($concepts as $conceptIdentifier => $similarity) {
			// create new concept items one by one
			$item = $document->getParents()[0]->createItem(array(
				PropertyIds::OBJECT_TYPE_ID => 'dkd:typo3:item:concept_similarity',
				'dkd:typo3:item:concept_similarity:concept_name' => $conceptIdentifier,
				'dkd:typo3:item:concept_similarity:similarity' => (float) $similarity
			));
			$session->createRelationship(array(
				PropertyIds::OBJECT_TYPE_ID => 'dkd:typo3:relation:concept_similarity',
				PropertyIds::SOURCE_ID => $document->getId(),
				PropertyIds::TARGET_ID => $item->getId()
			));
		}
	}

	/**
	 * @return SessionInterface
	 */
	protected function getCmisSession()
	{
		return ObjectFactory::getInstance()->getCmisService()->getCmisSession();
	}

	/**
	 * @return CalculateService
	 */
	protected function getCalculationService()
	{
		// temporary fake return to call the two methods below. Replace with commented-out line in final.
        //return $this;
        return GeneralUtility::makeInstance(CalculateService::class);
    }

	/**
	 * @param integer $uid
	 * @return array
	 */
	protected function quality($uid) {
		return array(0.1, 0.2, 0.3, 0.4, 0.5);
	}

	/**
	 * @param integer $uid
	 * @return array
	 */
	protected function concepts($uid) {
		return array('Concept_1' => 0.5, 'Concept_2' => 0.25);
	}
}
