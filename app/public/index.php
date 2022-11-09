<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$container = require __DIR__ . '/../bootstrap.php';

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->get('/', App\Controllers\GalleryController::class . ':displayPublicGalleries')->setName('index');

$app->post('/signUp', App\Controllers\UserController::class . ':signUp')->setName('signUp');

$app->get('/create', App\Controllers\GalleryController::class . ':create')->setName('create');

$app->post('/createGallery', App\Controllers\GalleryController::class . ':createGallery')->setName('createGallery');

$app->post('/logIn', App\Controllers\UserController::class . ':login')->setName('signIn');

$app->get('/auth', App\Controllers\UserController::class . ':auth')->setName('auth');

$app->get('/gallery/{id}', \App\Controllers\GalleryController::class . ':displayGallery')->setName('displayGallery');

$app->get('/profile/{user}', \App\Controllers\UserController::class . ':displayProfile')->setName('displayProfile');


$app->run();

