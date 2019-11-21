<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

Yii::setAlias('@certified', dirname(__FILE__) . '/production.pem');
Yii::setAlias('@certifiedSandbox', dirname(__FILE__) . '/sandbox.pem');

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'language' => 'pt-BR',
    'charset' => 'UTF-8',
    'timeZone' => 'America/Recife',
    'modules' => [
        'v1' => [
            'basePath' => '@api/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'America/Recife',
            'timeZone' => 'America/Recife',
        ],

        'request' => [
            'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'api\modules\v1\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/user',
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'POST login' => 'login',
                        'POST register' => 'register',
                    ]
                    ],
                    [
                        'class' => 'yii\rest\UrlRule',
                        'controller' => 'v1/device',
                        'extraPatterns' => [
                            'OPTIONS <whatever:.*>' => 'options',
                        ]
                    ]
            ],
        ],
    ],
    'params' => $params,
];