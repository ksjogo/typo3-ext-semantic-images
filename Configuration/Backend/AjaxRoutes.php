<?php
return [
    'semanticimages_quality' => [
        'path'=> '/semanticimages/quality',
        'target'=> Dkd\SemanticImages\Controller\RemoteController::class. '::calculateQualityAjax'
    ],
    'semanticimages_concepts' => [
        'path'=> '/semanticimages/concepts',
        'target'=> Dkd\SemanticImages\Controller\RemoteController::class. '::calculateConceptsAjax'
    ]
];