<?php
namespace Dkd\SemanticImages\Form\Element;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use Dkd\SemanticImages\Utility;
/**
 * Generation of elements of the type "user"
 */
class SemanticImages extends AbstractFormElement
{
    /**
     * User defined field type
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render()
    {
        $parameterArray = $this->data['parameterArray'];
        $parameterArray['table'] = $this->data['tableName'];
        $parameterArray['field'] = $this->data['fieldName'];
        $parameterArray['row'] = $this->data['databaseRow'];
        $parameterArray['parameters'] = isset($parameterArray['fieldConf']['config']['parameters'])
                                      ? $parameterArray['fieldConf']['config']['parameters']
                                      : array();
        $resultArray = $this->initializeResultArray();
        $resultArray['requireJsModules'][] = 'TYPO3/CMS/SemanticImages/Form';
        $resultArray['html'] = $this->renderField($parameterArray,$this);
        return $resultArray;
    }

    /**
     * @param array $parameters
     */
    public function renderField(array $parameters)
    {
        $uid = $parameters['row']['file'][0];
        $fileObject = Utility::uid2file($parameters['row']['file'][0]);
        $name = $fileObject->getIdentifier() ;
        return '<div data-uid="' . $uid . '" data-name="' . $name . '" class="semanticimage" />';
    }
}