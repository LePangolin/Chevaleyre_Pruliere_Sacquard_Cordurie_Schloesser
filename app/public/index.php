<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$container = require __DIR__ . '/../bootstrap.php';

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->get('/', App\Controllers\HTMLController::class . ':index')->setName('index');

$app->post('/signUp', App\controllers\UserController::class . ':signUp')->setName('signUp');

$app->post('/logIn', App\Controllers\UserController::class . ':login')->setName('signIn');


$app->run();
