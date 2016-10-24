<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Assert\Assertion;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Authentication\Authenticator;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;

final class AuthenticatorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        Assertion::isArrayAccessible($config);
        Assertion::keyExists($config, 'simplefm');

        $simpleFmConfig = $config['simplefm'];
        Assertion::isArrayAccessible($simpleFmConfig);
        Assertion::keyExists($simpleFmConfig, 'authenticator');

        $authenticatorConfig = $simpleFmConfig['authenticator'];
        Assertion::isArrayAccessible($authenticatorConfig);

        Assertion::keyExists('identity_handler', $authenticatorConfig);
        Assertion::keyExists('identity_layout', $authenticatorConfig);
        Assertion::keyExists('username_field', $authenticatorConfig);

        return new Authenticator(
            $container->get(ResultSetClientInterface::class),
            $container->get($authenticatorConfig['identity_handler']),
            $authenticatorConfig['identity_layout'],
            $authenticatorConfig['username_field']
        );
    }
}
