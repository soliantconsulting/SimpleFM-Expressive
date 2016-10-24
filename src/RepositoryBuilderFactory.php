<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Assert\Assertion;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;
use Soliant\SimpleFM\Repository\Builder\Metadata\MetadataBuilder;
use Soliant\SimpleFM\Repository\Builder\Proxy\ProxyBuilder;
use Soliant\SimpleFM\Repository\Builder\RepositoryBuilder;

final class RepositoryBuilderFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        Assertion::isArrayAccessible($config);
        Assertion::keyExists($config, 'simplefm');

        $simpleFmConfig = $config['simplefm'];
        Assertion::isArrayAccessible($simpleFmConfig);
        Assertion::keyExists($simpleFmConfig, 'repository_builder');

        $repositoryBuilderConfig = $simpleFmConfig['repository_builder'];
        Assertion::isArrayAccessible($repositoryBuilderConfig);

        Assertion::keyExists('xml_folder', $repositoryBuilderConfig);

        $additionalTypes = [];

        if (isset($repositoryBuilderConfig['additional_types'])) {
            Assertion::isTraversable($repositoryBuilderConfig['additional_types']);

            foreach ($repositoryBuilderConfig['additional_types'] as $typeName => $containerKey) {
                $additionalTypes[$typeName] = $container->get($containerKey);
            }
        }

        return new RepositoryBuilder(
            $container->get(ResultSetClientInterface::class),
            new MetadataBuilder(
                $repositoryBuilderConfig['xml_folder'],
                $additionalTypes
            ),
            new ProxyBuilder(
                $repositoryBuilderConfig['proxy_folder'] ?? null
            )
        );
    }
}
