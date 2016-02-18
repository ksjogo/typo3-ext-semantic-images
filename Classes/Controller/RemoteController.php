<?php
namespace Dkd\SemanticImages\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Dkd\SemanticImages\Utility;

/**
 * Remote Controller of the Semantic Images extension
 * Acces remote certh services and allow proxied calls to them via ajax
 *
 * @category    Controller
 */
class RemoteController extends ActionController
{
    private function client()
    {
        $configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dkd\\SemanticImages\\Configuration');
        $remoteHost = $configuration->getRemoteHost();
        return new \GuzzleHttp\Client(['base_url' => $remoteHost]);
    }


    private function call($method, $uid)
    {
        $temp = Utility::copyToPublic($uid);

        $result = $this->client()->get('methodGET', ['query' => [
            'imagePaths' => $temp['url'],
            'method' => $method
        ]]);

        Utility::decopyFromPublic($temp);

        $xmlstring = (string) $result->getBody();
        $xml = simplexml_load_string($xmlstring);

        return $xml;
    }

    public function calculateQuality($uid)
    {
        $xml = $this->call('quality', $uid);

        $qa = $xml->Quality_Assessment;

        $displayNames = [];
        foreach ($qa->QualityMeasures_list->children() as $node)
        {
            $id = (string)$node->attributes()['id'];
            $displayName = (string) $node;
            $displayNames[$id] = $displayName;
        }

        $order = explode(' ',$qa->QualityMeasures_order);
        $scores = explode(' ', $qa->Image_QualityMeasures_List->Image->confidence_scores);
        $quality = (string) $qa->Image_QualityMeasures_List->Image->imagequality_score;

        $result = [
            'Quality' => $quality,
        ];

        for ($i = 0; $i < count($scores); $i++)
        {
            $score = $scores[$i];
            $labelId = $order[$i];
            $label = $displayNames[$labelId];
            $result[$label] = $score;
        }

        return $result;
    }

    public function calculateQualityAjax(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getParsedBody();
        $uid = $params['uid'];

        $quality = $this->calculateQuality($uid);

        $response->getBody()->write(json_encode($quality));
        return $response;
    }


    public function calculateConcepts($uid)
    {
        $xml = $this->call('concept', $uid);

        $qa = $xml->Concept_detection;

        $displayNames = [];
        foreach ($qa->Concepts_list->children() as $node)
        {
            $id = (string)$node->attributes()['id'];
            $displayName = (string) $node;
            $displayNames[$id] = $displayName;
        }

        $order = explode(' ',$qa->Concepts_order);
        $scores = explode(' ', $qa->Image_Concepts_List->Image->confidence_scores);

        for ($i = 0; $i < count($scores); $i++)
        {
            $score = $scores[$i];
            $labelId = $order[$i];
            $label = $displayNames[$labelId];
            $result[$label] = $score;
        }

        return $result;
    }

    public function calculateConceptsAjax(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getParsedBody();
        $uid = $params['uid'];

        $concepts = $this->calculateConcepts($uid);

        $response->getBody()->write(json_encode($concepts));
        return $response;
    }

    public function search($temp, $word)
    {
        return [370, 371, 373];
    }
}
