<?php

namespace App;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Application extends \DI\Bridge\Slim\App
{

    public function __construct(array $settings)
    {
        parent::__construct($settings);
    }

    public function configureContainer(\DI\ContainerBuilder $container)
    {
        // $container->addDefinitions([
        //     "settings" => [
        //         'httpVersion' => '1.1',
        //         'responseChunkSize' => 4096,
        //         'outputBuffering' => "append",
        //         'determineRouteBeforeAppMiddleware' => false,
        //         'routerCacheFile' => false,

        //         'displayErrorDetails' => true,
        //         'addContentLengthHeader' => false,
        //     ]
        // ]);
        $container->addDefinitions(__DIR__ . '/../config/dependencies.php');
    }
}