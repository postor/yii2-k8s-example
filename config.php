<?php

$config = [
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
      'dsn' => getenv('DB_DSN') ? getenv('DB_DSN') : 'sqlite:@micro/database.sqlite',
      'username' => getenv('DB_USERNAME'),
      'password' => getenv('DB_PASSWORD'),
    ],
  ],
  'defaultRoute' => 'post',
];

if (defined('IS_WEB') && IS_WEB) {
  $config['components']['request'] = [
    'parsers' => [
      'application/json' => 'yii\web\JsonParser',
    ]
  ];
}

return $config;
