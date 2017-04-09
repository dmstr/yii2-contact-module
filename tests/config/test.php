<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DATABASE_DSN'),
            'username' => getenv('DATABASE_USER'),
            'password' => getenv('DATABASE_PASSWORD'),
            'charset' => 'utf8',
            'tablePrefix' => getenv('DATABASE_TABLE_PREFIX'),
        ],
        'settings' => [
            'class' => 'pheme\settings\components\Settings',
        ],
    ],
    'modules' => [
        'contact' => [
            'class' => 'dmstr\modules\contact\Module',
            'layout' => '@backend/views/layouts/main',
        ],
    ],
];