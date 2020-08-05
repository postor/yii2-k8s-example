<?php

$DSN = getenv('DSN')?getenv('DSN'):'sqlite:@micro/database.sqlite';

return [
  'id' => 'micro-app',
  // the basePath of the application will be the `micro-app` directory
  'basePath' => __DIR__,
  // this is where the application will find all controllers
  'controllerNamespace' => 'micro\controllers',
  // set an alias to enable autoloading of classes from the 'micro' namespace
  'aliases' => [
    '@micro' => __DIR__,
  ],
  'components' => [
    'db' => [
      'class' => 'yii\db\Connection',
      'dsn' => $DSN,
    ],
    'request' => [
      'parsers' => [
        'application/json' => 'yii\web\JsonParser',
      ]
    ],
  ],  
  'defaultRoute' => 'post',
];
