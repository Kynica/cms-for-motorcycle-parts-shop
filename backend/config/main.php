<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
                'order' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'order-status' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'customer' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
    ],
    'params' => $params,
    'as AccessBehavior' => [
        'class'         => 'backend\components\AccessBehavior',
        'redirectUri' => '/site/login'
    ],
];
