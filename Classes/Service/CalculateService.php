<?php
namespace Dkd\SemanticImages\Service;
use Dkd\SemanticImages\Utility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CalculateService implements \TYPO3\CMS\Core\SingletonInterface
{
    private function analysisClient()
    {
        $configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dkd\\SemanticImages\\Configuration');
        $remoteHost = $configuration->getAnalysisHost();
        return new \GuzzleHttp\Client(['base_url' => $remoteHost]);
    }

    private function analysisCall($method, $uid)
    {
        $temp = Utility::copyToPublic($uid);

        $result = $this->analysisClient()->get('methodGET', ['query' => [
            'imagePaths' => $temp['url'],
            'method' => $method
        ]]);

        Utility::decopyFromPublic($temp);

        $xmlstring = (string) $result->getBody();
        $xml = simplexml_load_string($xmlstring);

        return $xml;
    }

    public function quality($uid)
    {
        $xml = $this->analysisCall('quality', $uid);

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

    public function concepts($uid)
    {
        $xml = $this->analysisCall('concept', $uid);

        $qa = $xml->Concept_detection;

        $displayNames = [];
        foreach ($qa->Concepts_list->children() as $node)
        {
            $id = (string)$node->attributes()['id'];
            $displayName = (string) $node;
            $displayNames[$id] = $displayName;
        }
        foreach ($qa->Concepts_list_annotation->children() as $node)
        {
            $id = (string)$node->attributes()['id'];
            $displayName = (string) $node;
            $displayNames[$id] = $displayName;
        }
        $displayNames = array_map(function($name){
            $name = explode(',', $name)[0];
            $name = str_replace('_', ' ', $name);
            return strtolower($name);
        }, $displayNames);

        $order = explode(' ',$qa->Concepts_order);
        $orderAnnotation = explode(' ',$qa->Concepts_order_annotation);
        $scores = explode(' ', $qa->Image_Concepts_List->Image->confidence_scores);
        $scoresAnnotation = explode(' ', $qa->Image_Concepts_List->Image->confidence_scores_annotation);

        for ($i = 0; $i < count($scores); $i++)
        {
            $score = $scores[$i];
            $labelId = $order[$i];
            $label = $displayNames[$labelId];
            $result[$label] = $score;
        }

        for ($i = 0; $i < count($scores); $i++)
        {
            $score = $scoresAnnotation[$i];
            $labelId = $orderAnnotation[$i];
            $label = $displayNames[$labelId];
            $result[$label] = $score;
        }

        $result = array_filter($result, function($confidence){
            return $confidence >=0.3;
        });

        return $result;
    }

    private function zeedClient()
    {
        $configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Dkd\\SemanticImages\\Configuration');
        $remoteHost = $configuration->getZeedHost();
        return new \GuzzleHttp\Client(['base_url' => $remoteHost]);
    }

    private function searchCall($images, $text)
    {
        $result = $this->zeedClient()->get('GET', ['query' => [
            'imgPath' => $images['url'],
            'diary' => $text['url']
        ]]);

        $xmlstring = (string) $result->getBody();
        $xml = simplexml_load_string($xmlstring);

        error_log("xml found");
        error_log($xml);

        return $xml;
    }

    public function search($images, $text)
    {
        $xml = $this->searchCall($images, $text);

        $mapping = [];
        $imageList = $xml->Image_list;
        foreach ($imageList->children() as $node)
        {
            $name = preg_replace('/[^0-9]/', '', (string)$node->attributes()['filename']);
            $score= (string) $node;
            $mapping[$name] = $score;
        }

        return $mapping;
    }
}