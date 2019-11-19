<?php
declare(strict_types=1);

namespace Infrastructure\Ui\Console;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Application extends \Symfony\Component\Console\Application
{
    private $containerBuilder = null;

    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        $this->initContainer();
        parent::__construct($name, $version);
    }

    private function initContainer(): void
    {
        $cb = new ContainerBuilder();
        $loader = new YamlFileLoader($cb, new FileLocator(__DIR__));
        $loader->load('services.yaml');
        $this->containerBuilder = $cb;
    }

    public function container(): ContainerBuilder
    {
        return $this->containerBuilder;
    }
}

