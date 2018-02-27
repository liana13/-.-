<?php
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$baseUrl = str_replace('/web', '', (new \yii\web\Request)->getBaseUrl());

$connection = new \yii\db\Connection([
    'dsn' => $db['dsn'],
    'username' => $db['username'],
    'password' => $db['password'],
    'charset' => $db['charset'],
]);
$connection->open();
$command = $connection->createCommand('SELECT * FROM config WHERE id=1')
           ->queryOne();
$name = $command['title'].'.рф';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'sourceLanguage'=>'ru_RU',
    'name' => $name,
    'bootstrap' => ['log'],
    'modules' => [
        'filemanager' => [
            'class' => 'pendalf89\filemanager\Module',
            'routes' => [
                'baseUrl' => '/web',
                'basePath' => dirname(__DIR__),
                'uploadPath' => 'upload/posts',
            ],
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'cabinet' => [
            'class' => 'app\modules\cabinet\Module',
        ],
        'owner' => [
            'class' => 'app\modules\owner\Module',
        ],
    ],
    'components' => [
         'yandexMapsApi' => [
    	       	'class' => 'mirocow\yandexmaps\Api',
       	],
        'request' => [
            'baseUrl' => $baseUrl,
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wC5DCtL-PnUn1HPQ-jfsM7jtpYKaOGcT',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
           'class' => 'yii\swiftmailer\Mailer',
           'viewPath' => '@app/mail',
           'transport' => [
               'class' => 'Swift_MailTransport',
           ],
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'baseUrl' => $baseUrl,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                '/admin' => 'admin/default/index',
                '/admin/login' => 'admin/default/login',
                '/cabinet' => 'cabinet/default/index',
                '/owner' => 'owner/default/index',
                // '/<action>' => 'site/<action>',
                '/dogovor/<id:\d+>' => 'site/dogovor',
                '/act/<id:\d+>' => 'site/act',
                '/schet/<id:\d+>' => 'site/schet',
                '/qvintacia/<id:\d+>' => 'site/qvintacia',
                '/actonline/<id:\d+>' => 'site/actonline',
                '/schetonline/<id:\d+>' => 'site/schetonline',
                '/qvintaciaonline/<id:\d+>' => 'site/qvintaciaonline',
                '/<url:[\w-().№’"^*–!]+>' => 'site/view',
                '/update/<alias:[\w-().№’"^*–!]+>' => 'update/index',
                '<controller>' => '<controller>/index',

                '<controller>/<id:\d+>' => '<controller>/view',
                '<controller>/<action>' => '<controller>/<action>',
                'object/<action>/<alias:[\w-]+>' => 'object/<action>',
                '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
                'update/removeimg/<cat:\d+>/<src>' => 'update/removeimg',

                '<module>/<controller>/' => '<module>/<controller>/index',
                '<module>/<controller>/<action>' => '<module>/<controller>/<action>',
                '<module>/<controller>/<action>/<id:\d+>' => '<module>/<controller>/<action>',
            ],
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            // 'siteKey' => '6LfJZyQTAAAAAAyYVj_ZyItbAIIIave4YchyeELL',
            // 'secret' => '6LfJZyQTAAAAAGYoRZPYF9TGvS58BiYj5ajLe9aJ',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
