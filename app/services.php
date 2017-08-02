<?php

/* @var $app \Silex\Application */

// Logger
$app->register(
    new \Silex\Provider\MonologServiceProvider(),
    array(
        'monolog.level' => $app['monolog.level'],
        'monolog.logfile' => $app['monolog.logfile']
    )
);

// Twig Service
$app->register(new \Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => realpath(__DIR__ . '/resources/templates'),
    'twig.options' => array(
        'cache' => realpath(__DIR__ . '/cache/templates'),
        'strict_variables' => true
    )
));

//Form provider
$app->register(new \Silex\Provider\FormServiceProvider());
$app->register(new \Silex\Provider\ValidatorServiceProvider());
$app->register(new \Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains'    => array(),
    'locale'                => 'en'
));

//Url shortener service
$app->register(new \RSnake\Provider\UrlShortenerServiceProvider());
