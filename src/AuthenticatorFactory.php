<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Assert\Assertion;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Authentication\Authenticator;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;

final class AuthenticatorFactory
{
    public function __invoke(ContainerInterface $container) : Authenticator
    {
        $config = $container->get('config');
        Assertion::isArrayAccessible($config);
        Assertion::keyIsset($config, 'simplefm');

        $simpleFmConfig = $config['simplefm'];
        Assertion::isArrayAccessible($simpleFmConfig);
        Assertion::keyIsset($simpleFmConfig, 'authenticator');

        $authenticatorConfig = $simpleFmConfig['authenticator'];
        Assertion::isArrayAccessible($authenticatorConfig);

        Assertion::keyIsset($authenticatorConfig, 'identity_layout');
        Assertion::keyIsset($authenticatorConfig, 'username_field');

        return new Authenticator(
            $container->get(ResultSetClientInterface::class),
            $container->get($authenticatorConfig['identity_handler'] ?? 'soliant.simplefm.expressive.identity-handler'),
            $authenticatorConfig['identity_layout'],
            $authenticatorConfig['username_field']
        );
    }
}
