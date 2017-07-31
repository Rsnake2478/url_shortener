<?php

/** @var $app \Silex\Application */

$app->match('/', 'RSnake\\Controller\\IndexController::index');

