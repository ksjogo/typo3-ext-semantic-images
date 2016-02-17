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
        error_log($remoteHost);
        return new \GuzzleHttp\Client(['base_url' => $remoteHost]);
    }


    private function call($method, $uid)
    {
        $temp = Utility::copyToPublic($uid);

        $result = $this->client()->get('methodGET', ['query' => [
            'imagePaths' => $temp['url'],
            'method' => $method
        ]]);

        $xmlstring = (string) $result->getBody();
        $xml = simplexml_load_string($xmlstring);
        $json = json_encode($xml);

        Utility::decopyFromPublic($temp);

        return $json;
    }

    public function calculateQuality($uid)
    {
        return $this->call('quality', $uid);
    }

    public function calculateQualityAjax(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getParsedBody();
        $uid = $params['uid'];

        $quality = $this->calculateQuality($uid);

        $response->getBody()->write($quality);
        return $response;
    }
}
