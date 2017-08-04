<?php

/** @var $app \Silex\Application */

$app->match('/', 'RSnake\\Controller\\IndexController::indexAction');
$app->get('/show/{shortUrl}', 'RSnake\\Controller\\IndexController::showAction');
$app->get('/{shortUrl}', 'RSnake\\Controller\\IndexController::redirectAction');

