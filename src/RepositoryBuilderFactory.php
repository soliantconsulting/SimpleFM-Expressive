<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use DASPRiD\TreeReader\TreeReader;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;
use Soliant\SimpleFM\Repository\Builder\Metadata\MetadataBuilder;
use Soliant\SimpleFM\Repository\Builder\Proxy\ProxyBuilder;
use Soliant\SimpleFM\Repository\Builder\RepositoryBuilder;
use Soliant\SimpleFM\Repository\Builder\RepositoryBuilderInterface;

final class RepositoryBuilderFactory
{
    public function __invoke(ContainerInterface $container) : RepositoryBuilderInterface
    {
        $config = (new TreeReader($container->get('config'), 'config'))
            ->getChildren('simplefm')
            ->getChildren('repository_builder')
        ;

        $additionalTypes = [];

        if ($config->hasNonNullValue('additional_types')) {
            foreach ($config->getChildren('additional_types') as $item) {
                $additionalTypes[$item->getKey()] = $container->get($item->getString());
            }
        }

        return new RepositoryBuilder(
            $container->get(ResultSetClientInterface::class),
            new MetadataBuilder(
                $config->getString('xml_folder'),
                $additionalTypes
            ),
            new ProxyBuilder(
                $config->hasNonNullValue('proxy_folder') ? $config->getString('proxy_folder') : null
            )
        );
    }
}
