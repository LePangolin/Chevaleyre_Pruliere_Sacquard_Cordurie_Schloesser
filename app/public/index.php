<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$container = require __DIR__ . '/../bootstrap.php';

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->get('/', App\Controllers\HTMLController::class . ':index')->setName('index');

$app->post('/signUp', App\Controllers\UserController::class . ':signUp')->setName('signUp');

$app->get('/create', App\Controllers\GalleryController::class . ':create')->setName('create');

$app->post('/createGallery', App\Controllers\GalleryController::class . ':createGallery')->setName('createGallery');


$app->run();
