<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Soliant\SimpleFM\Client\ClientInterface;
use Soliant\SimpleFM\Client\Connection;
use Soliant\SimpleFM\Repository\Builder\RepositoryBuilderInterface;

final class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => [
                'factories' => [
                    Connection::class => ConnectionFactory::class,
                    RepositoryBuilderInterface::class => RepositoryBuilderFactory::class,
                    ClientInterface::class => RestClientFactory::class,
                ],
            ],
        ];
    }
}
