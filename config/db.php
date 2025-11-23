<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=pedago01c.univ-avignon.fr;port=5432;dbname=etd',
    'username' => 'uapv2401806',
    'password' => '95qxLY',
    'charset' => 'utf8',

    'on afterOpen' => function($event) {
        // On force PostgreSQL à utiliser le schéma fredouil
        $event->sender->createCommand("SET search_path TO fredouil")->execute();
    },
];