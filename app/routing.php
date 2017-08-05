<?php

/** @var $app \Silex\Application */

//Main routes
$app->match('/', 'RSnake\\Controller\\IndexController::indexAction');
$app->get('/show/{shortUrl}', 'RSnake\\Controller\\IndexController::showAction');
$app->get('/{shortUrl}', 'RSnake\\Controller\\IndexController::redirectAction');

//Api routes
$app->get('/api/{shortUrl}', 'RSnake\\Controller\\ApiController::getAction');
$app->post('/api/', 'RSnake\\Controller\\ApiController::createAction');
