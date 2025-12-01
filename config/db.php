<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=pedago01c;port=5432;dbname=etd',
    'username' => 'uapv2401806',
    'password' => '95qxLY',
    'charset' => 'utf8',

    // Forcer PostgreSQL à utiliser le schéma fredouil
    'schemaMap' => [
        'pgsql' => [
            'class' => 'yii\db\pgsql\Schema',
            'defaultSchema' => 'fredouil',
        ],
    ],

    // FORCER PostgreSQL à utiliser le bon schéma
    'on afterOpen' => function ($event) {
        $event->sender->createCommand("SET search_path TO fredouil")->execute();
    }
];
