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
        $container->addDefinitions(__DIR__ . '/../config/dependencies.php');
    }
}
