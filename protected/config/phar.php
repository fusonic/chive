<?php

return CMap::mergeArray(
    require(__DIR__ . '/main.php'),
    array(
        'runtimePath' => CAP_PATH,
        'import' => array(
            'application.components.phar.*',
        ),
        'components' => array(
            'assetManager' => array(
                'class' => 'PharAssetManager',
                'basePath' => CAP_PATH . DIRECTORY_SEPARATOR . "assets",
            ),
            'urlManager' => array(
                'rules' => array(
                    'assets/<path:(.*)>' => 'asset/default',
                ),
            ),
            'session' => array(
                'savePath' => CAP_PATH . DIRECTORY_SEPARATOR . 'sessions',
            ),
            'request' => array(
                'class' => 'PharHttpRequest',
            ),
        ),
    )
);