<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Soliant\SimpleFM\Authentication\Authenticator;
use Soliant\SimpleFM\Client\Layout\LayoutClientInterface;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;
use Soliant\SimpleFM\Connection\ConnectionInterface;
use Soliant\SimpleFM\Repository\Builder\RepositoryBuilderInterface;

final class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => [
                'factories' => [
                    Authenticator::class => AuthenticatorFactory::class,
                    ConnectionInterface::class => ConnectionFactory::class,
                    RepositoryBuilderInterface::class => RepositoryBuilderFactory::class,
                    ResultSetClientInterface::class => ResultSetClientFactory::class,
                    LayoutClientInterface::class => LayoutClientFactory::class,

                    'soliant.simplefm.expressive.identity-handler' => IdentityHandlerFactory::class,
                    'soliant.simplefm.expressive.logger' => LoggerFactory::class,
                ],
            ],
        ];
    }
}
