<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'cart'     => 'cart/index',
                'cart/add' => 'cart/add-product',
                [
                    'class' => 'frontend\components\UrlRule'
                ]
            ],
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/basic',
                'baseUrl' => '@web/themes/basic',
                'pathMap' => [
                    '@app/views' => '@app/themes/basic',
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'product' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'product-image' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'currency' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'category' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'page' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'supplier' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'category-product-margin' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'cart' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'cart-product' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
    ],
    'params' => $params,
];
