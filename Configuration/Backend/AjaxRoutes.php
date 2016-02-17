<?php
return [
    'semanticimages_quality' => [
        'path'=> '/semanticimages/quality',
        'target'=> Dkd\SemanticImages\Controller\RemoteController::class. '::calculateQualityAjax'
    ]
];