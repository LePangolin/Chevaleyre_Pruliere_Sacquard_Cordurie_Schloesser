<?php

// bootstrap.php

use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use App\Container;
use Psr\Log\LogLevel;

use App\Services\UserService;
use App\Services\GalleryService;

use App\Controllers\UserController;
use App\Controllers\GalleryController;
use App\Controllers\HTMLController;

require_once __DIR__ . '/vendor/autoload.php';

$logger =  new Logger('container');
$logger->pushHandler(new StreamHandler(__DIR__ . '/logs/container.log', LogLevel::DEBUG));

$container = new Container($logger, require __DIR__ . '/settings.php');

$container->set("view", function () {
    // return Twig::create(__DIR__ . '/app/Views/templates', ['cache' => __DIR__ . '/app/Views/cache']);
    return Twig::create(__DIR__ . '/src/Views', ['cache' => false]);
});

$container->set(LoggerInterface::class, function (ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Logger($settings['name']);
    $logger->pushHandler(new StreamHandler($settings['path'], LogLevel::DEBUG));
    return $logger;
});

$container->set(EntityManager::class, static function (Container $c): EntityManager {
    /** @var array $settings */
    $settings = $c->get('settings');

    // Use the ArrayAdapter or the FilesystemAdapter depending on the value of the 'dev_mode' setting
    // You can substitute the FilesystemAdapter for any other cache you prefer from the symfony/cache library
    $cache = $settings['doctrine']['dev_mode'] ?
        DoctrineProvider::wrap(new ArrayAdapter()) :
        DoctrineProvider::wrap(new FilesystemAdapter(directory: $settings['doctrine']['cache_dir']));

    $config = Setup::createAttributeMetadataConfiguration(
        $settings['doctrine']['metadata_dirs'],
        $settings['doctrine']['dev_mode'],
        null,
        $cache
    );

    return EntityManager::create($settings['doctrine']['connection'], $config);
});

$container->set(UserService::class, static function (Container $c): UserService {
    return new UserService($c->get(EntityManager::class), $c->get(LoggerInterface::class));
});

$container->set(UserController::class, static function (Container $c): UserController {
    return new UserController($c->get(UserService::class), $c->get("view"));
});

$container->set(GalleryService::class, static function (Container $c): GalleryService {
    return new GalleryService($c->get(EntityManager::class), $c->get(LoggerInterface::class));
});

$container->set(GalleryController::class, static function (Container $c): GalleryController {
    return new GalleryController($c->get(GalleryService::class), $c->get("view"));
});

$container->set(HTMLController::class, static function (Container $c): HTMLController {
    return new HTMLController($c->get('view'));
});

return $container;
