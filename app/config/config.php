<?php

$app['monolog.level'] = Monolog\Logger::DEBUG;
$app['monolog.logfile'] = '../app/logs/application.log';

/**
 * Storage configuration
 */
$app['storage.adapter'] = 'mysql'; // or redis
$app['storage.config'] = array(
    'mysql' => array(
        'host'      => '127.0.0.1',
        'user'      => 'root',
        'pass'      => 'scout',
        'database'  => 'url_shortener'
    ),
    'redis' => array(
        'host'      => '127.0.0.1',
        'port'      => 6379,
        'database'  => 0
    )
);

