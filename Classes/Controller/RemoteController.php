<?php
namespace Dkd\SemanticImages\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Dkd\SemanticImages\Service\CalculateService;

/**
 * Remote Controller of the Semantic Images extension
 * Acces remote certh services and allow proxied calls to them via ajax
 *
 * @category    Controller
 */
class RemoteController extends ActionController
{
    public function calculateQualityAjax(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getParsedBody();
        $uid = $params['uid'];

        $quality = GeneralUtility::makeInstance(CalculateService::class)->quality($uid);

        $response->getBody()->write(json_encode($quality));
        return $response;
    }

    public function calculateConceptsAjax(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getParsedBody();
        $uid = $params['uid'];

        $concepts = GeneralUtility::makeInstance(CalculateService::class)->concepts($uid);

        $response->getBody()->write(json_encode($concepts));
        return $response;
    }
}
